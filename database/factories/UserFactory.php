<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/** @extends Factory<User> */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'viewer',
            'is_active' => true,
            'locale' => 'en',
        ];
    }

    public function unverified(): static
    {
        return $this->state(['email_verified_at' => null]);
    }

    public function superAdmin(): static
    {
        return $this->state(['role' => User::ROLE_SUPER_ADMIN, 'clinic_id' => null]);
    }

    public function clinicOwner(): static
    {
        return $this->state(['role' => User::ROLE_CLINIC_OWNER]);
    }

    public function dentist(): static
    {
        return $this->state(['role' => User::ROLE_DENTIST]);
    }

    public function treatmentCoordinator(): static
    {
        return $this->state(['role' => User::ROLE_TREATMENT_COORDINATOR]);
    }

    public function salesStaff(): static
    {
        return $this->state(['role' => User::ROLE_SALES_STAFF]);
    }
}
