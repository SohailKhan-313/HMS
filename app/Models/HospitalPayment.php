<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HospitalPayment extends Model
{
    use HasFactory;

    protected $table = 'hospital_payments';

    public const CATEGORIES = [
        'Consultation' => 'Consultation Billing',
        'Pharmacy / Medicine' => 'Pharmacy / Medicines',
        'General Procedures' => 'General Procedures',
        'Diagnostics / Lab' => 'Diagnostics / Lab',
        'Emergency' => 'Emergency Care',
        'Other' => 'Other Clinical Services',
    ];

    public const PAYMENT_METHODS = [
        'Cash' => 'Cash',
        'Card' => 'Credit / Debit Card',
        'Bank Transfer' => 'Bank Transfer',
        'Wallet' => 'Patient Wallet Credit',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'invoice_no',
        'category',
        'patient_id',
        'patient_name',
        'patient_phone',
        'doctor_id',
        'appointment_id',
        'amount',
        'discount',
        'net_amount',
        'paid_amount',
        'payment_method',
        'payment_date',
        'status',
        'notes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'discount' => 'decimal:2',
            'net_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'payment_date' => 'date',
        ];
    }

    /**
     * Patient relationship (from PatientHistory).
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(PatientHistory::class, 'patient_id');
    }

    /**
     * Doctor relationship.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    /**
     * Appointment relationship.
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

    /**
     * Auto-generate a sequential unique invoice number.
     */
    public static function generateInvoiceNo(): string
    {
        $prefix = 'INV-'.date('Ymd');
        $lastPayment = static::where('invoice_no', 'like', "{$prefix}-%")->latest('id')->first();

        if ($lastPayment && preg_match('/-(\d+)$/', (string) $lastPayment->invoice_no, $matches)) {
            $nextSeq = str_pad((string) ((int) $matches[1] + 1), 4, '0', STR_PAD_LEFT);
        } else {
            $nextSeq = '0001';
        }

        return "{$prefix}-{$nextSeq}";
    }
}
