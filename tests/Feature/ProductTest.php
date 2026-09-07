<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_owner_can_access_product_create_page()
    {
        $owner = User::factory()->create();
        $owner->assignRole('owner');

        $response = $this->actingAs($owner)->get(route('owner.products.create'));
        $response->assertOk();
    }

    public function test_owner_can_create_product()
    {
        $owner = User::factory()->create();
        $owner->assignRole('owner');

        Livewire::actingAs($owner)
            ->test(\App\Livewire\Products\Create::class)
            ->set('name', 'Shampoo Anti Dandruff')
            ->set('category', 'Hair Care')
            ->set('unit', 'botol')
            ->set('cost_price', 40000)
            ->set('selling_price', 75000)
            ->set('current_stock', 15)
            ->set('minimum_stock', 3)
            ->set('alert_stock', 5)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('owner.products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Shampoo Anti Dandruff',
            'current_stock' => 15,
        ]);
    }
}
