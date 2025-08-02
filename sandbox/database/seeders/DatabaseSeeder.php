<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Location;
use App\Models\Route;
use App\Models\User;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->admin()->create();

        Location::factory(5)
            ->inBounds(
                53.38709407048300, 83.68419332161707,
                53.32300750725894, 83.79096669808192
            )
            ->create();

        Route::factory(5)
            ->inBounds(
                53.38709407048300, 83.68419332161707,
                53.32300750725894, 83.79096669808192
            )
            ->create();

        Area::factory(5)
            ->inBounds(
                53.38709407048300, 83.68419332161707,
                53.32300750725894, 83.79096669808192
            )
            ->create();
    }
}
