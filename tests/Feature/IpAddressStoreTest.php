<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IpAddressStoreTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_ipaddress_store()
    {
        Sanctum::actingAs($user = User::factory()->create());

        $this->json('POST', route('ip-address.store'), [
            'label' => $label = $this->faker()->text(50),
            'ip_address' => $ip = $this->faker()->ipv4,
        ])->assertStatus(201);

        $this->assertDatabaseHas('ip_addresses', [
            'label' => $label,
            'ip_address' => $ip,
            'user_id' => $user->id,
        ]);
    }

    public function test_ipaddress_store_unauthenticated_user()
    {
        $this->json('POST', route('ip-address.store'), [
            'label' => $this->faker()->text(50),
            'ip_address' => $this->faker()->ipv4,
        ])->assertStatus(401);
    }

    public function test_ipaddress_store_label_validation()
    {
        Sanctum::actingAs($user = User::factory()->create());

        $this->json('POST', route('ip-address.store'), [
            'label' => '',
            'ip_address' => $this->faker()->ipv4,
        ])->assertStatus(422)->assertJsonStructure([
            'errors' => ['label']
        ]);
    }

    public function test_ipaddress_store_ip_field_validation()
    {
        Sanctum::actingAs($user = User::factory()->create());
        // IP address field is empty
        $this->json('POST', route('ip-address.store'), [
            'label' => $this->faker()->text(50),
            'ip_address' => '',
        ])->assertStatus(422)->assertJsonStructure([
            'errors' => ['ip_address']
        ]);
        // Invalid ip address
        $this->json('POST', route('ip-address.store'), [
            'label' => $this->faker()->text(50),
            'ip_address' => 'not_ip_address',
        ])->assertStatus(422)->assertJsonStructure([
            'errors' => ['ip_address']
        ]);
    }
}
