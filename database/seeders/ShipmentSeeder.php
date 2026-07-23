<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Port;
use App\Models\Shipment;
use App\Models\Vessel;
use Illuminate\Database\Seeder;

class ShipmentSeeder extends Seeder
{
    public function run(): void
    {
        $this->ensureDemoMasters();
        $countries = Country::orderBy('id')->get();
        $ports = Port::whereNotNull('country_id')->orderBy('id')->get()->groupBy('country_id');
        $vessels = Vessel::orderBy('id')->limit(12)->get();
        $now = now();

        foreach ($vessels as $i => $vessel) {
            $origin = $countries[$i % $countries->count()];
            $destination = $countries[($i + 1) % $countries->count()];
            $departure = $now->copy()->addHours(($i - 8) * 18);
            Shipment::updateOrCreate(
                ['tracking_number' => sprintf('SIM-%s-%03d', $now->format('Y'), $i + 1)],
                ['origin_country_id'=>$origin->id, 'destination_country_id'=>$destination->id,
                    'origin_port_id'=>$ports->get($origin->id)?->first()?->id,
                    'destination_port_id'=>$ports->get($destination->id)?->first()?->id,
                    'vessel_id'=>$vessel->id, 'transport_mode'=>'ship', 'status'=>'pending',
                    'distance'=>(float)(1200 + $i * 375), 'progress'=>0, 'is_simulated'=>true,
                    'departure_at'=>$departure, 'estimated_arrival'=>$departure->copy()->addHours(72 + ($i % 4) * 24),
                    'arrived_at'=>null]
            );
        }
        $this->command?->call('shipments:update-progress');
    }

    private function ensureDemoMasters(): void
    {
        $masters = [
            ['Indonesia','ID','IDN','Jakarta',-6.12,106.82,'Tanjung Priok'],
            ['Singapore','SG','SGP','Singapore',1.26,103.84,'Port of Singapore'],
            ['Malaysia','MY','MYS','Kuala Lumpur',3.00,101.39,'Port Klang'],
            ['China','CN','CHN','Beijing',31.23,121.47,'Port of Shanghai'],
            ['Netherlands','NL','NLD','Amsterdam',51.95,4.14,'Port of Rotterdam'],
            ['United States','US','USA','Washington, D.C.',33.74,-118.27,'Port of Los Angeles'],
        ];
        foreach ($masters as [$name,$cca2,$cca3,$capital,$latitude,$longitude,$portName]) {
            $country = Country::firstOrCreate(['cca3'=>$cca3], compact('name','cca2','capital','latitude','longitude'));
            Port::firstOrCreate(['country_id'=>$country->id,'name'=>$portName], compact('latitude','longitude'));
        }
        if (Vessel::count() === 0) {
            foreach (['NUSANTARA EXPRESS','MERLION STAR','MALACCA VOYAGER','PACIFIC HORIZON','EUROPA CARRIER','OCEAN LIBERTY','JAVA TRADER','ASIA NAVIGATOR','GLOBAL MARINER','CARGO VENTURE','STRAIT COMMANDER','BLUE MERIDIAN'] as $i=>$name) {
                Vessel::create(['name'=>$name,'imo'=>(string)(9700000+$i),'type'=>'Container','status'=>'Underway',
                    'latitude'=>$masters[$i%6][4],'longitude'=>$masters[$i%6][5],'speed'=>14+($i%7),
                    'heading'=>($i*31)%360,'destination'=>$masters[($i+1)%6][6]]);
            }
        }
    }
}
