<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
final class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): self
    {
        return $this->state(static fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): self
    {
        return $this->state([
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);
    }
}
