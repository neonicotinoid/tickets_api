<?php

namespace Database\Factories;

use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'status' => TicketStatus::Active,
            'message' => $this->faker->text(150),
            'comment' => null,
        ];
    }

    public function resolved()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => TicketStatus::Resolved,
                'comment' => $this->faker->text(300),
            ];
        });
    }
}
