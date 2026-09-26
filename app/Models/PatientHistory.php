<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PatientHistory extends Model
{
    use HasFactory;

    protected $table = 'patienthistory';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'age',
        'phone',
        'cnic',
        'due_amount',
        'wallet_amount',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'age' => 'integer',
            'due_amount' => 'decimal:2',
            'wallet_amount' => 'decimal:2',
        ];
    }

    /**
     * Get the payments associated with the patient.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(HospitalPayment::class, 'patient_id');
    }
}
