<?php

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Expense;
use App\Models\HospitalPayment;
use App\Models\PatientHistory;
use App\Models\Staff;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('dashboard page renders successfully', function () {
    $response = $this->get(route('welcome'));

    $response->assertOk();
    $response->assertViewIs('welcome');
});

test('staff page renders successfully', function () {
    $response = $this->get(route('staff.index'));

    $response->assertOk();
    $response->assertViewIs('staff.staff');
});

test('appointments page renders successfully', function () {
    $response = $this->get(route('appointment.index'));

    $response->assertOk();
    $response->assertViewIs('appointments.appointment');
});

test('doctors page renders successfully', function () {
    $response = $this->get(route('doctors.index'));

    $response->assertOk();
    $response->assertViewIs('doctors.doctors');
});

test('expenses page renders successfully', function () {
    $response = $this->get(route('expenses.index'));

    $response->assertOk();
    $response->assertViewIs('expenses.expanse');
});

test('expense categories page renders successfully', function () {
    $response = $this->get(route('category.index'));

    $response->assertOk();
    $response->assertViewIs('expenses.expense-catagory');
});

test('patients page renders successfully', function () {
    $response = $this->get(route('patients.index'));

    $response->assertOk();
    $response->assertViewIs('history.p-history');
});

test('hospital payments page renders successfully', function () {
    $response = $this->get(route('hospital-payments'));

    $response->assertOk();
    $response->assertViewIs('hospital-payments');
});

test('can create a doctor via store route', function () {
    $doctorData = [
        'name' => 'Dr. Ahmed Khan',
        'email' => 'ahmed.khan@hospital.test',
        'phone' => '+92 300 1234567',
        'speciality' => 'Cardiologist',
        'pmdc' => 'PMC-12345-P',
        'fee' => 2500.00,
        'duty_days' => ['Monday', 'Wednesday', 'Friday'],
        'duty_time' => [
            'Monday' => ['start' => '09:00', 'end' => '14:00'],
            'Wednesday' => ['start' => '09:00', 'end' => '14:00'],
            'Friday' => ['start' => '09:00', 'end' => '13:00'],
        ],
    ];

    $response = $this->post(route('doctors.store'), $doctorData);

    $response->assertRedirect(route('doctors.index'));
    $this->assertDatabaseHas('doctors', [
        'name' => 'Dr. Ahmed Khan',
        'email' => 'ahmed.khan@hospital.test',
    ]);
});

test('can create an appointment for a doctor', function () {
    $doctor = Doctor::create([
        'name' => 'Dr. Sara Malik',
        'email' => 'sara@hospital.test',
        'phone' => '03121234567',
        'speciality' => 'Neurologist',
        'pmdc' => 'PMC-54321-N',
        'fee' => 3000.00,
    ]);

    $appointmentData = [
        'doctor_id' => $doctor->id,
        'name' => 'Usman Ali',
        'phone' => '03331112233',
        'gender' => 'Male',
        'age' => 32,
        'status' => 'Pending',
        'time' => '10:30 AM',
    ];

    $response = $this->post(route('appointment.store'), $appointmentData);

    $response->assertRedirect(route('appointment.index'));
    $this->assertDatabaseHas('appointments', [
        'name' => 'Usman Ali',
        'doctor_id' => $doctor->id,
    ]);
});

test('can create staff member', function () {
    $staffData = [
        'name' => 'Zubair Shah',
        'email' => 'zubair@hospital.test',
        'phone' => '03009988776',
        'designation' => 'Staff Nurse',
        'salary' => 45000.00,
    ];

    $response = $this->post(route('staff.store'), $staffData);

    $response->assertRedirect(route('staff.index'));
    $this->assertDatabaseHas('staff', [
        'email' => 'zubair@hospital.test',
        'name' => 'Zubair Shah',
    ]);
});

test('can create expense category and expense', function () {
    $categoryResponse = $this->post(route('category.store'), [
        'name' => 'Medical Supplies',
        'description' => 'Bandages, syringes and surgical consumables',
    ]);

    $categoryResponse->assertRedirect(route('category.index'));
    $this->assertDatabaseHas('expense_catagory', [
        'name' => 'Medical Supplies',
    ]);

    $expenseResponse = $this->post(route('expenses.store'), [
        'name' => 'Syringes Box',
        'date' => '2026-09-25',
        'catagory' => 'Medical Supplies',
        'amount' => 1500.00,
    ]);

    $expenseResponse->assertRedirect(route('expenses.index'));
    $this->assertDatabaseHas('expenses', [
        'name' => 'Syringes Box',
        'amount' => 1500.00,
    ]);
});

test('can create patient history', function () {
    $patientData = [
        'name' => 'Rashid Mehmood',
        'age' => 45,
        'phone' => '03214567890',
        'cnic' => '35201-1234567-1',
        'due_amount' => 500.00,
        'wallet_amount' => 1000.00,
    ];

    $response = $this->post(route('patients.store'), $patientData);

    $response->assertRedirect(route('patients.index'));
    $this->assertDatabaseHas('patienthistory', [
        'name' => 'Rashid Mehmood',
        'phone' => '03214567890',
    ]);
});

test('can generate appointment pdf receipt', function () {
    $doctor = Doctor::create([
        'name' => 'Dr. Farhan',
        'email' => 'farhan@hospital.test',
        'phone' => '03001122334',
        'speciality' => 'Dermatologist',
        'pmdc' => 'PMC-99887-D',
        'fee' => 2000.00,
    ]);

    $appointment = Appointment::create([
        'doctor_id' => $doctor->id,
        'name' => 'Kamran Khan',
        'phone' => '03451234567',
        'gender' => 'Male',
        'age' => 28,
        'status' => 'Pending',
        'time' => '11:00 AM',
    ]);

    $response = $this->get(route('pdf.appointment', $appointment->id));

    $response->assertOk();
    expect($response->headers->get('content-type'))->toBe('application/pdf');
});

test('can generate pdf reports for all record tables', function () {
    $doctor = Doctor::create([
        'name' => 'Dr. Ayesha',
        'email' => 'ayesha@hospital.test',
        'phone' => '03004455667',
        'speciality' => 'Gynecologist',
        'pmdc' => 'PMC-11223-G',
        'fee' => 2500.00,
    ]);

    $patient = PatientHistory::create([
        'name' => 'Sobia Tariq',
        'age' => 30,
        'phone' => '03001234567',
        'cnic' => '35201-9876543-2',
        'due_amount' => 1200.00,
        'wallet_amount' => 500.00,
    ]);

    Appointment::create([
        'doctor_id' => $doctor->id,
        'name' => 'Sobia Tariq',
        'phone' => '03001234567',
        'gender' => 'Female',
        'age' => 30,
        'status' => 'Pending',
        'time' => '02:00 PM',
    ]);

    Staff::create([
        'name' => 'Naveed Akhtar',
        'email' => 'naveed@hospital.test',
        'phone' => '03112233445',
        'designation' => 'Receptionist',
        'salary' => 35000.00,
    ]);

    Expense::create([
        'name' => 'Electricity Bill',
        'date' => '2026-09-25',
        'catagory' => 'Utilities',
        'amount' => 24500.00,
    ]);

    // Test All Patients PDF
    $patientsPdf = $this->get(route('reports.patients.pdf'));
    $patientsPdf->assertOk();
    expect($patientsPdf->headers->get('content-type'))->toBe('application/pdf');

    // Test Single Patient History PDF
    $singlePatientPdf = $this->get(route('reports.patient.pdf', $patient->id));
    $singlePatientPdf->assertOk();
    expect($singlePatientPdf->headers->get('content-type'))->toBe('application/pdf');

    // Test Appointments Table PDF
    $appointmentsPdf = $this->get(route('reports.appointments.pdf'));
    $appointmentsPdf->assertOk();
    expect($appointmentsPdf->headers->get('content-type'))->toBe('application/pdf');

    // Test Doctors Directory PDF
    $doctorsPdf = $this->get(route('reports.doctors.pdf'));
    $doctorsPdf->assertOk();
    expect($doctorsPdf->headers->get('content-type'))->toBe('application/pdf');

    // Test Staff Directory PDF
    $staffPdf = $this->get(route('reports.staff.pdf'));
    $staffPdf->assertOk();
    expect($staffPdf->headers->get('content-type'))->toBe('application/pdf');

    // Test Expenses Audit PDF
    $expensesPdf = $this->get(route('reports.expenses.pdf'));
    $expensesPdf->assertOk();
    expect($expensesPdf->headers->get('content-type'))->toBe('application/pdf');
});

test('can cancel appointments by date', function () {
    $doctor = Doctor::create([
        'name' => 'Dr. Zafar',
        'email' => 'zafar@hospital.test',
        'phone' => '03009998877',
        'speciality' => 'Cardiologist',
        'pmdc' => 'PMC-77889-C',
        'fee' => 3000.00,
    ]);

    $appointment = Appointment::create([
        'doctor_id' => $doctor->id,
        'name' => 'Adnan Sami',
        'phone' => '03211234567',
        'gender' => 'Male',
        'age' => 40,
        'status' => 'Pending',
        'time' => '04:00 PM',
    ]);

    $todayDate = date('Y-m-d');

    $response = $this->post(route('appointments.cancelToday'), [
        'date' => $todayDate,
    ]);

    $response->assertRedirect(route('appointment.index'));
    $response->assertSessionHas('success');

    $appointment->refresh();
    expect($appointment->status)->toBe('Cancelled');
});

test('chatbot provides hospital records information and medical triage suggestions', function () {
    $doctor = Doctor::create([
        'name' => 'Dr. Tariq Dent',
        'email' => 'tariq@hospital.test',
        'phone' => '03001112233',
        'speciality' => 'Dentist',
        'pmdc' => 'PMC-1122-D',
        'fee' => 1500.00,
    ]);

    // Test greeting
    $greetingRes = $this->postJson(route('chatbot.message'), [
        'message' => 'Hello hospital',
    ]);
    $greetingRes->assertOk();
    $greetingRes->assertJsonStructure(['status', 'reply', 'suggestions']);
    expect($greetingRes->json('status'))->toBe('success');

    // Test doctor record inquiry
    $doctorRes = $this->postJson(route('chatbot.message'), [
        'message' => 'Show me the doctors available',
    ]);
    $doctorRes->assertOk();
    expect($doctorRes->json('reply'))->toContain('Dr. Tariq Dent');

    // Test medical triage suggestion
    $symptomRes = $this->postJson(route('chatbot.message'), [
        'message' => 'I have severe toothache and bleeding gums, what should I do?',
    ]);
    $symptomRes->assertOk();
    expect($symptomRes->json('reply'))->toContain('Dental Surgery');
    expect($symptomRes->json('reply'))->toContain('Dr. Tariq Dent');

    // Test validation failure
    $emptyRes = $this->postJson(route('chatbot.message'), [
        'message' => '',
    ]);
    $emptyRes->assertStatus(422);
});

test('bootstrap and jquery assets are properly loaded in the layout', function () {
    $response = $this->get(route('welcome'));

    $response->assertOk();
    $response->assertSee('/css/bootstrap.min.css');
    $response->assertSee('/js/bootstrap.bundle.min.js');
    $response->assertSee('/js/jquery.min.js');
});

test('can create a custom hospital payment linked to patient and doctor', function () {
    $patient = PatientHistory::create([
        'name' => 'Zubair Ahmed',
        'age' => 35,
        'phone' => '03009988776',
        'cnic' => '17301-1234567-1',
        'due_amount' => 500.00,
        'wallet_amount' => 1000.00,
    ]);

    $doctor = Doctor::create([
        'name' => 'Dr. Kamran Ali',
        'email' => 'kamran@hospital.test',
        'phone' => '03331234567',
        'speciality' => 'Pathology',
        'pmdc' => 'PMC-9988-P',
        'fee' => 1200.00,
    ]);

    $paymentData = [
        'category' => 'Pharmacy / Medicine',
        'patient_id' => $patient->id,
        'patient_name' => $patient->name,
        'patient_phone' => $patient->phone,
        'doctor_id' => $doctor->id,
        'amount' => 2500.00,
        'discount' => 200.00,
        'paid_amount' => 2300.00,
        'payment_method' => 'Cash',
        'payment_date' => now()->toDateString(),
        'status' => 'Paid',
        'notes' => 'Prescription antibiotics and analgesics',
    ];

    $response = $this->post(route('hospital-payments.store'), $paymentData);

    $response->assertRedirect(route('hospital-payments'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('hospital_payments', [
        'category' => 'Pharmacy / Medicine',
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'amount' => 2500.00,
        'discount' => 200.00,
        'net_amount' => 2300.00,
        'paid_amount' => 2300.00,
        'status' => 'Paid',
    ]);

    // Check relationship from patient and doctor
    $patient->refresh();
    expect($patient->payments()->count())->toBe(1);
    expect($doctor->hospitalPayments()->count())->toBe(1);

    // Verify it appears in hospital payments view
    $viewResponse = $this->get(route('hospital-payments'));
    $viewResponse->assertOk();
    $viewResponse->assertSee('Pharmacy / Medicine');
    $viewResponse->assertSee('Prescription antibiotics and analgesics');
    $viewResponse->assertSee('Zubair Ahmed');
});

test('can delete a hospital payment record', function () {
    $payment = HospitalPayment::create([
        'invoice_no' => HospitalPayment::generateInvoiceNo(),
        'category' => 'Diagnostics / Lab',
        'patient_name' => 'Walk-in Patient',
        'amount' => 1500.00,
        'discount' => 0.00,
        'net_amount' => 1500.00,
        'paid_amount' => 1500.00,
        'payment_method' => 'Card',
        'payment_date' => now()->toDateString(),
        'status' => 'Paid',
        'notes' => 'Complete Blood Count (CBC)',
    ]);

    $response = $this->delete(route('hospital-payments.destroy', $payment->id));

    $response->assertRedirect(route('hospital-payments'));
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('hospital_payments', [
        'id' => $payment->id,
    ]);
});
