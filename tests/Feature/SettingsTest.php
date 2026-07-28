<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        
        // Seed default setting
        Setting::create([
            'company_name' => 'Veloure Beauty House',
            'address' => 'Jl. Kenanga No. 12',
            'phone' => '081234567890',
            'whatsapp_number' => '081234567890',
            'open_time' => '11:00',
            'close_time' => '20:00',
        ]);
    }

    public function test_owner_can_access_and_save_settings()
    {
        $owner = User::factory()->create();
        $owner->assignRole('owner');

        $this->actingAs($owner);

        Livewire::test(\App\Livewire\Owner\Settings::class)
            ->set('company_name', 'Veloure New Name')
            ->set('address', 'New Address')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('owner.settings.edit'));

        $setting = Setting::first();
        $this->assertEquals('Veloure New Name', $setting->company_name);
        $this->assertEquals('New Address', $setting->address);
    }

    public function test_owner_can_upload_and_delete_hero_image()
    {
        Storage::fake('public');

        $owner = User::factory()->create();
        $owner->assignRole('owner');

        $this->actingAs($owner);

        $file = UploadedFile::fake()->image('hero.jpg');

        Livewire::test(\App\Livewire\Owner\Settings::class)
            ->set('hero_image_file', $file)
            ->call('save')
            ->assertHasNoErrors();

        $setting = Setting::first();
        $this->assertNotNull($setting->hero_image);
        Storage::disk('public')->assertExists($setting->hero_image);

        // Test delete hero image
        Livewire::test(\App\Livewire\Owner\Settings::class)
            ->call('deleteHeroImage')
            ->assertHasNoErrors();

        $setting->refresh();
        $this->assertNull($setting->hero_image);
    }

    public function test_non_owner_cannot_access_settings()
    {
        $cashier = User::factory()->create();
        $cashier->assignRole('kasir');

        $response = $this->actingAs($cashier)->get(route('owner.settings.edit'));
        $response->assertStatus(403);
    }
}
