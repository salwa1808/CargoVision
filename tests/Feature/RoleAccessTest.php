<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_access_monitoring_but_not_administration(): void
    {
        $user = User::factory()->create(['role'=>'user']);
        $a=Country::create(['name'=>'Indonesia','cca2'=>'ID','cca3'=>'IDN']);
        $b=Country::create(['name'=>'Singapore','cca2'=>'SG','cca3'=>'SGP']);
        $shipment=Shipment::create(['tracking_number'=>'ROLE-001','origin_country_id'=>$a->id,
            'destination_country_id'=>$b->id,'transport_mode'=>'ship','status'=>'pending','progress'=>0]);

        $this->actingAs($user)->get(route('shipments.index'))->assertOk();
        $this->actingAs($user)->get(route('shipments.show',$shipment))->assertOk();
        $this->actingAs($user)->get(route('admin.ports.index'))->assertForbidden();
        $this->actingAs($user)->get(route('users.index'))->assertForbidden();
        $this->actingAs($user)->post(route('admin.risk.recalculate'))->assertForbidden();
        $this->actingAs($user)->post(route('admin.weather.refresh', $a->id))->assertForbidden();
        $this->actingAs($user)->get(route('settings'))->assertOk()
            ->assertSee('Shipment Monitoring')
            ->assertDontSee('Insights')
            ->assertDontSee('Port Dataset');
    }

    public function test_admin_can_manage_port_dataset(): void
    {
        $admin=User::factory()->create(['role'=>'admin']);
        $country=Country::create(['name'=>'Indonesia','cca2'=>'ID','cca3'=>'IDN']);
        $this->actingAs($admin)->post(route('admin.ports.store'), [
            'country_id'=>$country->id,'name'=>'Tanjung Priok','latitude'=>-6.1,'longitude'=>106.8,
        ])->assertRedirect();
        $this->assertDatabaseHas('ports',['name'=>'Tanjung Priok','country_id'=>$country->id]);
    }
}
