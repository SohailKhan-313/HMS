<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointments';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'doctor_id',
        'name',
        'gender',
        'age',
        'phone',
        'time',
        'status',
        'notes',
        'diagnosis',
        'medicine_suggestions',
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
        ];
    }

    /**
     * Get the doctor associated with the appointment.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    /**
     * Get the payments associated with the appointment.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(HospitalPayment::class, 'appointment_id');
    }
}
