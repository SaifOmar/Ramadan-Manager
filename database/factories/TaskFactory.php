<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->name(),
            'type' => $this->randType(),
            'user_id' => User::InRandomOrder()->first()->id,
            'status' => $this->randStatus(),
            'description' => fake()->numberBetween(0, 1) == 1 ?  fake()->sentence() : null,
            'expiry' => fake()->time(),
        ];
    }
    private function randStatus(): string
    {
        $rn = fake()->numberBetween(1, 3);
        $type = match ($rn) {
            1 => 'missed',
            2 => 'done',
            3 => 'waiting',
        };
        return $type;
    }
    private function randType(): string
    {
        $rn = fake()->numberBetween(1, 5);
        $type = match ($rn) {
            1 => 'salah',
            2 => 'quran',
            3 => 'food',
            4 => 'work',
            5 => 'sleep',
        };
        return $type;
    }
}
