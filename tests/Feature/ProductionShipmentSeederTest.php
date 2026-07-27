<?php

namespace Tests\Feature;

use App\Models\Shipment;
use Database\Seeders\ShipmentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductionShipmentSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_shipment_seeder_is_repeatable_and_keeps_simulation_label(): void
    {
        $this->seed(ShipmentSeeder::class);
        $firstCount = Shipment::count();

        $this->seed(ShipmentSeeder::class);

        $this->assertSame(12, $firstCount);
        $this->assertSame($firstCount, Shipment::count());
        $this->assertSame($firstCount, Shipment::where('is_simulated', true)->count());
    }
}
