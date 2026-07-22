<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response
            ->assertOk()
            ->assertSeeVolt('pages.auth.login');
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $component = Volt::test('pages.auth.login')
            ->set('form.email', $user->email)
            ->set('form.password', 'password');

        $component->call('login');

        $component
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $component = Volt::test('pages.auth.login')
            ->set('form.email', $user->email)
            ->set('form.password', 'wrong-password');

        $component->call('login');

        $component
            ->assertHasErrors()
            ->assertNoRedirect();

        $this->assertGuest();
    }

    public function test_navigation_menu_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/dashboard');

        $response
            ->assertOk()
            ->assertSeeVolt('layout.navigation');
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $component = Volt::test('layout.navigation');

        $component->call('logout');

        $component
            ->assertHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
    }

    public function test_customer_can_authenticate_using_phone_number(): void
    {
        $user = User::factory()->create();
        $user->assignRole('customer');

        \App\Models\Customer::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'phone' => '081234567890',
            'email' => $user->email,
            'is_active' => true,
        ]);

        $component = Volt::test('pages.auth.login')
            ->set('form.email', '081234567890')
            ->set('form.password', 'password');

        $component->call('login');

        $component
            ->assertHasNoErrors()
            ->assertRedirect(route('customer.dashboard', absolute: false));

        $this->assertAuthenticated();
    }

    public function test_employee_can_authenticate_using_phone_number(): void
    {
        $user = User::factory()->create();
        // Give general employee roles that resolve to dashboard redirect
        $user->assignRole('owner');

        \App\Models\Employee::create([
            'user_id' => $user->id,
            'employee_code' => 'EMP-TEST',
            'full_name' => $user->name,
            'phone' => '081234567891',
            'commission_percentage' => 10,
            'basic_salary' => 3000000,
            'employment_status' => 'active',
        ]);

        $component = Volt::test('pages.auth.login')
            ->set('form.email', '081234567891')
            ->set('form.password', 'password');

        $component->call('login');

        $component
            ->assertHasNoErrors()
            ->assertRedirect(route('owner.dashboard', absolute: false));

        $this->assertAuthenticated();
    }
}
