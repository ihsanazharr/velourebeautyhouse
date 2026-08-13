<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_owner_can_create_kasir_employee_with_user_account()
    {
        $owner = User::factory()->create();
        $owner->assignRole('owner');

        $this->actingAs($owner);

        Livewire::test(\App\Livewire\Employees\Create::class)
            ->set('name', 'Kasir Baru')
            ->set('email', 'kasirbaru@veloure.com')
            ->set('password', 'password123')
            ->set('role', 'kasir')
            ->set('phone', '08123456789')
            ->set('hire_date', '2026-07-31')
            ->set('basic_salary', 2500000)
            ->set('meal_allowance', 15000)
            ->set('overtime_rate', 15000)
            ->set('employment_status', 'active')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('owner.employees.index'));

        // Assert User is created
        $user = User::where('email', 'kasirbaru@veloure.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('kasir'));

        // Assert Employee is created and linked
        $employee = Employee::where('full_name', 'Kasir Baru')->first();
        $this->assertNotNull($employee);
        $this->assertEquals($user->id, $employee->user_id);
        $this->assertEquals('kasir', $employee->role);
    }

    public function test_owner_can_create_stylist_employee_without_user_account()
    {
        $owner = User::factory()->create();
        $owner->assignRole('owner');

        $this->actingAs($owner);

        Livewire::test(\App\Livewire\Employees\Create::class)
            ->set('name', 'Stylist Baru')
            ->set('role', 'hairstylist')
            ->set('phone', '08123456780')
            ->set('hire_date', '2026-07-31')
            ->set('basic_salary', 2000000)
            ->set('meal_allowance', 15000)
            ->set('overtime_rate', 15000)
            ->set('employment_status', 'active')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('owner.employees.index'));

        // Assert NO User is created
        $userCount = User::where('name', 'Stylist Baru')->count();
        $this->assertEquals(0, $userCount);

        // Assert Employee is created with user_id = null
        $employee = Employee::where('full_name', 'Stylist Baru')->first();
        $this->assertNotNull($employee);
        $this->assertNull($employee->user_id);
        $this->assertEquals('hairstylist', $employee->role);
    }

    public function test_editing_employee_roles_syncs_user_accounts()
    {
        $owner = User::factory()->create();
        $owner->assignRole('owner');

        $this->actingAs($owner);

        // 1. Create a hairstylist (no user account)
        $employee = Employee::create([
            'employee_code' => 'EMP-0001',
            'full_name' => 'Stylist X',
            'role' => 'hairstylist',
            'hire_date' => '2026-07-31',
            'basic_salary' => 2000000,
            'employment_status' => 'active',
        ]);

        // Edit: Change to kasir (should create user account)
        Livewire::test(\App\Livewire\Employees\Edit::class, ['employee' => $employee])
            ->set('role', 'kasir')
            ->set('email', 'kasirx@veloure.com')
            ->set('password', 'password123')
            ->call('save')
            ->assertHasNoErrors();

        $employee->refresh();
        $this->assertNotNull($employee->user_id);
        $user = User::find($employee->user_id);
        $this->assertEquals('kasirx@veloure.com', $user->email);
        $this->assertTrue($user->hasRole('kasir'));

        // Edit: Change back to nailist_senior (should delete user account)
        Livewire::test(\App\Livewire\Employees\Edit::class, ['employee' => $employee])
            ->set('role', 'nailist_senior')
            ->call('save')
            ->assertHasNoErrors();

        $employee->refresh();
        $this->assertNull($employee->user_id);
        $this->assertNull(User::find($user->id));
    }

    public function test_can_create_employee_with_custom_role_and_null_compensation_fields()
    {
        $owner = User::factory()->create();
        $owner->assignRole('owner');

        $this->actingAs($owner);

        Livewire::test(\App\Livewire\Employees\Create::class)
            ->set('name', 'Terapis Baru')
            ->set('role', 'Therapist Senior')
            ->set('phone', '08123456781')
            ->set('hire_date', '2026-07-31')
            ->set('basic_salary', null)
            ->set('meal_allowance', null)
            ->set('overtime_rate', null)
            ->set('employment_status', 'active')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('owner.employees.index'));

        $employee = Employee::where('full_name', 'Terapis Baru')->first();
        $this->assertNotNull($employee);
        $this->assertEquals('Therapist Senior', $employee->role);
        $this->assertNull($employee->basic_salary);
        $this->assertNull($employee->meal_allowance);
        $this->assertNull($employee->overtime_rate);
    }
}
