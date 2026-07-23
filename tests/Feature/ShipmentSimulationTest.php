<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShipmentSimulationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_monitor_and_track_simulated_shipment(): void
    {
        $admin=User::factory()->create(['role'=>'admin']);
        $a=Country::create(['name'=>'Indonesia','cca2'=>'ID','cca3'=>'IDN','latitude'=>-6,'longitude'=>106]);
        $b=Country::create(['name'=>'Singapore','cca2'=>'SG','cca3'=>'SGP','latitude'=>1,'longitude'=>103]);
        $shipment=Shipment::create(['tracking_number'=>'SIM-TEST-001','origin_country_id'=>$a->id,
            'destination_country_id'=>$b->id,'transport_mode'=>'ship','status'=>'in_transit',
            'progress'=>55,'is_simulated'=>true,'departure_at'=>now()->subDay(),'estimated_arrival'=>now()->addDay()]);

        $this->actingAs($admin)->get(route('shipments.index'))->assertOk()->assertSee('SIM-TEST-001')->assertSee('Simulasi')->assertSee('Detail');
        $this->actingAs($admin)->get(route('shipments.show',$shipment))->assertOk()->assertSee('shipmentMap')->assertSee('Simulated Route')->assertSee('55%');
    }

    public function test_user_can_view_simulation_but_cannot_administer_data(): void
    {
        $user = User::factory()->create(['role'=>'user']);
        $this->actingAs($user)->get(route('shipments.index'))->assertOk();
        $this->actingAs($user)->get(route('admin.ports.index'))->assertForbidden();
    }
}
