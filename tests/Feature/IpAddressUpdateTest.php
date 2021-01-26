<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\IpAddress;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IpAddressUpdateTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_ipaddress_update()
    {
        $ipAddress = IpAddress::factory()->create();

        $this->actingAs($ipAddress->user)->json('PUT', route('ip-address.update', ['ip_address' => $ipAddress]), [
            'label' => $label = $this->faker()->text(50),
        ])->assertStatus(200);

        $this->assertDatabaseHas('ip_addresses', [
            'id' => $ipAddress->id,
            'ip_address' => $ipAddress->ip_address,
            'label' => $label,
            'user_id' => $ipAddress->user_id,
        ]);
    }

    public function test_ipaddress_update_authorization()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $ipAddress = IpAddress::factory()->create(['user_id' => $user2->id]);

        $this->actingAs($user)->json('PUT', route('ip-address.update', ['ip_address' => $ipAddress]), [
            'label' => $this->faker()->text(50),
        ])->assertStatus(403);
    }

    public function test_ipaddress_update_label_field_validation()
    {
        $ipAddress = IpAddress::factory()->create();

        $this->actingAs($ipAddress->user)->json('PUT', route('ip-address.update', ['ip_address' => $ipAddress]), [
            'label' => '',
        ])->assertStatus(422)->assertJsonStructure([
            'errors' => ['label']
        ]);
    }
}
