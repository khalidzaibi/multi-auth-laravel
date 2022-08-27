<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Contacts;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contacts>
 */
class ContactsFactory extends Factory
{
    protected $model = Contacts::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address
        ];
    }
}