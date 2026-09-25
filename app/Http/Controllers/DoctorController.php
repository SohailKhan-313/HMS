<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Models\Doctor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DoctorController extends Controller
{
    /**
     * Display a listing of doctors.
     */
    public function index(): View
    {
        $doctors = Doctor::query()->latest()->get();

        $doctorsData = $doctors->map(function (Doctor $doctor): array {
            $dutySchedule = is_array($doctor->duty_schedule)
                ? $doctor->duty_schedule
                : (json_decode((string) $doctor->duty_schedule, true) ?: []);

            $dutyDays = [];
            foreach ($dutySchedule as $day => $times) {
                $dutyDays[$day] = [
                    'start' => $times['start'] ?? '',
                    'end' => $times['end'] ?? '',
                ];
            }

            return [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'speciality' => $doctor->speciality,
                'phone' => $doctor->phone,
                'email' => $doctor->email,
                'pmdc' => $doctor->pmdc,
                'fee' => $doctor->fee,
                'duty_days' => $dutyDays,
            ];
        })->keyBy('id');

        return view('doctors.doctors', compact('doctors', 'doctorsData'));
    }

    /**
     * Show the form for creating a new doctor (handled via modal on index).
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('doctors.index');
    }

    /**
     * Store a newly created doctor.
     */
    public function store(StoreDoctorRequest $request): RedirectResponse
    {
        $dutyDays = (array) $request->input('duty_days', []);
        $dutyTimeInput = (array) $request->input('duty_time', []);

        $dutySchedule = [];
        foreach ($dutyDays as $day) {
            $dutySchedule[$day] = [
                'start' => $dutyTimeInput[$day]['start'] ?? null,
                'end' => $dutyTimeInput[$day]['end'] ?? null,
            ];
        }

        Doctor::create([
            'name' => $request->validated('name'),
            'phone' => $request->validated('phone'),
            'email' => $request->validated('email'),
            'speciality' => $request->validated('speciality'),
            'pmdc' => $request->validated('pmdc'),
            'fee' => $request->validated('fee'),
            'duty_days' => implode(',', $dutyDays),
            'duty_schedule' => json_encode($dutySchedule),
        ]);

        return redirect()->route('doctors.index')->with('success', 'Doctor added successfully.');
    }

    /**
     * Show the form for editing a doctor.
     */
    public function edit(int|string $id): JsonResponse|RedirectResponse
    {
        $doctor = Doctor::findOrFail($id);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json($doctor);
        }

        return redirect()->route('doctors.index');
    }

    /**
     * Update the specified doctor.
     */
    public function update(UpdateDoctorRequest $request, int|string $id): RedirectResponse
    {
        $doctor = Doctor::findOrFail($id);

        $dutyDays = (array) $request->input('duty_days', []);
        $dutySchedule = [];
        foreach ($dutyDays as $day) {
            $dutySchedule[$day] = [
                'start' => $request->input("duty_time.{$day}.start"),
                'end' => $request->input("duty_time.{$day}.end"),
            ];
        }

        $doctor->update([
            'name' => $request->validated('name'),
            'phone' => $request->validated('phone'),
            'email' => $request->validated('email'),
            'speciality' => $request->validated('speciality'),
            'pmdc' => $request->validated('pmdc'),
            'fee' => $request->validated('fee'),
            'duty_days' => implode(',', $dutyDays),
            'duty_schedule' => json_encode($dutySchedule),
        ]);

        return redirect()->route('doctors.index')->with('success', 'Doctor updated successfully!');
    }

    /**
     * Delete the specified doctor.
     */
    public function destroy(int|string $id): RedirectResponse
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();

        return redirect()->route('doctors.index')->with('success', 'Doctor removed successfully.');
    }
}
