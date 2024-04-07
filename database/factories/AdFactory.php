<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ad>
 */
class AdFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "title"=> $this->faker->sentence(5),
            "text"=> $this->faker->text(200),
            "phone"=> $this->faker->phoneNumber,
            "user_id"=> 1,
            "domain_id"=>1,
        ];
    }
}
