<?php

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('login page renders successfully for guests', function () {
    $response = $this->get(route('login'));

    $response->assertOk();
    $response->assertViewIs('auth.login');
});

test('active user can authenticate with valid credentials and redirect to dashboard', function () {
    $user = User::factory()->create([
        'email' => 'staff@hospital.test',
        'password' => bcrypt('password123'),
        'status' => User::STATUS_ACTIVE,
    ]);

    $response = $this->post(route('login.post'), [
        'email' => 'staff@hospital.test',
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('welcome'));
    $this->assertAuthenticatedAs($user);
});

test('user cannot authenticate with invalid credentials', function () {
    User::factory()->create([
        'email' => 'staff@hospital.test',
        'password' => bcrypt('password123'),
        'status' => User::STATUS_ACTIVE,
    ]);

    $response = $this->post(route('login.post'), [
        'email' => 'staff@hospital.test',
        'password' => 'wrongpassword',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('inactive user cannot authenticate and receives deactivation notice', function () {
    User::factory()->inactive()->create([
        'email' => 'inactive@hospital.test',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->post(route('login.post'), [
        'email' => 'inactive@hospital.test',
        'password' => 'password123',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('authenticated user can logout and session is invalidated', function () {
    $user = User::factory()->admin()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $response->assertRedirect(route('login'));
    $this->assertGuest();
});

test('unauthenticated guest is redirected to login when visiting protected routes', function () {
    $response = $this->get(route('welcome'));

    $response->assertRedirect(route('login'));
});

// DOCTOR ROLE PERMISSION TESTS
test('doctor can access appointments and doctors roster pages', function () {
    $doctorUser = User::factory()->doctor()->create();

    $this->actingAs($doctorUser)->get(route('doctors.index'))->assertOk();
    $this->actingAs($doctorUser)->get(route('appointment.index'))->assertOk();
});

test('doctor is blocked from payment, expense, staff, and user management routes', function () {
    $doctorUser = User::factory()->doctor()->create();

    $this->actingAs($doctorUser)->get(route('hospital-payments'))->assertRedirect(route('welcome'));
    $this->actingAs($doctorUser)->get(route('expenses.index'))->assertRedirect(route('welcome'));
    $this->actingAs($doctorUser)->get(route('staff.index'))->assertRedirect(route('welcome'));
    $this->actingAs($doctorUser)->get(route('users.index'))->assertRedirect(route('welcome'));
});

test('doctor view hides new doctor button and edit delete actions on doctors blade', function () {
    $doctorUser = User::factory()->doctor()->create();
    $doctor = Doctor::create([
        'name' => 'Dr. Farooq Tariq',
        'email' => 'farooq@hospital.test',
        'phone' => '03001234567',
        'speciality' => 'Dermatologist',
        'pmdc' => 'PMC-99881',
        'fee' => 1800,
        'duty_days' => 'Monday,Tuesday',
        'duty_schedule' => json_encode(['Monday' => ['start' => '09:00', 'end' => '13:00']]),
    ]);

    $response = $this->actingAs($doctorUser)->get(route('doctors.index'));

    $response->assertOk();
    $response->assertDontSee('data-bs-target="#doctorModal"', false);
    $response->assertSee('View Only');
});

test('doctor is forbidden from posting doctor create or delete actions', function () {
    $doctorUser = User::factory()->doctor()->create();

    $response = $this->actingAs($doctorUser)->post(route('doctors.store'), [
        'name' => 'Illegal Doctor',
        'email' => 'illegal@test.com',
        'phone' => '123456',
        'speciality' => 'Surgery',
        'pmdc' => '12345',
        'fee' => 1000,
    ]);

    $response->assertRedirect(route('welcome'));
    $this->assertDatabaseMissing('doctors', ['email' => 'illegal@test.com']);
});

// HR ROLE PERMISSION TESTS
test('hr can manage doctors and staff', function () {
    $hrUser = User::factory()->hr()->create();

    $this->actingAs($hrUser)->get(route('doctors.index'))->assertOk();
    $this->actingAs($hrUser)->get(route('staff.index'))->assertOk();

    $response = $this->actingAs($hrUser)->post(route('doctors.store'), [
        'name' => 'Dr. Hina Rizvi',
        'email' => 'hina.rizvi@hospital.test',
        'phone' => '03331112233',
        'speciality' => 'Gynecologist',
        'pmdc' => 'PMC-77441',
        'fee' => 3000,
        'duty_days' => ['Monday', 'Wednesday'],
        'duty_time' => [
            'Monday' => ['start' => '10:00', 'end' => '14:00'],
            'Wednesday' => ['start' => '10:00', 'end' => '14:00'],
        ],
    ]);

    $response->assertRedirect(route('doctors.index'));
    $this->assertDatabaseHas('doctors', ['email' => 'hina.rizvi@hospital.test']);
});

test('hr is blocked from hospital payments and users management', function () {
    $hrUser = User::factory()->hr()->create();

    $this->actingAs($hrUser)->get(route('hospital-payments'))->assertRedirect(route('welcome'));
    $this->actingAs($hrUser)->get(route('users.index'))->assertRedirect(route('welcome'));
});

// ACCOUNTANT ROLE PERMISSION TESTS
test('accountant can access hospital payments, expenses, and categories', function () {
    $accountantUser = User::factory()->accountant()->create();

    $this->actingAs($accountantUser)->get(route('hospital-payments'))->assertOk();
    $this->actingAs($accountantUser)->get(route('expenses.index'))->assertOk();
    $this->actingAs($accountantUser)->get(route('category.index'))->assertOk();
});

test('accountant is blocked from staff, appointments, and users management', function () {
    $accountantUser = User::factory()->accountant()->create();

    $this->actingAs($accountantUser)->get(route('staff.index'))->assertRedirect(route('welcome'));
    $this->actingAs($accountantUser)->get(route('appointment.index'))->assertRedirect(route('welcome'));
    $this->actingAs($accountantUser)->get(route('users.index'))->assertRedirect(route('welcome'));
});

// RECEPTIONIST ROLE PERMISSION TESTS
test('receptionist can access appointments and patients directory', function () {
    $receptionistUser = User::factory()->receptionist()->create();

    $this->actingAs($receptionistUser)->get(route('appointment.index'))->assertOk();
    $this->actingAs($receptionistUser)->get(route('patients.index'))->assertOk();
    $this->actingAs($receptionistUser)->get(route('doctors.index'))->assertOk();
});

test('receptionist is blocked from payments, expenses, and staff management', function () {
    $receptionistUser = User::factory()->receptionist()->create();

    $this->actingAs($receptionistUser)->get(route('hospital-payments'))->assertRedirect(route('welcome'));
    $this->actingAs($receptionistUser)->get(route('expenses.index'))->assertRedirect(route('welcome'));
    $this->actingAs($receptionistUser)->get(route('staff.index'))->assertRedirect(route('welcome'));
});

// ADMIN ROLE & USERS MANAGEMENT TESTS
test('admin can access users management blade', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('users.index'));

    $response->assertOk();
    $response->assertViewIs('users.index');
    $response->assertSee('Role-Based Access Control');
});

test('non-admin users cannot access users management blade', function () {
    $doctor = User::factory()->doctor()->create();
    $hr = User::factory()->hr()->create();
    $accountant = User::factory()->accountant()->create();
    $receptionist = User::factory()->receptionist()->create();

    $this->actingAs($doctor)->get(route('users.index'))->assertRedirect(route('welcome'));
    $this->actingAs($hr)->get(route('users.index'))->assertRedirect(route('welcome'));
    $this->actingAs($accountant)->get(route('users.index'))->assertRedirect(route('welcome'));
    $this->actingAs($receptionist)->get(route('users.index'))->assertRedirect(route('welcome'));
});

test('admin can create a new staff account with an assigned role in users blade', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->post(route('users.store'), [
        'name' => 'Sara Bilal',
        'email' => 'sara.bilal@hospital.test',
        'phone' => '03009988776',
        'role' => User::ROLE_ACCOUNTANT,
        'status' => User::STATUS_ACTIVE,
        'password' => 'secret1234',
        'password_confirmation' => 'secret1234',
    ]);

    $response->assertRedirect(route('users.index'));
    $this->assertDatabaseHas('users', [
        'email' => 'sara.bilal@hospital.test',
        'role' => User::ROLE_ACCOUNTANT,
        'status' => User::STATUS_ACTIVE,
    ]);
});

test('admin can update a user and reassign their role', function () {
    $admin = User::factory()->admin()->create();
    $targetUser = User::factory()->receptionist()->create([
        'name' => 'Old Receptionist',
        'email' => 'reception@hospital.test',
    ]);

    $response = $this->actingAs($admin)->put(route('users.update', $targetUser), [
        'name' => 'Promoted Receptionist',
        'email' => 'reception@hospital.test',
        'phone' => '03001122334',
        'role' => User::ROLE_HR,
        'status' => User::STATUS_ACTIVE,
    ]);

    $response->assertRedirect(route('users.index'));
    $this->assertDatabaseHas('users', [
        'id' => $targetUser->id,
        'name' => 'Promoted Receptionist',
        'role' => User::ROLE_HR,
    ]);
});

test('admin can toggle active status of a user', function () {
    $admin = User::factory()->admin()->create();
    $staff = User::factory()->doctor()->create(['status' => User::STATUS_ACTIVE]);

    $response = $this->actingAs($admin)->post(route('users.toggle-status', $staff));

    $response->assertRedirect(route('users.index'));
    expect($staff->fresh()->status)->toBe(User::STATUS_INACTIVE);

    $this->actingAs($admin)->post(route('users.toggle-status', $staff));
    expect($staff->fresh()->status)->toBe(User::STATUS_ACTIVE);
});

test('admin can delete a user account but cannot delete their own account', function () {
    $admin = User::factory()->admin()->create();
    $staff = User::factory()->doctor()->create();

    // Cannot delete self
    $selfDeleteResponse = $this->actingAs($admin)->delete(route('users.destroy', $admin));
    $selfDeleteResponse->assertSessionHas('error');
    $this->assertDatabaseHas('users', ['id' => $admin->id]);

    // Can delete other staff
    $deleteResponse = $this->actingAs($admin)->delete(route('users.destroy', $staff));
    $deleteResponse->assertRedirect(route('users.index'));
    $this->assertDatabaseMissing('users', ['id' => $staff->id]);
});
