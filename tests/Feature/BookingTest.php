<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\BookingService;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_can_create_booking_with_commission_calculation()
    {
        $owner = User::factory()->create();
        $owner->assignRole('owner');

        $customer = Customer::create([
            'name' => 'John Doe',
            'phone' => '0812345678',
            'email' => 'john@example.com',
            'is_active' => true,
        ]);

        $employee = Employee::create([
            'employee_code' => 'EMP-01',
            'full_name' => 'Jane Stylist',
            'phone' => '0812345679',
            'commission_percentage' => 10,
            'basic_salary' => 3000000,
            'employment_status' => 'active',
        ]);

        $category = ServiceCategory::create([
            'name' => 'Hair Services',
            'slug' => 'hair-services',
            'is_active' => true,
        ]);

        $service = Service::create([
            'service_category_id' => $category->id,
            'name' => 'Haircut',
            'price' => 100000,
            'duration_minutes' => 60,
            'is_active' => true,
        ]);

        Livewire::actingAs($owner)
            ->test(\App\Livewire\Bookings\Create::class)
            ->set('customer_id', $customer->id)
            ->set('service_id', $service->id)
            ->set('employee_id', $employee->id)
            ->set('booking_date', now()->toDateString())
            ->set('booking_time', '10:00')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('owner.bookings.index'));

        $booking = Booking::first();
        $this->assertNotNull($booking);
        $this->assertEquals(100000, (float) $booking->total_amount);

        $bookingService = BookingService::first();
        $this->assertNotNull($bookingService);
        $this->assertEquals(100000, (float) $bookingService->price);
        $this->assertEquals(10, (float) $bookingService->commission_percentage);
        $this->assertEquals(10000, (float) $bookingService->commission_amount);
    }

    public function test_cannot_create_overlapping_booking_for_same_stylist()
    {
        $owner = User::factory()->create();
        $owner->assignRole('owner');

        $customer = Customer::create([
            'name' => 'John Doe',
            'phone' => '0812345678',
            'email' => 'john@example.com',
            'is_active' => true,
        ]);

        $employee = Employee::create([
            'employee_code' => 'EMP-01',
            'full_name' => 'Jane Stylist',
            'phone' => '0812345679',
            'commission_percentage' => 10,
            'basic_salary' => 3000000,
            'employment_status' => 'active',
        ]);

        $category = ServiceCategory::create([
            'name' => 'Hair Services',
            'slug' => 'hair-services',
            'is_active' => true,
        ]);

        $service = Service::create([
            'service_category_id' => $category->id,
            'name' => 'Haircut',
            'price' => 100000,
            'duration_minutes' => 60,
            'is_active' => true,
        ]);

        $today = now()->toDateString();

        // Create first booking
        $booking = Booking::create([
            'booking_number' => 'BK-1',
            'customer_id' => $customer->id,
            'booking_date' => $today,
            'booking_start' => $today . ' 10:00:00',
            'booking_end' => $today . ' 11:00:00',
            'dp_amount' => 0,
            'total_amount' => 100000,
            'status' => 'pending',
        ]);

        BookingService::create([
            'booking_id' => $booking->id,
            'service_id' => $service->id,
            'employee_id' => $employee->id,
            'price' => 100000,
            'commission_percentage' => 10,
            'commission_amount' => 10000,
            'duration_minutes' => 60,
            'start_time' => $today . ' 10:00:00',
            'end_time' => $today . ' 11:00:00',
        ]);

        // Try to create overlapping booking (10:30) via Livewire
        Livewire::actingAs($owner)
            ->test(\App\Livewire\Bookings\Create::class)
            ->set('customer_id', $customer->id)
            ->set('service_id', $service->id)
            ->set('employee_id', $employee->id)
            ->set('booking_date', $today)
            ->set('booking_time', '10:30')
            ->call('save')
            ->assertHasErrors(['employee_id']);
    }

    public function test_can_edit_booking_and_recalculate_commission()
    {
        $owner = User::factory()->create();
        $owner->assignRole('owner');

        $customer = Customer::create([
            'name' => 'John Doe',
            'phone' => '0812345678',
            'email' => 'john@example.com',
            'is_active' => true,
        ]);

        $employee1 = Employee::create([
            'employee_code' => 'EMP-01',
            'full_name' => 'Jane Stylist',
            'phone' => '0812345679',
            'commission_percentage' => 10,
            'basic_salary' => 3000000,
            'employment_status' => 'active',
        ]);

        $employee2 = Employee::create([
            'employee_code' => 'EMP-02',
            'full_name' => 'Mary Senior',
            'phone' => '0812345670',
            'commission_percentage' => 20,
            'basic_salary' => 4000000,
            'employment_status' => 'active',
        ]);

        $category = ServiceCategory::create([
            'name' => 'Hair Services',
            'slug' => 'hair-services',
            'is_active' => true,
        ]);

        $service1 = Service::create([
            'service_category_id' => $category->id,
            'name' => 'Haircut',
            'price' => 100000,
            'duration_minutes' => 60,
            'is_active' => true,
        ]);

        $service2 = Service::create([
            'service_category_id' => $category->id,
            'name' => 'Hair Coloring',
            'price' => 300000,
            'duration_minutes' => 120,
            'is_active' => true,
        ]);

        $today = now()->toDateString();

        $booking = Booking::create([
            'booking_number' => 'BK-1',
            'customer_id' => $customer->id,
            'booking_date' => $today,
            'booking_start' => $today . ' 10:00:00',
            'booking_end' => $today . ' 11:00:00',
            'dp_amount' => 0,
            'total_amount' => 100000,
            'status' => 'pending',
        ]);

        BookingService::create([
            'booking_id' => $booking->id,
            'service_id' => $service1->id,
            'employee_id' => $employee1->id,
            'price' => 100000,
            'commission_percentage' => 10,
            'commission_amount' => 10000,
            'duration_minutes' => 60,
            'start_time' => $today . ' 10:00:00',
            'end_time' => $today . ' 11:00:00',
        ]);

        // Edit booking: change service to Service2 and stylist to Employee2
        Livewire::actingAs($owner)
            ->test(\App\Livewire\Bookings\Edit::class, ['booking' => $booking])
            ->set('service_id', $service2->id)
            ->set('employee_id', $employee2->id)
            ->set('status', 'confirmed')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('owner.bookings.index'));

        $booking->refresh();
        $this->assertEquals(300000, (float) $booking->total_amount);
        $this->assertEquals('confirmed', $booking->status);

        // check if duration end time has updated (10:00 + 120 mins = 12:00)
        $this->assertEquals($today . ' 12:00:00', $booking->booking_end->format('Y-m-d H:i:s'));

        $bookingService = BookingService::first();
        $this->assertEquals($service2->id, $bookingService->service_id);
        $this->assertEquals($employee2->id, $bookingService->employee_id);
        $this->assertEquals(300000, (float) $bookingService->price);
        $this->assertEquals(20, (float) $bookingService->commission_percentage);
        $this->assertEquals(60000, (float) $bookingService->commission_amount);
        $this->assertEquals(120, $bookingService->duration_minutes);
        $this->assertEquals($today . ' 12:00:00', $bookingService->end_time->format('Y-m-d H:i:s'));
    }
}
