<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AppointmentPrintController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ExpenseCatagoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HospitalPaymentController;
use App\Http\Controllers\PatientHistoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Hospital Management System
|--------------------------------------------------------------------------
*/

// Authentication Routes (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

// Authenticated Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Application Routes (Requires Auth & Active Account)
Route::middleware(['auth', 'active'])->group(function () {

    // Dashboard Overview
    Route::get('/', [DashboardController::class, 'index'])->name('welcome');

    // Users & Roles Management (Strictly Administrator Only)
    Route::prefix('users')->name('users.')->middleware('role:admin')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::post('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    // Appointment Management (Admin, Doctor, Receptionist)
    Route::prefix('appointments')->middleware('role:admin,doctor,receptionist')->group(function () {
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
    Route::post('/print-appointment', [AppointmentPrintController::class, 'print'])->name('print.appointment')->middleware('role:admin,doctor,receptionist');
    Route::get('/appointment-pdf/{id}', [AppointmentPrintController::class, 'appointmentPdf'])->name('pdf.appointment')->middleware('role:admin,doctor,receptionist');

    // Doctor Management (View: Admin, HR, Doctor, Receptionist | Create, Edit, Delete: Admin, HR only)
    Route::prefix('doctors')->name('doctors.')->group(function () {
        Route::get('/', [DoctorController::class, 'index'])->name('index')->middleware('role:admin,hr,doctor,receptionist');

        Route::middleware('role:admin,hr')->group(function () {
            Route::get('/create', [DoctorController::class, 'create'])->name('create');
            Route::post('/store', [DoctorController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [DoctorController::class, 'edit'])->name('edit');
            Route::match(['put', 'post'], '/update/{id}', [DoctorController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [DoctorController::class, 'destroy'])->name('delete');
            Route::delete('/{id}', [DoctorController::class, 'destroy'])->name('destroy');
        });
    });

    // Staff Management (Admin, HR)
    Route::prefix('staff')->name('staff.')->middleware('role:admin,hr')->group(function () {
        Route::get('/', [StaffController::class, 'index'])->name('index');
        Route::get('/create', [StaffController::class, 'create'])->name('create');
        Route::post('/store', [StaffController::class, 'store'])->name('store');
        Route::match(['post', 'put'], '/update/{id}', [StaffController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [StaffController::class, 'destroy'])->name('delete');
        Route::delete('/{id}', [StaffController::class, 'destroy'])->name('destroy');
    });

    // Daily Expenses (Admin, Accountant)
    Route::prefix('expenses')->name('expenses.')->middleware('role:admin,accountant')->group(function () {
        Route::get('/', [ExpenseController::class, 'index'])->name('index');
        Route::get('/create', [ExpenseController::class, 'create'])->name('create');
        Route::post('/', [ExpenseController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ExpenseController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ExpenseController::class, 'update'])->name('update');
        Route::delete('/{id}', [ExpenseController::class, 'destroy'])->name('destroy');
    });

    // Expense Categories (Admin, Accountant)
    Route::prefix('expense-category')->name('category.')->middleware('role:admin,accountant')->group(function () {
        Route::get('/', [ExpenseCatagoryController::class, 'index'])->name('index');
        Route::post('/store', [ExpenseCatagoryController::class, 'store'])->name('store');
        Route::match(['post', 'put'], '/update/{id}', [ExpenseCatagoryController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [ExpenseCatagoryController::class, 'destroy'])->name('delete');
        Route::delete('/{id}', [ExpenseCatagoryController::class, 'destroy'])->name('destroy');
    });

    // Patient History Management (Admin, Receptionist, Doctor)
    Route::prefix('patients')->name('patients.')->middleware('role:admin,receptionist,doctor')->group(function () {
        Route::get('/', [PatientHistoryController::class, 'index'])->name('index');
        Route::get('/create', [PatientHistoryController::class, 'create'])->name('create');
        Route::post('/', [PatientHistoryController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PatientHistoryController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PatientHistoryController::class, 'update'])->name('update');
        Route::delete('/{id}', [PatientHistoryController::class, 'destroy'])->name('destroy');
        Route::get('/show/{id}', [PatientHistoryController::class, 'show'])->name('show');
        Route::get('/{id}', [PatientHistoryController::class, 'show'])->name('show.alias');
    });

    // Hospital Payments (Billing & Reports - Admin, Accountant)
    Route::prefix('hospital-payments')->middleware('role:admin,accountant')->group(function () {
        Route::get('/', [HospitalPaymentController::class, 'index'])->name('hospital-payments');
        Route::post('/', [HospitalPaymentController::class, 'store'])->name('hospital-payments.store');
        Route::delete('/{id}', [HospitalPaymentController::class, 'destroy'])->name('hospital-payments.destroy');
    });

    // FPDF Table Export & PDF Printing
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/patients/pdf', [ReportController::class, 'patientsPdf'])->name('patients.pdf')->middleware('role:admin,receptionist,doctor');
        Route::get('/patients/{id}/pdf', [ReportController::class, 'patientProfilePdf'])->name('patient.profile.pdf')->middleware('role:admin,receptionist,doctor');
        Route::get('/patients/{id}/statement', [ReportController::class, 'patientProfilePdf'])->name('patient.pdf')->middleware('role:admin,receptionist,doctor');
        Route::get('/appointments/pdf', [ReportController::class, 'appointmentsPdf'])->name('appointments.pdf')->middleware('role:admin,doctor,receptionist');
        Route::get('/doctors/pdf', [ReportController::class, 'doctorsPdf'])->name('doctors.pdf')->middleware('role:admin,hr,doctor,receptionist');
        Route::get('/staff/pdf', [ReportController::class, 'staffPdf'])->name('staff.pdf')->middleware('role:admin,hr');
        Route::get('/expenses/pdf', [ReportController::class, 'expensesPdf'])->name('expenses.pdf')->middleware('role:admin,accountant');
        Route::get('/payments/pdf', [ReportController::class, 'paymentsPdf'])->name('payments.pdf')->middleware('role:admin,accountant');
    });

    // AI Medical Assistant & Hospital Chatbot
    Route::post('/chatbot/message', [ChatbotController::class, 'handle'])->name('chatbot.message');
});

// Live Production Database Diagnostics and Migration Trigger
Route::get('/db-status', function () {
    $default = config('database.default');
    $config = config("database.connections.{$default}", []);

    $result = [
        'default_connection' => $default,
        'driver' => $config['driver'] ?? null,
        'host' => $config['host'] ?? null,
        'port' => $config['port'] ?? null,
        'database' => $config['database'] ?? null,
        'username' => $config['username'] ?? null,
        'env_DB_HOST' => env('DB_HOST'),
        'env_MYSQLHOST' => env('MYSQLHOST'),
        'env_MYSQL_URL' => env('MYSQL_URL') ? 'PRESENT' : 'NOT_SET',
    ];

    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $result['connection_status'] = 'CONNECTED';
        $tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
        $result['tables_count'] = count($tables);
        $result['tables'] = $tables;

        if (count($tables) === 0 || request()->query('migrate') === '1') {
            \Illuminate\Support\Facades\Artisan::call('migrate --force');
            $result['migrate_output'] = \Illuminate\Support\Facades\Artisan::output();
            $result['tables_after_migrate'] = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
            $result['tables_count_after_migrate'] = count($result['tables_after_migrate']);
        }
    } catch (\Throwable $e) {
        $result['connection_status'] = 'FAILED: '.$e->getMessage();
    }

    return response()->json($result, 200, [], JSON_PRETTY_PRINT);
})->name('db.status');
