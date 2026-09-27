<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Show the user registration form.
     */
    public function showRegistrationForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('welcome');
        }

        return view('auth.register');
    }

    /**
     * Handle an incoming user registration request.
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->string('name')->trim()->value(),
            'email' => $request->string('email')->lower()->trim()->value(),
            'phone' => $request->input('phone'),
            'role' => $request->input('role'),
            'status' => User::STATUS_ACTIVE,
            'password' => Hash::make($request->string('password')->value()),
        ]);

        // If registered as Doctor, create linked doctor profile automatically
        if ($user->isDoctor()) {
            $doctor = Doctor::create([
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '0000000000',
                'speciality' => $request->input('speciality') ?: 'General Specialist',
                'pmdc' => $request->input('pmdc') ?: 'PMC-'.rand(10000, 99999),
                'fee' => $request->input('fee') ?: 1500,
                'duty_days' => 'Monday,Tuesday,Wednesday,Thursday,Friday',
                'duty_schedule' => json_encode([
                    'Monday' => ['start' => '09:00', 'end' => '13:00'],
                    'Tuesday' => ['start' => '09:00', 'end' => '13:00'],
                    'Wednesday' => ['start' => '09:00', 'end' => '13:00'],
                    'Thursday' => ['start' => '09:00', 'end' => '13:00'],
                    'Friday' => ['start' => '09:00', 'end' => '13:00'],
                ]),
            ]);

            $user->update(['doctor_id' => $doctor->id]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('welcome')
            ->with('success', "Welcome to Hospital Management System, {$user->name}! You are registered as {$user->role}.");
    }
}
