<?php

namespace App\Http\Controllers;

use App\Models\doctor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    // Show all doctors
    public function index()
    {
        // Original models (for the table)
        $doctors = Doctor::all();

        // Transformed data for the edit modals
        $doctorsData = $doctors->map(function ($doctor) {
            $dutySchedule = json_decode($doctor->duty_schedule, true) ?: [];
            $dutyDays = [];
            foreach ($dutySchedule as $day => $times) {
                $dutyDays[$day] = [
                    'start' => $times['start'] ?? '',
                    'end'   => $times['end'] ?? '',
                ];
            }

            return [
                'id'         => $doctor->id,
                'name'       => $doctor->name,
                'speciality' => $doctor->speciality,
                'phone'      => $doctor->phone,
                'email'      => $doctor->email,
                'pmdc'       => $doctor->pmdc,
                'fee'       => $doctor->fee,
                'duty_days'  => $dutyDays,
            ];
        })->keyBy('id'); // key by id for easy lookup

        return view('doctors.doctors', compact('doctors', 'doctorsData'));
    }
    // Show create form
    public function create()
    {
        return view('doctors.create');
    }

    // Store doctor
    public function store(Request $request)
    {
        // Validation (recommended)
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'speciality' => 'required',
            'pmdc' => 'required',
            'fee' => 'required',
        ]);

        $doctor = new Doctor();

        $doctor->name = $request->name;
        $doctor->phone = $request->phone;
        $doctor->email = $request->email;
        $doctor->speciality = $request->speciality;
        $doctor->pmdc = $request->pmdc;
        $doctor->fee = $request->fee;

        // Duty Days
        $dutyDays = $request->input('duty_days', []);
        $doctor->duty_days = implode(',', $dutyDays);

        // Duty Time Schedule
        $dutySchedule = $request->input('duty_time', []);
        $doctor->duty_schedule = json_encode($dutySchedule);

        $doctor->save();

        return redirect()->route('doctors.index')->with('success', 'Doctor added successfully');
    }

    // Show edit form
    public function edit($id)
    {
        $doctors = Doctor::with('dutyTimes')->get()->map(function ($doctor) {
            // Transform dutyTimes into the format expected by openEditModal
            $dutyDays = [];
            foreach ($doctor->dutyTimes as $duty) {
                $dutyDays[$duty->day] = [
                    'start' => $duty->start_time,
                    'end'   => $duty->end_time,
                ];
            }

            return [
                'id'         => $doctor->id,
                'name'       => $doctor->name,
                'speciality' => $doctor->speciality,
                'phone'      => $doctor->phone,
                'email'      => $doctor->email,
                'pmdc'       => $doctor->pmdc,
                'fee'       => $doctor->fee,
                'duty_days'  => $dutyDays,
            ];
        });
    }

    // Update doctor
    public function update(Request $request, $id)
    {
        $doctor = Doctor::find($id);

        // Basic info
        $doctor->name = $request->name;
        $doctor->phone = $request->phone;
        $doctor->email = $request->email;
        $doctor->speciality = $request->speciality;
        $doctor->pmdc = $request->pmdc;
        $doctor->fee = $request->fee;

        // Duty days (comma-separated string)
        $dutyDays = $request->input('duty_days', []);
        $doctor->duty_days = implode(',', $dutyDays);

        // Duty schedule (JSON)
        $dutySchedule = [];
        foreach ($dutyDays as $day) {
            $dutySchedule[$day] = [
                'start' => $request->input("duty_time.$day.start", null),
                'end' => $request->input("duty_time.$day.end", null),
            ];
        }
        $doctor->duty_schedule = json_encode($dutySchedule);

        $doctor->save();

        return redirect()->route('doctors.index')->with('success', 'Doctor updated successfully!');
    }
    // Delete doctor
    public function destroy($id)
    {
        $doctor = Doctor::find($id);
        $doctor->delete();

        return redirect()->route('doctors.index');
    }
}
