<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Expense;
use App\Models\HospitalPayment;
use App\Models\PatientHistory;
use App\Models\Staff;
use Carbon\Carbon;
use FPDF;
use Illuminate\Http\Response;

require_once base_path('vendor/setasign/fpdf/fpdf.php');

class HospitalPdfDocument extends FPDF
{
    public string $reportTitle = 'Hospital Report';

    public string $subTitle = '';

    public function Header(): void
    {
        $logoPath = public_path('images/medical-logo-png-897.png');
        $hasLogo = file_exists($logoPath);

        if ($hasLogo) {
            $this->Image($logoPath, 10, 8, 16);
            $this->SetX(30);
        }

        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(15, 23, 42); // slate-900
        $this->Cell(0, 7, 'CITY HOSPITAL & MEDICAL CENTER', 0, 1, $hasLogo ? 'L' : 'C');

        $this->SetFont('Arial', 'B', 11);
        $this->SetTextColor(37, 99, 235); // blue-600
        if ($hasLogo) {
            $this->SetX(30);
        }
        $this->Cell(0, 6, strtoupper($this->reportTitle), 0, 1, $hasLogo ? 'L' : 'C');

        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(100, 116, 139); // slate-500
        if ($hasLogo) {
            $this->SetX(30);
        }
        $this->Cell(0, 5, 'Generated: '.now()->format('d M Y, h:i A').' | '.$this->subTitle, 0, 1, $hasLogo ? 'L' : 'C');

        $this->Ln(3);
        $this->SetDrawColor(203, 213, 225); // slate-300
        $this->SetLineWidth(0.3);
        $this->Line(10, $this->GetY(), $this->GetPageWidth() - 10, $this->GetY());
        $this->Ln(4);
    }

    public function Footer(): void
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(148, 163, 184); // slate-400
        $this->SetDrawColor(226, 232, 240);
        $this->Line(10, $this->GetY(), $this->GetPageWidth() - 10, $this->GetY());
        $this->Ln(2);
        $this->Cell(0, 8, 'Hospital Management System | Confidential Medical Record | Page '.$this->PageNo().' of {nb}', 0, 0, 'C');
    }
}

class PdfReportService
{
    /**
     * Generate Patient History Directory PDF.
     */
    public function exportPatientsPdf(?string $search = null): Response
    {
        $query = PatientHistory::query();
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('cnic', 'like', "%{$search}%");
            });
        }
        $patients = $query->latest()->get();

        $pdf = new HospitalPdfDocument('P', 'mm', 'A4');
        $pdf->reportTitle = 'Patients Directory & History Report';
        $pdf->subTitle = ! empty($search) ? "Filtered by keyword: '{$search}'" : 'All registered patients';
        $pdf->AliasNbPages();
        $pdf->AddPage();

        // Summary Statistics Row
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(241, 245, 249);
        $pdf->SetTextColor(30, 41, 59);
        $totalDues = $patients->sum('due_amount');
        $totalWallet = $patients->sum('wallet_amount');

        $pdf->Cell(63, 7, 'Total Patients: '.$patients->count(), 1, 0, 'C', true);
        $pdf->Cell(64, 7, 'Total Outstanding Due: Rs. '.number_format($totalDues, 2), 1, 0, 'C', true);
        $pdf->Cell(63, 7, 'Total Wallet Credit: Rs. '.number_format($totalWallet, 2), 1, 1, 'C', true);
        $pdf->Ln(3);

        // Table Header
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetFillColor(30, 58, 138); // Navy blue
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(12, 7, '#ID', 1, 0, 'C', true);
        $pdf->Cell(45, 7, 'Patient Name', 1, 0, 'L', true);
        $pdf->Cell(14, 7, 'Age', 1, 0, 'C', true);
        $pdf->Cell(32, 7, 'Phone Number', 1, 0, 'C', true);
        $pdf->Cell(37, 7, 'CNIC / Identity', 1, 0, 'C', true);
        $pdf->Cell(25, 7, 'Due (Rs.)', 1, 0, 'R', true);
        $pdf->Cell(25, 7, 'Wallet (Rs.)', 1, 1, 'R', true);

        // Table Body
        $pdf->SetFont('Arial', '', 8);
        $fill = false;
        foreach ($patients as $patient) {
            $pdf->SetFillColor($fill ? 248 : 255, $fill ? 250 : 255, $fill ? 252 : 255);
            $pdf->SetTextColor(30, 41, 59);

            $pdf->Cell(12, 6, (string) $patient->id, 1, 0, 'C', $fill);
            $pdf->Cell(45, 6, substr($patient->name, 0, 24), 1, 0, 'L', $fill);
            $pdf->Cell(14, 6, (string) ($patient->age ?? 'N/A'), 1, 0, 'C', $fill);
            $pdf->Cell(32, 6, (string) ($patient->phone ?? '-'), 1, 0, 'C', $fill);
            $pdf->Cell(37, 6, (string) ($patient->cnic ?? 'Not Provided'), 1, 0, 'C', $fill);

            // Red due amount if > 0
            if ($patient->due_amount > 0) {
                $pdf->SetTextColor(185, 28, 28); // Red
            }
            $pdf->Cell(25, 6, number_format((float) $patient->due_amount, 2), 1, 0, 'R', $fill);

            // Green wallet credit if > 0
            $pdf->SetTextColor($patient->wallet_amount > 0 ? 21 : 30, $patient->wallet_amount > 0 ? 128 : 41, $patient->wallet_amount > 0 ? 61 : 59);
            $pdf->Cell(25, 6, number_format((float) $patient->wallet_amount, 2), 1, 1, 'R', $fill);

            $fill = ! $fill;
        }

        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="patients_report_'.date('Ymd_His').'.pdf"');
    }

    /**
     * Generate Individual Patient Profile & History Statement PDF.
     */
    public function exportSinglePatientPdf(PatientHistory $patient): Response
    {
        $pdf = new HospitalPdfDocument('P', 'mm', 'A4');
        $pdf->reportTitle = 'Patient Profile & Medical Record';
        $pdf->subTitle = 'MRN / Patient ID: #'.$patient->id;
        $pdf->AliasNbPages();
        $pdf->AddPage();

        // Patient Bio Card
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(238, 242, 255); // Indigo light
        $pdf->SetTextColor(49, 46, 129);
        $pdf->Cell(0, 8, 'PATIENT INFORMATION', 1, 1, 'L', true);

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(15, 23, 42);

        $pdf->Cell(45, 7, 'Full Name:', 1, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(50, 7, $patient->name, 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(45, 7, 'Patient ID / Code:', 1, 0, 'L');
        $pdf->Cell(50, 7, '#'.str_pad((string) $patient->id, 5, '0', STR_PAD_LEFT), 1, 1, 'L');

        $pdf->Cell(45, 7, 'Age:', 1, 0, 'L');
        $pdf->Cell(50, 7, ($patient->age ? $patient->age.' Years' : 'Not recorded'), 1, 0, 'L');
        $pdf->Cell(45, 7, 'Phone Number:', 1, 0, 'L');
        $pdf->Cell(50, 7, $patient->phone ?? '-', 1, 1, 'L');

        $pdf->Cell(45, 7, 'CNIC Number:', 1, 0, 'L');
        $pdf->Cell(50, 7, $patient->cnic ?? 'Not provided', 1, 0, 'L');
        $pdf->Cell(45, 7, 'Registration Date:', 1, 0, 'L');
        $pdf->Cell(50, 7, $patient->created_at ? $patient->created_at->format('d M Y') : 'N/A', 1, 1, 'L');

        $pdf->Ln(4);

        // Account Financials
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(241, 245, 249);
        $pdf->SetTextColor(30, 41, 59);
        $pdf->Cell(0, 8, 'FINANCIAL & BILLING STATUS', 1, 1, 'L', true);

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(95, 7, 'Outstanding Due Balance: Rs. '.number_format((float) $patient->due_amount, 2), 1, 0, 'L');
        $pdf->Cell(95, 7, 'Available Wallet Credit: Rs. '.number_format((float) $patient->wallet_amount, 2), 1, 1, 'L');

        $pdf->Ln(6);

        // Associated Appointments log
        $appointments = Appointment::query()
            ->where('phone', $patient->phone)
            ->orWhere('name', 'like', "%{$patient->name}%")
            ->with('doctor')
            ->latest()
            ->get();

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(238, 242, 255);
        $pdf->SetTextColor(49, 46, 129);
        $pdf->Cell(0, 8, 'APPOINTMENT VISITS & CONSULTATIONS ('.$appointments->count().' Records)', 1, 1, 'L', true);

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetFillColor(30, 58, 138);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(15, 6, '#ID', 1, 0, 'C', true);
        $pdf->Cell(35, 6, 'Date & Time', 1, 0, 'L', true);
        $pdf->Cell(55, 6, 'Doctor Specialist', 1, 0, 'L', true);
        $pdf->Cell(30, 6, 'Status', 1, 0, 'C', true);
        $pdf->Cell(55, 6, 'Consultation Diagnosis', 1, 1, 'L', true);

        $pdf->SetFont('Arial', '', 8);
        $fill = false;
        if ($appointments->isEmpty()) {
            $pdf->SetTextColor(100, 116, 139);
            $pdf->Cell(190, 8, 'No previous hospital appointments found for this patient phone/record.', 1, 1, 'C');
        } else {
            foreach ($appointments as $apt) {
                $pdf->SetFillColor($fill ? 248 : 255, $fill ? 250 : 255, $fill ? 252 : 255);
                $pdf->SetTextColor(30, 41, 59);

                $pdf->Cell(15, 6, (string) $apt->id, 1, 0, 'C', $fill);
                $aptDate = ($apt->created_at ? $apt->created_at->format('d/m/y') : '').' '.($apt->time ?? '');
                $pdf->Cell(35, 6, substr($aptDate, 0, 20), 1, 0, 'L', $fill);
                $pdf->Cell(55, 6, substr($apt->doctor?->name ?? 'Dr. Unassigned', 0, 30), 1, 0, 'L', $fill);
                $pdf->Cell(30, 6, (string) $apt->status, 1, 0, 'C', $fill);
                $pdf->Cell(55, 6, substr($apt->diagnosis ?: ($apt->notes ?: 'General Checkup'), 0, 32), 1, 1, 'L', $fill);

                $fill = ! $fill;
            }
        }

        // Signature section
        $pdf->Ln(15);
        $pdf->SetDrawColor(148, 163, 184);
        $pdf->Line(15, $pdf->GetY(), 70, $pdf->GetY());
        $pdf->Line(135, $pdf->GetY(), 190, $pdf->GetY());
        $pdf->Ln(2);
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->Cell(80, 5, 'Hospital Records Officer', 0, 0, 'L');
        $pdf->Cell(110, 5, 'Medical Officer / Stamp', 0, 1, 'R');

        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="patient_history_'.$patient->id.'.pdf"');
    }

    /**
     * Generate Appointments Schedule PDF Report.
     */
    public function exportAppointmentsPdf(?string $search = null, ?string $status = null): Response
    {
        $query = Appointment::query()->with('doctor');
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        if (! empty($status) && $status !== 'All') {
            $query->where('status', $status);
        }
        $appointments = $query->latest()->get();

        $pdf = new HospitalPdfDocument('P', 'mm', 'A4');
        $pdf->reportTitle = 'Appointments Schedule & Registry';
        $pdf->subTitle = 'Total Appointments: '.$appointments->count().($status ? " | Filter: {$status}" : '');
        $pdf->AliasNbPages();
        $pdf->AddPage();

        // Table Header
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetFillColor(30, 58, 138);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(12, 7, '#ID', 1, 0, 'C', true);
        $pdf->Cell(45, 7, 'Patient Name', 1, 0, 'L', true);
        $pdf->Cell(28, 7, 'Phone', 1, 0, 'C', true);
        $pdf->Cell(18, 7, 'Gender/Age', 1, 0, 'C', true);
        $pdf->Cell(45, 7, 'Attending Doctor', 1, 0, 'L', true);
        $pdf->Cell(22, 7, 'Time', 1, 0, 'C', true);
        $pdf->Cell(20, 7, 'Status', 1, 1, 'C', true);

        // Table Body
        $pdf->SetFont('Arial', '', 8);
        $fill = false;
        foreach ($appointments as $apt) {
            $pdf->SetFillColor($fill ? 248 : 255, $fill ? 250 : 255, $fill ? 252 : 255);
            $pdf->SetTextColor(30, 41, 59);

            $pdf->Cell(12, 6, (string) $apt->id, 1, 0, 'C', $fill);
            $pdf->Cell(45, 6, substr($apt->name, 0, 24), 1, 0, 'L', $fill);
            $pdf->Cell(28, 6, substr($apt->phone, 0, 16), 1, 0, 'C', $fill);
            $pdf->Cell(18, 6, ($apt->gender ? substr($apt->gender, 0, 1) : '-').'/'.($apt->age ?? '-'), 1, 0, 'C', $fill);
            $pdf->Cell(45, 6, substr($apt->doctor?->name ?? 'N/A', 0, 24), 1, 0, 'L', $fill);
            $pdf->Cell(22, 6, (string) ($apt->time ?? '-'), 1, 0, 'C', $fill);

            // Status color badge text
            if ($apt->status === 'Completed') {
                $pdf->SetTextColor(21, 128, 61); // Green
            } elseif ($apt->status === 'Pending') {
                $pdf->SetTextColor(180, 83, 9); // Amber
            } elseif ($apt->status === 'Cancelled') {
                $pdf->SetTextColor(185, 28, 28); // Red
            } else {
                $pdf->SetTextColor(2, 132, 199); // Blue
            }
            $pdf->Cell(20, 6, (string) $apt->status, 1, 1, 'C', $fill);

            $fill = ! $fill;
        }

        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="appointments_report_'.date('Ymd_His').'.pdf"');
    }

    /**
     * Generate Doctors Directory PDF Report.
     */
    public function exportDoctorsPdf(): Response
    {
        $doctors = Doctor::query()->orderBy('name')->get();

        $pdf = new HospitalPdfDocument('P', 'mm', 'A4');
        $pdf->reportTitle = 'Medical Specialists & Doctors Directory';
        $pdf->subTitle = 'Total Active Doctors: '.$doctors->count();
        $pdf->AliasNbPages();
        $pdf->AddPage();

        // Table Header
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetFillColor(30, 58, 138);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(12, 7, '#ID', 1, 0, 'C', true);
        $pdf->Cell(45, 7, 'Doctor Name', 1, 0, 'L', true);
        $pdf->Cell(35, 7, 'Speciality', 1, 0, 'L', true);
        $pdf->Cell(28, 7, 'PMDC Reg.', 1, 0, 'C', true);
        $pdf->Cell(28, 7, 'Phone Number', 1, 0, 'C', true);
        $pdf->Cell(22, 7, 'Fee (Rs.)', 1, 0, 'R', true);
        $pdf->Cell(20, 7, 'Duty Days', 1, 1, 'C', true);

        // Table Body
        $pdf->SetFont('Arial', '', 8);
        $fill = false;
        foreach ($doctors as $doctor) {
            $pdf->SetFillColor($fill ? 248 : 255, $fill ? 250 : 255, $fill ? 252 : 255);
            $pdf->SetTextColor(30, 41, 59);

            $pdf->Cell(12, 6, (string) $doctor->id, 1, 0, 'C', $fill);
            $pdf->Cell(45, 6, substr($doctor->name, 0, 24), 1, 0, 'L', $fill);
            $pdf->Cell(35, 6, substr($doctor->speciality, 0, 20), 1, 0, 'L', $fill);
            $pdf->Cell(28, 6, (string) ($doctor->pmdc ?? 'N/A'), 1, 0, 'C', $fill);
            $pdf->Cell(28, 6, (string) ($doctor->phone ?? '-'), 1, 0, 'C', $fill);
            $pdf->Cell(22, 6, number_format((float) $doctor->fee, 2), 1, 0, 'R', $fill);
            $pdf->Cell(20, 6, substr($doctor->duty_days ?? 'All week', 0, 14), 1, 1, 'C', $fill);

            $fill = ! $fill;
        }

        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="doctors_report_'.date('Ymd_His').'.pdf"');
    }

    /**
     * Generate Staff Members Directory PDF Report.
     */
    public function exportStaffPdf(): Response
    {
        $staff = Staff::query()->orderBy('name')->get();

        $pdf = new HospitalPdfDocument('P', 'mm', 'A4');
        $pdf->reportTitle = 'Hospital Staff & Personnel Directory';
        $pdf->subTitle = 'Total Staff Members: '.$staff->count().' | Monthly Payroll: Rs. '.number_format($staff->sum('salary'), 2);
        $pdf->AliasNbPages();
        $pdf->AddPage();

        // Table Header
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetFillColor(30, 58, 138);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(12, 7, '#ID', 1, 0, 'C', true);
        $pdf->Cell(50, 7, 'Employee Name', 1, 0, 'L', true);
        $pdf->Cell(38, 7, 'Designation', 1, 0, 'L', true);
        $pdf->Cell(32, 7, 'Phone Number', 1, 0, 'C', true);
        $pdf->Cell(30, 7, 'Salary (Rs.)', 1, 0, 'R', true);
        $pdf->Cell(28, 7, 'Joined Date', 1, 1, 'C', true);

        // Table Body
        $pdf->SetFont('Arial', '', 8);
        $fill = false;
        foreach ($staff as $emp) {
            $pdf->SetFillColor($fill ? 248 : 255, $fill ? 250 : 255, $fill ? 252 : 255);
            $pdf->SetTextColor(30, 41, 59);

            $pdf->Cell(12, 6, (string) $emp->id, 1, 0, 'C', $fill);
            $pdf->Cell(50, 6, substr($emp->name, 0, 28), 1, 0, 'L', $fill);
            $pdf->Cell(38, 6, substr($emp->designation ?? 'Staff', 0, 22), 1, 0, 'L', $fill);
            $pdf->Cell(32, 6, (string) ($emp->phone ?? '-'), 1, 0, 'C', $fill);
            $pdf->Cell(30, 6, number_format((float) $emp->salary, 2), 1, 0, 'R', $fill);
            $pdf->Cell(28, 6, $emp->created_at ? $emp->created_at->format('d M Y') : 'N/A', 1, 1, 'C', $fill);

            $fill = ! $fill;
        }

        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="staff_report_'.date('Ymd_His').'.pdf"');
    }

    /**
     * Generate Expenses Ledger PDF Report.
     */
    public function exportExpensesPdf(): Response
    {
        $expenses = Expense::query()->latest('date')->get();
        $totalExpense = $expenses->sum('amount');

        $pdf = new HospitalPdfDocument('P', 'mm', 'A4');
        $pdf->reportTitle = 'Daily Operational Expenses Audit';
        $pdf->subTitle = 'Total Entries: '.$expenses->count().' | Total Expenditure: Rs. '.number_format($totalExpense, 2);
        $pdf->AliasNbPages();
        $pdf->AddPage();

        // Table Header
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetFillColor(30, 58, 138);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(15, 7, '#ID', 1, 0, 'C', true);
        $pdf->Cell(30, 7, 'Expense Date', 1, 0, 'C', true);
        $pdf->Cell(70, 7, 'Description / Title', 1, 0, 'L', true);
        $pdf->Cell(45, 7, 'Category', 1, 0, 'L', true);
        $pdf->Cell(30, 7, 'Amount (Rs.)', 1, 1, 'R', true);

        // Table Body
        $pdf->SetFont('Arial', '', 8);
        $fill = false;
        foreach ($expenses as $item) {
            $pdf->SetFillColor($fill ? 248 : 255, $fill ? 250 : 255, $fill ? 252 : 255);
            $pdf->SetTextColor(30, 41, 59);

            $pdf->Cell(15, 6, (string) $item->id, 1, 0, 'C', $fill);
            $pdf->Cell(30, 6, $item->date ? Carbon::parse($item->date)->format('d M Y') : '-', 1, 0, 'C', $fill);
            $pdf->Cell(70, 6, substr($item->name, 0, 40), 1, 0, 'L', $fill);
            $pdf->Cell(45, 6, substr($item->catagory ?? 'General', 0, 25), 1, 0, 'L', $fill);
            $pdf->Cell(30, 6, number_format((float) $item->amount, 2), 1, 1, 'R', $fill);

            $fill = ! $fill;
        }

        // Summary Total Row
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(241, 245, 249);
        $pdf->SetTextColor(15, 23, 42);
        $pdf->Cell(160, 8, 'TOTAL ACCUMULATED OPERATIONAL EXPENDITURE:', 1, 0, 'R', true);
        $pdf->SetTextColor(185, 28, 28);
        $pdf->Cell(30, 8, 'Rs. '.number_format($totalExpense, 2), 1, 1, 'R', true);

        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="expenses_report_'.date('Ymd_His').'.pdf"');
    }

    /**
     * Export Hospital Payments and Financial Summary Report to PDF.
     */
    public function exportPaymentsPdf(?string $startDate = null, ?string $endDate = null): Response
    {
        $paymentsQuery = HospitalPayment::with(['doctor', 'patient'])->latest('payment_date');
        if ($startDate && $endDate) {
            $paymentsQuery->whereBetween('payment_date', [$startDate, $endDate]);
        }
        $payments = $paymentsQuery->get();

        $aptQuery = Appointment::with('doctor')->latest();
        if ($startDate && $endDate) {
            $aptQuery->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
        }
        $appointments = $aptQuery->get();

        $aptConsultation = (float) $appointments->sum(fn ($apt): float => $apt->doctor ? (float) $apt->doctor->fee : 0.0);
        $customConsultation = (float) $payments->where('category', 'Consultation')->sum('net_amount');
        $consultationSales = $aptConsultation + $customConsultation;
        $pharmacySales = (float) $payments->where('category', 'Pharmacy / Medicine')->sum('net_amount');
        $procedureSales = (float) $payments->where('category', 'General Procedures')->sum('net_amount');
        $labSales = (float) $payments->where('category', 'Diagnostics / Lab')->sum('net_amount');
        $otherSales = (float) $payments->whereIn('category', ['Emergency', 'Other'])->sum('net_amount');
        $totalSales = $consultationSales + $pharmacySales + $procedureSales + $labSales + $otherSales;

        $totalExpenses = (float) Expense::sum('amount');
        $totalDues = (float) PatientHistory::sum('due_amount');
        $netRevenue = max(0.0, $totalSales - $totalExpenses);

        $pdf = new HospitalPdfDocument('P', 'mm', 'A4');
        $pdf->reportTitle = 'Hospital Payments & Financial Statement';
        $pdf->subTitle = ($startDate && $endDate)
            ? 'Period: '.Carbon::parse($startDate)->format('d M Y').' to '.Carbon::parse($endDate)->format('d M Y')
            : 'All Recorded Clinical Billings & Services';
        $pdf->AliasNbPages();
        $pdf->AddPage();

        // Financial Overview Cards
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetFillColor(241, 245, 249);
        $pdf->SetTextColor(15, 23, 42);
        $pdf->Cell(47, 7, 'TOTAL SALES', 1, 0, 'C', true);
        $pdf->Cell(47, 7, 'DAILY EXPENSES', 1, 0, 'C', true);
        $pdf->Cell(47, 7, 'NET REVENUE', 1, 0, 'C', true);
        $pdf->Cell(49, 7, 'PATIENT DUES', 1, 1, 'C', true);

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetTextColor(37, 99, 235);
        $pdf->Cell(47, 7, 'Rs. '.number_format($totalSales, 2), 1, 0, 'C');
        $pdf->SetTextColor(185, 28, 28);
        $pdf->Cell(47, 7, 'Rs. '.number_format($totalExpenses, 2), 1, 0, 'C');
        $pdf->SetTextColor(16, 185, 129);
        $pdf->Cell(47, 7, 'Rs. '.number_format($netRevenue, 2), 1, 0, 'C');
        $pdf->SetTextColor(217, 119, 6);
        $pdf->Cell(49, 7, 'Rs. '.number_format($totalDues, 2), 1, 1, 'C');

        $pdf->Ln(4);

        // Category Breakdown sub-summary
        $pdf->SetFont('Arial', 'B', 7.5);
        $pdf->SetFillColor(248, 250, 252);
        $pdf->SetTextColor(71, 85, 105);
        $pdf->Cell(38, 6, 'Consultations: Rs. '.number_format($consultationSales, 0), 1, 0, 'C', true);
        $pdf->Cell(38, 6, 'Pharmacy: Rs. '.number_format($pharmacySales, 0), 1, 0, 'C', true);
        $pdf->Cell(38, 6, 'Procedures: Rs. '.number_format($procedureSales, 0), 1, 0, 'C', true);
        $pdf->Cell(38, 6, 'Lab: Rs. '.number_format($labSales, 0), 1, 0, 'C', true);
        $pdf->Cell(38, 6, 'Other: Rs. '.number_format($otherSales, 0), 1, 1, 'C', true);

        $pdf->Ln(4);

        // Table Header
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetFillColor(30, 58, 138);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(28, 7, 'Invoice / Ref #', 1, 0, 'C', true);
        $pdf->Cell(25, 7, 'Date', 1, 0, 'C', true);
        $pdf->Cell(38, 7, 'Category', 1, 0, 'L', true);
        $pdf->Cell(45, 7, 'Patient Name', 1, 0, 'L', true);
        $pdf->Cell(30, 7, 'Method', 1, 0, 'C', true);
        $pdf->Cell(24, 7, 'Net (Rs.)', 1, 1, 'R', true);

        // Table Body
        $pdf->SetFont('Arial', '', 7.5);
        $fill = false;

        // If custom payments exist, list them
        if ($payments->isNotEmpty()) {
            foreach ($payments as $pay) {
                $pdf->SetFillColor($fill ? 248 : 255, $fill ? 250 : 255, $fill ? 252 : 255);
                $pdf->SetTextColor(30, 41, 59);

                $pdf->Cell(28, 6, substr($pay->invoice_no, 0, 16), 1, 0, 'C', $fill);
                $pdf->Cell(25, 6, $pay->payment_date ? Carbon::parse($pay->payment_date)->format('d M Y') : '-', 1, 0, 'C', $fill);
                $pdf->Cell(38, 6, substr($pay->category, 0, 22), 1, 0, 'L', $fill);
                $pdf->Cell(45, 6, substr($pay->patient_name, 0, 25), 1, 0, 'L', $fill);
                $pdf->Cell(30, 6, substr($pay->payment_method, 0, 16), 1, 0, 'C', $fill);
                $pdf->Cell(24, 6, number_format((float) $pay->net_amount, 2), 1, 1, 'R', $fill);

                $fill = ! $fill;
            }
        } else {
            // Otherwise show appointment consultations
            foreach ($appointments as $apt) {
                $pdf->SetFillColor($fill ? 248 : 255, $fill ? 250 : 255, $fill ? 252 : 255);
                $pdf->SetTextColor(30, 41, 59);

                $pdf->Cell(28, 6, '#APT-'.str_pad((string) $apt->id, 4, '0', STR_PAD_LEFT), 1, 0, 'C', $fill);
                $pdf->Cell(25, 6, $apt->created_at ? $apt->created_at->format('d M Y') : '-', 1, 0, 'C', $fill);
                $pdf->Cell(38, 6, 'Consultation', 1, 0, 'L', $fill);
                $pdf->Cell(45, 6, substr($apt->name, 0, 25), 1, 0, 'L', $fill);
                $pdf->Cell(30, 6, 'Direct Billing', 1, 0, 'C', $fill);
                $fee = $apt->doctor ? (float) $apt->doctor->fee : 0.0;
                $pdf->Cell(24, 6, number_format($fee, 2), 1, 1, 'R', $fill);

                $fill = ! $fill;
            }
        }

        // Summary Total Row
        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->SetFillColor(241, 245, 249);
        $pdf->SetTextColor(15, 23, 42);
        $pdf->Cell(166, 8, 'TOTAL ACCUMULATED GROSS SALES:', 1, 0, 'R', true);
        $pdf->SetTextColor(37, 99, 235);
        $pdf->Cell(24, 8, 'Rs. '.number_format($totalSales, 0), 1, 1, 'R', true);

        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="hospital_payments_'.date('Ymd_His').'.pdf"');
    }
}
