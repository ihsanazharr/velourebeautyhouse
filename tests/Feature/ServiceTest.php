<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_owner_can_toggle_service_active_status()
    {
        $owner = User::factory()->create();
        $owner->assignRole('owner');

        $category = ServiceCategory::create([
            'name' => 'Nails',
            'slug' => 'nails',
            'is_active' => true,
        ]);

        $service = Service::create([
            'service_category_id' => $category->id,
            'name' => 'Manicure',
            'duration_minutes' => 30,
            'price' => 45000,
            'commission_rate' => 10,
            'requires_booking' => true,
            'is_active' => true,
        ]);

        $this->assertTrue($service->is_active);

        $this->actingAs($owner);

        Livewire::test(\App\Livewire\Services\Index::class)
            ->call('toggleActive', $service->id)
            ->assertHasNoErrors();

        $service->refresh();
        $this->assertFalse($service->is_active);

        Livewire::test(\App\Livewire\Services\Index::class)
            ->call('toggleActive', $service->id)
            ->assertHasNoErrors();

        $service->refresh();
        $this->assertTrue($service->is_active);
    }
}
