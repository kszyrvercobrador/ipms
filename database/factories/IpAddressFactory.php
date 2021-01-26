<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\IpAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

class IpAddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = IpAddress::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'    => User::factory(),
            'label'      => $this->faker->text(50),
            'ip_address' => $this->faker->ipv4,
        ];
    }
}
