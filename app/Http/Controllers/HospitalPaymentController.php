<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHospitalPaymentRequest;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Expense;
use App\Models\ExpenseCatagory;
use App\Models\HospitalPayment;
use App\Models\PatientHistory;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HospitalPaymentController extends Controller
{
    /**
     * Display the hospital payments, sales, and financial summary dashboard.
     */
    public function index(Request $request): View
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $filterType = $request->input('filter', 'all');
        $selectedCategory = $request->input('category', 'all');
        $search = $request->input('search');

        // Preset quick filters
        if ($filterType === 'today') {
            $startDate = Carbon::today()->toDateString();
            $endDate = Carbon::today()->toDateString();
        } elseif ($filterType === 'week') {
            $startDate = Carbon::now()->startOfWeek()->toDateString();
            $endDate = Carbon::now()->endOfWeek()->toDateString();
        } elseif ($filterType === 'month') {
            $startDate = Carbon::now()->startOfMonth()->toDateString();
            $endDate = Carbon::now()->endOfMonth()->toDateString();
        }

        // Base query for HospitalPayment records
        $paymentsQuery = HospitalPayment::with(['patient', 'doctor', 'appointment'])->latest('payment_date')->latest('id');

        if ($startDate && $endDate) {
            $paymentsQuery->whereBetween('payment_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $paymentsQuery->whereDate('payment_date', '>=', $startDate);
        } elseif ($endDate) {
            $paymentsQuery->whereDate('payment_date', '<=', $endDate);
        }

        if ($selectedCategory && $selectedCategory !== 'all') {
            $paymentsQuery->where('category', $selectedCategory);
        }

        if ($search) {
            $paymentsQuery->where(function ($q) use ($search): void {
                $q->where('invoice_no', 'like', "%{$search}%")
                    ->orWhere('patient_name', 'like', "%{$search}%")
                    ->orWhere('patient_phone', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        $payments = $paymentsQuery->paginate(15, ['*'], 'payments_page')->withQueryString();

        // Query for appointments stream
        $appointmentsQuery = Appointment::with('doctor')->latest();
        if ($startDate && $endDate) {
            $appointmentsQuery->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
        } elseif ($startDate) {
            $appointmentsQuery->whereDate('created_at', '>=', Carbon::parse($startDate));
        } elseif ($endDate) {
            $appointmentsQuery->whereDate('created_at', '<=', Carbon::parse($endDate));
        }

        $appointments = $appointmentsQuery->paginate(15, ['*'], 'appointments_page')->withQueryString();
        $allAppointments = (clone $appointmentsQuery)->get();

        // 3. Query for Daily Expenses stream (paginated for ledger tab)
        $expensesQuery = Expense::query()->latest('date')->latest('id');
        if ($startDate && $endDate) {
            $expensesQuery->whereBetween('date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $expensesQuery->whereDate('date', '>=', $startDate);
        } elseif ($endDate) {
            $expensesQuery->whereDate('date', '<=', $endDate);
        }

        if ($selectedCategory && $selectedCategory !== 'all') {
            $expensesQuery->where('catagory', $selectedCategory);
        }

        if ($search) {
            $expensesQuery->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('catagory', 'like', "%{$search}%")
                    ->orWhere('amount', 'like', "%{$search}%");
            });
        }

        $expenses = $expensesQuery->paginate(15, ['*'], 'expenses_page')->withQueryString();

        // All expenses in date range (unfiltered by search/pagination for summary metrics)
        $allExpensesQuery = Expense::query();
        if ($startDate && $endDate) {
            $allExpensesQuery->whereBetween('date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $allExpensesQuery->whereDate('date', '>=', $startDate);
        } elseif ($endDate) {
            $allExpensesQuery->whereDate('date', '<=', $endDate);
        }
        $allExpensesInRange = $allExpensesQuery->get();
        $dailyExpenses = (float) $allExpensesInRange->sum('amount');

        // Dynamic breakdown of daily expenses by category
        $expenseCategoryBreakdown = $allExpensesInRange->groupBy('catagory')->map(function ($group): array {
            return [
                'count' => $group->count(),
                'total' => (float) $group->sum('amount'),
            ];
        });

        // 4. Calculate Category Breakdown totals for payments (for active date range)
        $paymentTotalsQuery = HospitalPayment::query();
        if ($startDate && $endDate) {
            $paymentTotalsQuery->whereBetween('payment_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $paymentTotalsQuery->whereDate('payment_date', '>=', $startDate);
        } elseif ($endDate) {
            $paymentTotalsQuery->whereDate('payment_date', '<=', $endDate);
        }

        $allPaymentsInRange = (clone $paymentTotalsQuery)->get();

        // Dynamic breakdown of custom payments by category
        $paymentCategoryBreakdown = $allPaymentsInRange->groupBy('category')->map(function ($group): array {
            return [
                'count' => $group->count(),
                'total' => (float) $group->sum('net_amount'),
                'gross' => (float) $group->sum('amount'),
                'discount' => (float) $group->sum('discount'),
            ];
        });

        // 1. Consultation Billing (Appointments consultation fees + custom consultation payments)
        $appointmentConsultationFee = (float) $allAppointments->sum(function ($apt): float {
            return $apt->doctor ? (float) $apt->doctor->fee : 0.0;
        });
        $customConsultationSales = (float) $allPaymentsInRange->where('category', 'Consultation')->sum('net_amount');
        $consultationSales = $appointmentConsultationFee + $customConsultationSales;

        // 2. Pharmacy / Medicines
        $medicineSales = (float) $allPaymentsInRange->filter(fn ($p) => str_contains(strtolower($p->category), 'pharmacy') || str_contains(strtolower($p->category), 'medicine'))->sum('net_amount');

        // 3. General Procedures
        $procedureSales = (float) $allPaymentsInRange->filter(fn ($p) => str_contains(strtolower($p->category), 'procedure'))->sum('net_amount');

        // 4. Diagnostics / Lab
        $labSales = (float) $allPaymentsInRange->filter(fn ($p) => str_contains(strtolower($p->category), 'lab') || str_contains(strtolower($p->category), 'diagnostic'))->sum('net_amount');

        // 5. Emergency
        $emergencySales = (float) $allPaymentsInRange->filter(fn ($p) => str_contains(strtolower($p->category), 'emergency'))->sum('net_amount');

        // 6. Custom / other categories dynamically aggregated
        $standardCategoryKeywords = ['consultation', 'pharmacy', 'medicine', 'procedure', 'lab', 'diagnostic', 'emergency'];
        $customCategorySales = [];
        foreach ($paymentCategoryBreakdown as $catName => $data) {
            $isStandard = false;
            foreach ($standardCategoryKeywords as $kw) {
                if (str_contains(strtolower($catName), $kw)) {
                    $isStandard = true;
                    break;
                }
            }
            if (! $isStandard) {
                $customCategorySales[$catName] = $data['total'];
            }
        }
        $otherSales = (float) array_sum($customCategorySales);
        $additionalSales = $emergencySales + $otherSales;

        // Discounts Applied
        $totalDiscount = (float) $allPaymentsInRange->sum('discount');

        // Gross Total Sales (All services billing + consultations)
        $totalSales = $appointmentConsultationFee + (float) $allPaymentsInRange->sum('net_amount');

        $staffPayroll = (float) Staff::sum('salary');

        // Net hospital revenue (Total sales minus operational expenses)
        $netRevenue = max(0.0, $totalSales - $dailyExpenses);

        // Patient accounts & dues
        $totalPatients = PatientHistory::count();
        $totalDueAmount = (float) PatientHistory::sum('due_amount');
        $totalWalletAmount = (float) PatientHistory::sum('wallet_amount');
        $totalAppointments = $allAppointments->count();
        $totalCustomPayments = $allPaymentsInRange->count();
        $totalDailyExpensesCount = $allExpensesInRange->count();

        // Averages and rates
        $totalTransactions = $totalAppointments + $totalCustomPayments;
        $avgFeePerVisit = $totalTransactions > 0 ? round($totalSales / $totalTransactions, 2) : 0.0;
        $collectionRate = ($totalSales + $totalDueAmount) > 0
            ? round(($totalSales / ($totalSales + $totalDueAmount)) * 100, 1)
            : 100.0;

        // Categories from Database table expense_catagory
        $expenseCategories = ExpenseCatagory::query()->orderBy('name')->get();

        // Data for Add Payment Modal
        $doctors = Doctor::orderBy('name')->get();
        $patients = PatientHistory::orderBy('name')->get();
        $paymentMethods = HospitalPayment::PAYMENT_METHODS;

        return view('hospital-payments', compact(
            'payments',
            'appointments',
            'expenses',
            'expenseCategories',
            'expenseCategoryBreakdown',
            'paymentCategoryBreakdown',
            'customCategorySales',
            'consultationSales',
            'appointmentConsultationFee',
            'customConsultationSales',
            'medicineSales',
            'procedureSales',
            'labSales',
            'emergencySales',
            'otherSales',
            'additionalSales',
            'totalDiscount',
            'totalSales',
            'dailyExpenses',
            'staffPayroll',
            'netRevenue',
            'totalPatients',
            'totalDueAmount',
            'totalWalletAmount',
            'totalAppointments',
            'totalCustomPayments',
            'totalDailyExpensesCount',
            'avgFeePerVisit',
            'collectionRate',
            'startDate',
            'endDate',
            'filterType',
            'selectedCategory',
            'search',
            'doctors',
            'patients',
            'paymentMethods'
        ));
    }

    /**
     * Store a newly created custom hospital payment.
     */
    public function store(StoreHospitalPaymentRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $amount = (float) $validated['amount'];
        $discount = (float) ($validated['discount'] ?? 0.0);
        $netAmount = max(0.0, $amount - $discount);

        // If patient_id provided, look up patient details if phone was not manually provided
        $patient = null;
        if (! empty($validated['patient_id'])) {
            $patient = PatientHistory::find($validated['patient_id']);
            if ($patient && empty($validated['patient_phone'])) {
                $validated['patient_phone'] = $patient->phone;
            }
        }

        $paidAmount = $validated['status'] === 'Paid' ? $netAmount : (float) ($validated['paid_amount'] ?? $netAmount);

        $payment = HospitalPayment::create([
            'invoice_no' => HospitalPayment::generateInvoiceNo(),
            'category' => $validated['category'],
            'patient_id' => $validated['patient_id'] ?? null,
            'patient_name' => $validated['patient_name'],
            'patient_phone' => $validated['patient_phone'] ?? null,
            'doctor_id' => $validated['doctor_id'] ?? null,
            'appointment_id' => $validated['appointment_id'] ?? null,
            'amount' => $amount,
            'discount' => $discount,
            'net_amount' => $netAmount,
            'paid_amount' => $paidAmount,
            'payment_method' => $validated['payment_method'],
            'payment_date' => $validated['payment_date'],
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        // If paid via Patient Wallet Credit, deduct from patient's wallet balance
        if ($patient && $validated['payment_method'] === 'Wallet') {
            $currentWallet = (float) $patient->wallet_amount;
            $newWallet = max(0.0, $currentWallet - $paidAmount);
            $patient->update(['wallet_amount' => $newWallet]);
        }

        // If status is Pending or Partial, reflect remaining balance in patient's due amount
        if ($patient && in_array($validated['status'], ['Pending', 'Partial'])) {
            $remainingDue = max(0.0, $netAmount - $paidAmount);
            if ($remainingDue > 0) {
                $currentDue = (float) $patient->due_amount;
                $patient->update(['due_amount' => $currentDue + $remainingDue]);
            }
        }

        return redirect()->route('hospital-payments')
            ->with('success', "Payment #{$payment->invoice_no} of Rs. ".number_format($netAmount, 0)." for {$payment->category} recorded successfully!");
    }

    /**
     * Remove a hospital payment record.
     */
    public function destroy(int $id): RedirectResponse
    {
        $payment = HospitalPayment::findOrFail($id);
        $invoice = $payment->invoice_no;
        $payment->delete();

        return redirect()->route('hospital-payments')
            ->with('success', "Payment #{$invoice} deleted successfully.");
    }
}
