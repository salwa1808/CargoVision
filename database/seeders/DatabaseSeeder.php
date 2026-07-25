<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        if (env('ADMIN_EMAIL') && env('ADMIN_PASSWORD')) {
            User::updateOrCreate(
                ['email' => env('ADMIN_EMAIL')],
                [
                    'name' => env('ADMIN_NAME', 'System Administrator'),
                    'role' => 'admin',
                    'status' => 'Active',
                    'password' => bcrypt(env('ADMIN_PASSWORD')),
                ]
            );
        }

        $this->call(VesselSeeder::class);
        $this->call(ShipmentSeeder::class);
    }
}
