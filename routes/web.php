<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AppointmentPrintController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ExpenseCatagoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PatientHistoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Hospital Management System
|--------------------------------------------------------------------------
*/

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('welcome');

// Staff Management
Route::prefix('staff')->name('staff.')->group(function () {
    Route::get('/', [StaffController::class, 'index'])->name('index');
    Route::get('/create', [StaffController::class, 'create'])->name('create');
    Route::post('/store', [StaffController::class, 'store'])->name('store');
    Route::match(['post', 'put'], '/update/{id}', [StaffController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [StaffController::class, 'destroy'])->name('delete');
    Route::delete('/{id}', [StaffController::class, 'destroy'])->name('destroy');
});

// Appointment Management
Route::prefix('appointments')->group(function () {
    Route::get('/', [AppointmentController::class, 'index'])->name('appointment.index');
    Route::get('/create', [AppointmentController::class, 'create'])->name('appointment.create');
    Route::post('/store', [AppointmentController::class, 'store'])->name('appointment.store');
    Route::get('/edit/{id}', [AppointmentController::class, 'edit'])->name('appointment.edit');
    Route::match(['put', 'post'], '/update/{id}', [AppointmentController::class, 'update'])->name('appointment.update');
    Route::delete('/delete/{id}', [AppointmentController::class, 'destroy'])->name('appointment.destroy');
    Route::delete('/{id}', [AppointmentController::class, 'destroy']);
    Route::get('/show/{id}', [AppointmentController::class, 'show'])->name('appointment.show');
    Route::match(['post', 'put'], '/cancel-today', [AppointmentController::class, 'cancelByDate'])->name('appointments.cancelToday');
});

// Appointment Printing & Export
Route::post('/print-appointment', [AppointmentPrintController::class, 'print'])->name('print.appointment');
Route::get('/appointment-pdf/{id}', [AppointmentPrintController::class, 'appointmentPdf'])->name('pdf.appointment');

// Doctor Management
Route::prefix('doctors')->name('doctors.')->group(function () {
    Route::get('/', [DoctorController::class, 'index'])->name('index');
    Route::get('/create', [DoctorController::class, 'create'])->name('create');
    Route::post('/store', [DoctorController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [DoctorController::class, 'edit'])->name('edit');
    Route::match(['put', 'post'], '/update/{id}', [DoctorController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [DoctorController::class, 'destroy'])->name('delete');
    Route::delete('/{id}', [DoctorController::class, 'destroy'])->name('destroy');
});

// Daily Expenses
Route::prefix('expenses')->name('expenses.')->group(function () {
    Route::get('/', [ExpenseController::class, 'index'])->name('index');
    Route::get('/create', [ExpenseController::class, 'create'])->name('create');
    Route::post('/', [ExpenseController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [ExpenseController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ExpenseController::class, 'update'])->name('update');
    Route::delete('/{id}', [ExpenseController::class, 'destroy'])->name('destroy');
});

// Expense Categories
Route::prefix('expense-category')->name('category.')->group(function () {
    Route::get('/', [ExpenseCatagoryController::class, 'index'])->name('index');
    Route::post('/store', [ExpenseCatagoryController::class, 'store'])->name('store');
    Route::match(['post', 'put'], '/update/{id}', [ExpenseCatagoryController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [ExpenseCatagoryController::class, 'destroy'])->name('delete');
    Route::delete('/{id}', [ExpenseCatagoryController::class, 'destroy'])->name('destroy');
});

// Patient History Management
Route::prefix('patients')->name('patients.')->group(function () {
    Route::get('/', [PatientHistoryController::class, 'index'])->name('index');
    Route::get('/create', [PatientHistoryController::class, 'create'])->name('create');
    Route::post('/', [PatientHistoryController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [PatientHistoryController::class, 'edit'])->name('edit');
    Route::put('/{id}', [PatientHistoryController::class, 'update'])->name('update');
    Route::delete('/{id}', [PatientHistoryController::class, 'destroy'])->name('destroy');
    Route::get('/{id}', [PatientHistoryController::class, 'show'])->name('show');
});

// Hospital Payments (Billing & Reports)
Route::get('/hospital-payments', function () {
    return view('hospital-payments');
})->name('hospital-payments');

// FPDF Table Export & PDF Printing
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/patients/pdf', [ReportController::class, 'patientsPdf'])->name('patients.pdf');
    Route::get('/patients/{id}/pdf', [ReportController::class, 'patientProfilePdf'])->name('patient.profile.pdf');
    Route::get('/patients/{id}/statement', [ReportController::class, 'patientProfilePdf'])->name('patient.pdf');
    Route::get('/appointments/pdf', [ReportController::class, 'appointmentsPdf'])->name('appointments.pdf');
    Route::get('/doctors/pdf', [ReportController::class, 'doctorsPdf'])->name('doctors.pdf');
    Route::get('/staff/pdf', [ReportController::class, 'staffPdf'])->name('staff.pdf');
    Route::get('/expenses/pdf', [ReportController::class, 'expensesPdf'])->name('expenses.pdf');
});

// AI Medical Assistant & Hospital Chatbot
Route::post('/chatbot/message', [ChatbotController::class, 'handle'])->name('chatbot.message');
