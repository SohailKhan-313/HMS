<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseCatagoryController;
use App\Http\Controllers\PatientHistoryController;
use App\Http\Controllers\AppointmentPrintController;


Route::get('/', function () {
    return view('welcome');
    })->name('welcome');
    //doctors
// Route::get('/doctors', function () {
//     return view('doctors');
// })->name('doctors');

// Route::get('/history', function () {
//     return view('history.p-history');
// })->name('p-history');
//expanse
// Route::get('/expanse', function () {
//     return view('expanse');
// })->name('expanse');
//expanse catagory
// Route::get('/expanse-catagory', function () {
//     return view('expenses.expense-catagory');
// })->name('expense-catagory');
//hospital payments
Route::get('/hospital-payments', function () {
    return view('hospital-payments');
})->name('hospital-payments');


/*
|--------------------------------------------------------------------------
| Staff Routes
|--------------------------------------------------------------------------
*/

Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');

Route::post('/staff/store', [StaffController::class, 'store'])->name('staff.store');

Route::post('/staff/update/{id}', [StaffController::class, 'update'])->name('staff.update');

Route::delete('/staff/delete/{id}', [StaffController::class, 'delete'])->name('staff.delete');
/*
|--------------------------------------------------------------------------
| appointment routes
|--------------------------------------------------------------------------
*/
Route::get('/appointments', [AppointmentController::class,'index'])->name('appointment.index');
Route::get('/appointments/create', [AppointmentController::class,'create'])->name('appointment.create');
Route::post('/appointments/store', [AppointmentController::class,'store'])->name('appointment.store');
Route::get('/appointments/edit/{id}', [AppointmentController::class,'edit'])->name('appointment.edit');
Route::put('/appointments/update/{id}', [AppointmentController::class,'update'])->name('appointment.update');
Route::delete('/appointments/delete/{id}', [AppointmentController::class,'destroy'])->name('appointment.destroy');
Route::get('/appointments/show/{id}', [AppointmentController::class,'show'])->name('appointment.show');
Route::post('/print-appointment', [AppointmentPrintController::class, 'print'])->name('print.appointment');
Route::get('/appointment-pdf/{id}', [AppointmentPrintController::class, 'appointmentPdf'])->name('pdf.appointment');
//cancling today ppointment
Route::post('/appointments/cancel-today', [AppointmentController::class, 'cancelByDate'])->name('appointments.cancelToday');
//doctors routes

Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');

Route::get('/doctors/create', [DoctorController::class, 'create'])->name('doctors.create');

Route::post('/doctors/store', [DoctorController::class, 'store'])->name('doctors.store');

Route::get('/doctors/edit/{id}', [DoctorController::class, 'edit'])->name('doctors.edit');

Route::put('/doctors/update/{id}', [DoctorController::class, 'update'])->name('doctors.update');

Route::delete('/doctors/delete/{id}', [DoctorController::class, 'destroy'])->name('doctors.delete');
/*
|--------------------------------------------------------------------------
| expence routes
|--------------------------------------------------------------------------
*/
Route::get('/expenses',[ExpenseController::class,'index'])->name('expenses.index');

Route::get('/expenses/create',[ExpenseController::class,'create'])->name('expenses.create');

Route::post('/expenses',[ExpenseController::class,'store'])->name('expenses.store');

Route::get('/expenses/{id}/edit',[ExpenseController::class,'edit'])->name('expenses.edit');

Route::put('/expenses/{id}',[ExpenseController::class,'update'])->name('expenses.update');

Route::delete('/expenses/{id}',[ExpenseController::class,'destroy'])->name('expenses.destroy');
/*
|--------------------------------------------------------------------------
| expence-catagory routes
|--------------------------------------------------------------------------
*/

Route::get('/expense-category',[ExpenseCatagoryController::class,'index'])->name('category.index');
Route::post('/expense-category/store',[ExpenseCatagoryController::class,'store'])->name('category.store');
Route::post('/expense-category/update/{id}',[ExpenseCatagoryController::class,'update'])->name('category.update');
Route::delete('/expense-category/delete/{id}',[ExpenseCatagoryController::class,'destroy'])->name('category.delete');
/*
|--------------------------------------------------------------------------
|patientshistory routes
|--------------------------------------------------------------------------
*/


// INDEX
Route::get('/patients', [PatientHistoryController::class, 'index'])->name('patients.index');

// CREATE FORM
Route::get('/patients/create', [PatientHistoryController::class, 'create'])->name('patients.create');

// STORE
Route::post('/patients', [PatientHistoryController::class, 'store'])->name('patients.store');

// EDIT FORM
Route::get('/patients/{id}/edit', [PatientHistoryController::class, 'edit'])->name('patients.edit');

// UPDATE
Route::put('/patients/{id}', [PatientHistoryController::class, 'update'])->name('patients.update');

// DELETE
Route::delete('/patients/{id}', [PatientHistoryController::class, 'destroy'])->name('patients.destroy');

// OPTIONAL (if you need single view)
Route::get('/patients/{id}', [PatientHistoryController::class, 'show'])->name('patients.show');