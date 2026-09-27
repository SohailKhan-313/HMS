<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('password123');

        // 1. Primary Hospital Admin
        User::firstOrCreate(
            ['email' => 'admin@hospital.com'],
            [
                'name' => 'Hospital Admin',
                'password' => $defaultPassword,
                'role' => 'admin',
                'phone' => '03470232059',
                'status' => 'active',
            ]
        );

        // 2. Secondary Admin for developer/owner email
        User::firstOrCreate(
            ['email' => 'skpattan850911@gmail.com'],
            [
                'name' => 'Sohail Khan (Super Admin)',
                'password' => $defaultPassword,
                'role' => 'admin',
                'phone' => '03470232059',
                'status' => 'active',
            ]
        );

        // 3. Doctor User
        $firstDoctor = Doctor::first();
        User::firstOrCreate(
            ['email' => 'doctor@hospital.com'],
            [
                'name' => $firstDoctor ? $firstDoctor->name : 'Dr. Hassan Zeb',
                'password' => $defaultPassword,
                'role' => 'doctor',
                'phone' => $firstDoctor ? $firstDoctor->phone : '03001234567',
                'status' => 'active',
                'doctor_id' => $firstDoctor ? $firstDoctor->id : null,
            ]
        );

        // 4. HR User (Manages Staff & Doctors roster)
        User::firstOrCreate(
            ['email' => 'hr@hospital.com'],
            [
                'name' => 'HR Manager',
                'password' => $defaultPassword,
                'role' => 'hr',
                'phone' => '03119876543',
                'status' => 'active',
            ]
        );

        // 5. Accountant User (Manages Hospital Payments & Daily Expenses)
        User::firstOrCreate(
            ['email' => 'accountant@hospital.com'],
            [
                'name' => 'Chief Accountant',
                'password' => $defaultPassword,
                'role' => 'accountant',
                'phone' => '03224567890',
                'status' => 'active',
            ]
        );

        // 6. Receptionist User (Front Desk / Appointments / Patients)
        User::firstOrCreate(
            ['email' => 'receptionist@hospital.com'],
            [
                'name' => 'Front Desk Receptionist',
                'password' => $defaultPassword,
                'role' => 'receptionist',
                'phone' => '03331122334',
                'status' => 'active',
            ]
        );
    }
}
