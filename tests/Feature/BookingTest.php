<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\BookingProduct;
use App\Models\BookingService;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\StockMovement;
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

        $test = Livewire::actingAs($owner)
            ->test(\App\Livewire\Bookings\Create::class)
            ->set('customer_id', $customer->id)
            ->set('customer_name', $customer->name)
            ->set('customer_phone', $customer->phone)
            ->set('selected_services', [
                ['service_id' => $service->id, 'employee_id' => $employee->id]
            ])
            ->set('booking_date', now()->toDateString())
            ->set('booking_time', '10:00')
            ->call('save')
            ->assertHasNoErrors();

        $booking = Booking::first();
        $this->assertNotNull($booking);
        $this->assertEquals(100000, (float) $booking->total_amount);
        $this->assertNotNull($booking->invoice);
        $test->assertRedirect(route('owner.invoices.show', $booking->invoice->id));

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
            ->set('customer_name', $customer->name)
            ->set('customer_phone', $customer->phone)
            ->set('selected_services', [
                ['service_id' => $service->id, 'employee_id' => $employee->id]
            ])
            ->set('booking_date', $today)
            ->set('booking_time', '10:30')
            ->call('save')
            ->assertHasErrors(['selected_services.0.employee_id']);
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

    public function test_can_create_walkin_booking_without_existing_account()
    {
        $owner = User::factory()->create();
        $owner->assignRole('owner');

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

        $employee = Employee::create([
            'employee_code' => 'EMP-01',
            'full_name' => 'Jane Stylist',
            'phone' => '0812345679',
            'commission_percentage' => 10,
            'basic_salary' => 3000000,
            'employment_status' => 'active',
        ]);

        $this->assertEquals(0, Customer::count());

        $test = Livewire::actingAs($owner)
            ->test(\App\Livewire\Bookings\Create::class)
            ->set('customer_name', 'Guest User')
            ->set('customer_phone', '08987654321')
            ->set('selected_services', [
                ['service_id' => $service->id, 'employee_id' => $employee->id]
            ])
            ->set('booking_date', now()->toDateString())
            ->set('booking_time', '12:00')
            ->call('save')
            ->assertHasNoErrors();

        // Verify a new customer was created for the guest
        $this->assertEquals(1, Customer::count());
        $customer = Customer::first();
        $this->assertEquals('Guest User', $customer->name);
        $this->assertEquals('08987654321', $customer->phone);

        // Verify the booking is linked to the newly created customer
        $booking = Booking::first();
        $this->assertNotNull($booking);
        $this->assertEquals($customer->id, $booking->customer_id);
        $this->assertNotNull($booking->invoice);
        $test->assertRedirect(route('owner.invoices.show', $booking->invoice->id));
    }

    public function test_can_get_customer_suggestions_when_typing_name()
    {
        $owner = User::factory()->create();
        $owner->assignRole('owner');

        $customer = Customer::create([
            'name' => 'Alice Margatroid',
            'phone' => '0812345678',
            'email' => 'alice@example.com',
            'is_active' => true,
        ]);

        Livewire::actingAs($owner)
            ->test(\App\Livewire\Bookings\Create::class)
            ->set('customer_name', 'Alic')
            ->assertSet('customer_id', '')
            ->assertCount('customer_suggestions', 1)
            ->call('selectCustomer', $customer->id, $customer->name, $customer->phone)
            ->assertSet('customer_id', $customer->id)
            ->assertSet('customer_name', 'Alice Margatroid')
            ->assertSet('customer_phone', '0812345678')
            ->assertCount('customer_suggestions', 0);
    }

    public function test_can_create_booking_with_multiple_services()
    {
        $owner = User::factory()->create();
        $owner->assignRole('owner');

        $customer = Customer::create([
            'name' => 'Alice Doe',
            'phone' => '0812345678',
            'is_active' => true,
        ]);

        $employee1 = Employee::create([
            'employee_code' => 'EMP-01',
            'full_name' => 'Jane Stylist',
            'commission_percentage' => 10,
            'basic_salary' => 3000000,
            'employment_status' => 'active',
        ]);

        $employee2 = Employee::create([
            'employee_code' => 'EMP-02',
            'full_name' => 'John Nailist',
            'commission_percentage' => 20,
            'basic_salary' => 3000000,
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
            'name' => 'Manicure',
            'price' => 150000,
            'duration_minutes' => 30,
            'is_active' => true,
        ]);

        $today = now()->toDateString();

        Livewire::actingAs($owner)
            ->test(\App\Livewire\Bookings\Create::class)
            ->set('customer_id', $customer->id)
            ->set('customer_name', $customer->name)
            ->set('booking_date', $today)
            ->set('booking_time', '10:00')
            ->set('selected_services', [
                ['service_id' => $service1->id, 'employee_id' => $employee1->id],
                ['service_id' => $service2->id, 'employee_id' => $employee2->id],
            ])
            ->call('save')
            ->assertHasNoErrors();

        $booking = Booking::first();
        $this->assertNotNull($booking);
        $this->assertEquals(250000, (float) $booking->total_amount);

        $bookingServices = BookingService::all();
        $this->assertCount(2, $bookingServices);

        $bs1 = $bookingServices[0];
        $this->assertEquals($service1->id, $bs1->service_id);
        $this->assertEquals($employee1->id, $bs1->employee_id);
        $this->assertEquals($today . ' 10:00:00', $bs1->start_time->format('Y-m-d H:i:s'));
        $this->assertEquals($today . ' 11:00:00', $bs1->end_time->format('Y-m-d H:i:s'));

        $bs2 = $bookingServices[1];
        $this->assertEquals($service2->id, $bs2->service_id);
        $this->assertEquals($employee2->id, $bs2->employee_id);
        $this->assertEquals($today . ' 11:00:00', $bs2->start_time->format('Y-m-d H:i:s'));
        $this->assertEquals($today . ' 11:30:00', $bs2->end_time->format('Y-m-d H:i:s'));
    }

    public function test_can_edit_booking_with_multiple_services()
    {
        $owner = User::factory()->create();
        $owner->assignRole('owner');

        $customer = Customer::create([
            'name' => 'Jane Customer',
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        $employee1 = Employee::create([
            'employee_code' => 'EMP-ANNA',
            'full_name' => 'Stylist Anna',
            'role' => 'terapis',
            'employment_status' => 'active',
            'commission_percentage' => 10,
        ]);

        $employee2 = Employee::create([
            'employee_code' => 'EMP-BELLA',
            'full_name' => 'Stylist Bella',
            'role' => 'terapis',
            'employment_status' => 'active',
            'commission_percentage' => 15,
        ]);

        $category = ServiceCategory::create(['name' => 'Hair Care', 'slug' => 'hair-care']);

        $service1 = Service::create([
            'service_category_id' => $category->id,
            'name' => 'Creambath',
            'price' => 120000,
            'duration_minutes' => 60,
            'is_active' => true,
        ]);

        $service2 = Service::create([
            'service_category_id' => $category->id,
            'name' => 'Nail Art',
            'price' => 180000,
            'duration_minutes' => 45,
            'is_active' => true,
        ]);

        $today = now()->toDateString();

        $booking = Booking::create([
            'booking_number' => 'BK-EDIT-MULTI',
            'customer_id' => $customer->id,
            'booking_date' => $today,
            'booking_start' => $today . ' 13:00:00',
            'booking_end' => $today . ' 14:00:00',
            'dp_amount' => 0,
            'total_amount' => 120000,
            'status' => 'pending',
        ]);

        BookingService::create([
            'booking_id' => $booking->id,
            'service_id' => $service1->id,
            'employee_id' => $employee1->id,
            'price' => 120000,
            'commission_percentage' => 10,
            'commission_amount' => 12000,
            'duration_minutes' => 60,
            'start_time' => $today . ' 13:00:00',
            'end_time' => $today . ' 14:00:00',
        ]);

        // Edit the booking to include both Service 1 and Service 2
        Livewire::actingAs($owner)
            ->test(\App\Livewire\Bookings\Edit::class, ['booking' => $booking])
            ->set('booking_date', $today)
            ->set('booking_time', '13:00')
            ->set('selected_services', [
                ['service_id' => $service1->id, 'employee_id' => $employee1->id],
                ['service_id' => $service2->id, 'employee_id' => $employee2->id],
            ])
            ->set('status', 'confirmed')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('owner.bookings.index'));

        $booking->refresh();
        $this->assertEquals(300000, (float) $booking->total_amount);
        $this->assertEquals('confirmed', $booking->status);
        $this->assertEquals($today . ' 14:45:00', $booking->booking_end->format('Y-m-d H:i:s'));

        $bookingServices = $booking->services()->orderBy('start_time')->get();
        $this->assertCount(2, $bookingServices);

        $this->assertEquals($service1->id, $bookingServices[0]->service_id);
        $this->assertEquals($employee1->id, $bookingServices[0]->employee_id);
        $this->assertEquals($today . ' 13:00:00', $bookingServices[0]->start_time->format('Y-m-d H:i:s'));
        $this->assertEquals($today . ' 14:00:00', $bookingServices[0]->end_time->format('Y-m-d H:i:s'));

        $this->assertEquals($service2->id, $bookingServices[1]->service_id);
        $this->assertEquals($employee2->id, $bookingServices[1]->employee_id);
        $this->assertEquals($today . ' 14:00:00', $bookingServices[1]->start_time->format('Y-m-d H:i:s'));
        $this->assertEquals($today . ' 14:45:00', $bookingServices[1]->end_time->format('Y-m-d H:i:s'));
        $this->assertEquals(27000, (float) $bookingServices[1]->commission_amount); // 15% of 180,000
    }

    public function test_edit_booking_detects_stylist_schedule_conflict()
    {
        $owner = User::factory()->create();
        $owner->assignRole('owner');

        $customer1 = Customer::create([
            'name' => 'Customer 1',
            'phone' => '081111111111',
            'is_active' => true,
        ]);

        $customer2 = Customer::create([
            'name' => 'Customer 2',
            'phone' => '082222222222',
            'is_active' => true,
        ]);

        $employee = Employee::create([
            'employee_code' => 'EMP-BUSY',
            'full_name' => 'Busy Stylist',
            'role' => 'terapis',
            'employment_status' => 'active',
            'commission_percentage' => 10,
        ]);

        $category = ServiceCategory::create(['name' => 'Facial', 'slug' => 'facial']);

        $service = Service::create([
            'service_category_id' => $category->id,
            'name' => 'Facial Glow',
            'price' => 200000,
            'duration_minutes' => 60,
            'is_active' => true,
        ]);

        $today = now()->toDateString();

        // Existing booking for Busy Stylist from 15:00 to 16:00
        $existingBooking = Booking::create([
            'booking_number' => 'BK-EXISTING',
            'customer_id' => $customer1->id,
            'booking_date' => $today,
            'booking_start' => $today . ' 15:00:00',
            'booking_end' => $today . ' 16:00:00',
            'dp_amount' => 0,
            'total_amount' => 200000,
            'status' => 'confirmed',
        ]);

        BookingService::create([
            'booking_id' => $existingBooking->id,
            'service_id' => $service->id,
            'employee_id' => $employee->id,
            'price' => 200000,
            'commission_percentage' => 10,
            'commission_amount' => 20000,
            'duration_minutes' => 60,
            'start_time' => $today . ' 15:00:00',
            'end_time' => $today . ' 16:00:00',
        ]);

        // Another booking for Customer 2 originally at 10:00
        $bookingToEdit = Booking::create([
            'booking_number' => 'BK-TO-EDIT',
            'customer_id' => $customer2->id,
            'booking_date' => $today,
            'booking_start' => $today . ' 10:00:00',
            'booking_end' => $today . ' 11:00:00',
            'dp_amount' => 0,
            'total_amount' => 200000,
            'status' => 'pending',
        ]);

        BookingService::create([
            'booking_id' => $bookingToEdit->id,
            'service_id' => $service->id,
            'employee_id' => $employee->id,
            'price' => 200000,
            'commission_percentage' => 10,
            'commission_amount' => 20000,
            'duration_minutes' => 60,
            'start_time' => $today . ' 10:00:00',
            'end_time' => $today . ' 11:00:00',
        ]);

        // Try to edit bookingToEdit to conflict with existingBooking (at 15:30)
        Livewire::actingAs($owner)
            ->test(\App\Livewire\Bookings\Edit::class, ['booking' => $bookingToEdit])
            ->set('booking_date', $today)
            ->set('booking_time', '15:30')
            ->set('selected_services', [
                ['service_id' => $service->id, 'employee_id' => $employee->id],
            ])
            ->call('save')
            ->assertHasErrors(['selected_services.0.employee_id']);
    }

    public function test_can_create_booking_with_product_and_checkout_flow()
    {
        $owner = User::factory()->create();
        $owner->assignRole('owner');

        $customer = Customer::create([
            'name' => 'Sarah Johnson',
            'phone' => '08123456789',
            'is_active' => true,
        ]);

        $employee = Employee::create([
            'employee_code' => 'EMP-PRODUCT-01',
            'full_name' => 'Mia Stylist',
            'commission_percentage' => 10,
            'employment_status' => 'active',
        ]);

        $category = ServiceCategory::create([
            'name' => 'Hair Care',
            'slug' => 'hair-care',
            'is_active' => true,
        ]);

        $service = Service::create([
            'service_category_id' => $category->id,
            'name' => 'Hair Treatment',
            'price' => 150000,
            'duration_minutes' => 60,
            'is_active' => true,
        ]);

        $product = Product::create([
            'name' => 'Hair Serum Gold',
            'sku' => 'SERUM-001',
            'category' => 'Hair Care',
            'unit' => 'bottle',
            'cost_price' => 50000,
            'selling_price' => 100000,
            'current_stock' => 10,
            'minimum_stock' => 2,
            'is_active' => true,
        ]);

        $test = Livewire::actingAs($owner)
            ->test(\App\Livewire\Bookings\Create::class)
            ->set('customer_id', $customer->id)
            ->set('customer_name', $customer->name)
            ->set('customer_phone', $customer->phone)
            ->set('selected_services', [
                ['service_id' => $service->id, 'employee_id' => $employee->id]
            ])
            ->set('selected_products', [
                [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => (float) $product->selling_price,
                    'quantity' => 2,
                    'max_stock' => 10,
                ]
            ])
            ->set('booking_date', now()->toDateString())
            ->set('booking_time', '11:00')
            ->call('save')
            ->assertHasNoErrors();

        $booking = Booking::where('customer_id', $customer->id)->first();
        $this->assertNotNull($booking);
        // Total should be 150,000 (service) + 200,000 (2 x 100,000 product) = 350,000
        $this->assertEquals(350000, (float) $booking->total_amount);

        // Booking product checked
        $bookingProduct = BookingProduct::where('booking_id', $booking->id)->first();
        $this->assertNotNull($bookingProduct);
        $this->assertEquals($product->id, $bookingProduct->product_id);
        $this->assertEquals(2, $bookingProduct->quantity);
        $this->assertEquals(100000, (float) $bookingProduct->unit_price);
        $this->assertEquals(200000, (float) $bookingProduct->subtotal);

        // Stock should be decremented from 10 to 8
        $product->refresh();
        $this->assertEquals(8, $product->current_stock);

        // Stock movement created
        $stockMovement = StockMovement::where('product_id', $product->id)->latest()->first();
        $this->assertNotNull($stockMovement);
        $this->assertEquals('out', $stockMovement->type);
        $this->assertEquals(2, $stockMovement->quantity);

        // Invoice should be created with 350,000
        $invoice = Invoice::where('booking_id', $booking->id)->first();
        $this->assertNotNull($invoice);
        $this->assertEquals(350000, (float) $invoice->subtotal);
        $this->assertEquals(350000, (float) $invoice->grand_total);
        $this->assertEquals('unpaid', $invoice->payment_status);

        // Checkout flow: redirect to invoice show
        $test->assertRedirect(route('owner.invoices.show', $invoice->id));
    }

    public function test_can_edit_booking_products_with_stock_reconciliation()
    {
        $owner = User::factory()->create();
        $owner->assignRole('owner');

        $customer = Customer::create([
            'name' => 'Alice Edit',
            'phone' => '081299998888',
            'is_active' => true,
        ]);

        $employee = Employee::create([
            'employee_code' => 'EMP-EDIT-01',
            'full_name' => 'Celia Hair',
            'commission_percentage' => 10,
            'employment_status' => 'active',
        ]);

        $category = ServiceCategory::create([
            'name' => 'Spa',
            'slug' => 'spa',
            'is_active' => true,
        ]);

        $service = Service::create([
            'service_category_id' => $category->id,
            'name' => 'Creambath',
            'price' => 100000,
            'duration_minutes' => 60,
            'is_active' => true,
        ]);

        $product = Product::create([
            'name' => 'Shampoo Bottle',
            'sku' => 'SHP-001',
            'category' => 'Hair Care',
            'unit' => 'bottle',
            'cost_price' => 30000,
            'selling_price' => 60000,
            'current_stock' => 5, // 5 currently in stock
            'minimum_stock' => 1,
            'is_active' => true,
        ]);

        $today = now()->toDateString();

        $booking = Booking::create([
            'booking_number' => 'BK-EDIT-TEST',
            'customer_id' => $customer->id,
            'booking_date' => $today,
            'booking_start' => $today . ' 14:00:00',
            'booking_end' => $today . ' 15:00:00',
            'dp_amount' => 0,
            'total_amount' => 160000,
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
            'start_time' => $today . ' 14:00:00',
            'end_time' => $today . ' 15:00:00',
        ]);

        // Previously booked 1 product
        BookingProduct::create([
            'booking_id' => $booking->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => 60000,
            'subtotal' => 60000,
        ]);

        $invoice = Invoice::create([
            'invoice_number' => 'INV-EDIT-TEST',
            'booking_id' => $booking->id,
            'customer_id' => $customer->id,
            'subtotal' => 160000,
            'discount' => 0,
            'grand_total' => 160000,
            'payment_status' => 'unpaid',
        ]);

        // Now edit to increase quantity to 3 (requires 2 more from remaining stock of 5)
        Livewire::actingAs($owner)
            ->test(\App\Livewire\Bookings\Edit::class, ['booking' => $booking])
            ->set('selected_products', [
                [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => (float) $product->selling_price,
                    'quantity' => 3,
                    'max_stock' => 6,
                ]
            ])
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('owner.bookings.index'));

        $booking->refresh();
        // 100,000 + 3 x 60,000 = 280,000
        $this->assertEquals(280000, (float) $booking->total_amount);

        $invoice->refresh();
        $this->assertEquals(280000, (float) $invoice->subtotal);
        $this->assertEquals(280000, (float) $invoice->grand_total);

        // Product stock: was 5, +1 restored, -3 deducted = 3 remaining
        $product->refresh();
        $this->assertEquals(3, $product->current_stock);
    }

    public function test_payment_checkout_redirects_to_invoice_success_page()
    {
        $owner = User::factory()->create();
        $owner->assignRole('owner');

        $customer = Customer::create([
            'name' => 'Bella Pay',
            'phone' => '081300001111',
            'is_active' => true,
        ]);

        $booking = Booking::create([
            'booking_number' => 'BK-PAY-01',
            'customer_id' => $customer->id,
            'booking_date' => now()->toDateString(),
            'booking_start' => now(),
            'booking_end' => now()->addHour(),
            'dp_amount' => 0,
            'total_amount' => 120000,
            'status' => 'pending',
        ]);

        $invoice = Invoice::create([
            'invoice_number' => 'INV-PAY-01',
            'booking_id' => $booking->id,
            'customer_id' => $customer->id,
            'subtotal' => 120000,
            'discount' => 0,
            'grand_total' => 120000,
            'payment_status' => 'unpaid',
        ]);

        // Process payment in Invoice Show
        Livewire::actingAs($owner)
            ->test(\App\Livewire\Invoices\Show::class, ['invoice' => $invoice])
            ->set('payment_method', 'Cash')
            ->set('cash_received', 150000)
            ->call('markPaid')
            ->assertHasNoErrors()
            ->assertRedirect(route('owner.invoices.success', $invoice->id));

        $invoice->refresh();
        $this->assertEquals('paid', $invoice->payment_status);
        $this->assertEquals('Cash', $invoice->payment_method);
        $this->assertEquals(150000, (float) $invoice->cash_received);
        $this->assertEquals(30000, (float) $invoice->cash_change);

        // Booking status updated to completed
        $booking->refresh();
        $this->assertEquals('completed', $booking->status);

        // Visit the success page
        Livewire::actingAs($owner)
            ->test(\App\Livewire\Invoices\Success::class, ['invoice' => $invoice])
            ->assertOk()
            ->assertSee('Transaksi Berhasil!')
            ->assertSee('INV-PAY-01')
            ->assertSee('Rp 120.000');
    }
}

