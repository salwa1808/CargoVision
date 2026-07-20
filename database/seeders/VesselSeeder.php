<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vessel;

class VesselSeeder extends Seeder
{
    public function run(): void
    {
        Vessel::truncate();
        $vessels = [
            // Container ships
            [
                'name' => 'EVER GIVEN',
                'imo' => '9811000',
                'type' => 'Container',
                'status' => 'Underway',
                'latitude' => 12.5,
                'longitude' => 45.8, // Gulf of Aden / Red Sea route
                'speed' => 18.5,
                'heading' => 315, // Heading North-West towards Suez
                'destination' => 'Rotterdam',
                'port_id' => 8091
            ],
            [
                'name' => 'MSC GÜLSÜN',
                'imo' => '9839349',
                'type' => 'Container',
                'status' => 'Underway',
                'latitude' => 1.35,
                'longitude' => 102.50, // Malacca Strait
                'speed' => 19.2,
                'heading' => 295, // Heading West
                'destination' => 'Suez Canal',
                'port_id' => null
            ],
            [
                'name' => 'MAERSK MC-KINNEY MOLLER',
                'imo' => '9632064',
                'type' => 'Container',
                'status' => 'Moored',
                'latitude' => 1.283333,
                'longitude' => 103.850000, // Singapore Port
                'speed' => 0.0,
                'heading' => 90,
                'destination' => 'Singapore',
                'port_id' => 9743
            ],
            [
                'name' => 'COSCO SHIPPING UNIVERSE',
                'imo' => '9795610',
                'type' => 'Container',
                'status' => 'Underway',
                'latitude' => 34.2,
                'longitude' => -125.5, // Pacific Ocean heading to LA
                'speed' => 20.1,
                'heading' => 110,
                'destination' => 'Los Angeles',
                'port_id' => 10661
            ],
            [
                'name' => 'CMA CGM ANTOINE DE SAINT EXUPERY',
                'imo' => '9776413',
                'type' => 'Container',
                'status' => 'Underway',
                'latitude' => 30.5,
                'longitude' => 123.5, // East China Sea near Shanghai
                'speed' => 17.8,
                'heading' => 270, // Heading towards Shanghai
                'destination' => 'Shanghai',
                'port_id' => 2067
            ],
            
            // Tankers
            [
                'name' => 'TI EUROPE',
                'imo' => '9224764',
                'type' => 'Tanker',
                'status' => 'Anchored',
                'latitude' => 1.25,
                'longitude' => 104.15, // Singapore Outer Port Limit
                'speed' => 0.1,
                'heading' => 180,
                'destination' => 'Singapore OPL',
                'port_id' => 9743
            ],
            [
                'name' => 'BATILLUS',
                'imo' => '7360095',
                'type' => 'Tanker',
                'status' => 'Underway',
                'latitude' => 25.10,
                'longitude' => 57.15, // Strait of Hormuz / Gulf of Oman
                'speed' => 14.5,
                'heading' => 135, // Heading out of the Persian Gulf
                'destination' => 'Yokohama',
                'port_id' => null
            ],
            [
                'name' => 'KNOCK NEVIS',
                'imo' => '7381154',
                'type' => 'Tanker',
                'status' => 'Underway',
                'latitude' => 36.12,
                'longitude' => -7.20, // Heading into Gibraltar Strait
                'speed' => 12.8,
                'heading' => 95,
                'destination' => 'Genoa',
                'port_id' => null
            ],
            [
                'name' => 'SIRIUS STAR',
                'imo' => '9384198',
                'type' => 'Tanker',
                'status' => 'Underway',
                'latitude' => -34.50,
                'longitude' => 18.00, // Cape of Good Hope
                'speed' => 15.2,
                'heading' => 270, // Westward around Cape
                'destination' => 'Houston',
                'port_id' => null
            ],
            [
                'name' => 'FRONT HAKATA',
                'imo' => '9236717',
                'type' => 'Tanker',
                'status' => 'Moored',
                'latitude' => 31.22,
                'longitude' => 121.49, // Shanghai Port Area
                'speed' => 0.0,
                'heading' => 45,
                'destination' => 'Shanghai',
                'port_id' => 2067
            ],

            // Bulk Carriers
            [
                'name' => 'VALEMAX AMAPA',
                'imo' => '9575448',
                'type' => 'Bulk Carrier',
                'status' => 'Underway',
                'latitude' => -20.50,
                'longitude' => -38.20, // Off coast of Brazil (carrying iron ore)
                'speed' => 13.1,
                'heading' => 45, // Heading Northeast to China
                'destination' => 'Qingdao',
                'port_id' => null
            ],
            [
                'name' => 'PACIFIC SPIRIT',
                'imo' => '9783980',
                'type' => 'Bulk Carrier',
                'status' => 'Underway',
                'latitude' => -10.50,
                'longitude' => 115.00, // Indian Ocean off Australia
                'speed' => 12.4,
                'heading' => 350,
                'destination' => 'Singapore',
                'port_id' => 9743
            ],
            [
                'name' => 'STEEL SUPREME',
                'imo' => '9604433',
                'type' => 'Bulk Carrier',
                'status' => 'Moored',
                'latitude' => 51.93,
                'longitude' => 4.45, // Rotterdam Port Area
                'speed' => 0.0,
                'heading' => 270,
                'destination' => 'Rotterdam',
                'port_id' => 8091
            ],
            
            // Cargo
            [
                'name' => 'ATLANTIC CONVEYOR',
                'imo' => '8215352',
                'type' => 'Cargo',
                'status' => 'Underway',
                'latitude' => 48.50,
                'longitude' => -35.20, // Mid-Atlantic Route
                'speed' => 16.5,
                'heading' => 80, // Heading East to Europe
                'destination' => 'Liverpool',
                'port_id' => null
            ],
            [
                'name' => 'BBC RUSSIA',
                'imo' => '9787122',
                'type' => 'Cargo',
                'status' => 'Underway',
                'latitude' => 50.45,
                'longitude' => -1.15, // English Channel
                'speed' => 14.0,
                'heading' => 60, // Heading East
                'destination' => 'Hamburg',
                'port_id' => null
            ],
            [
                'name' => 'JUMBO JAVELIN',
                'imo' => '9263899',
                'type' => 'Cargo',
                'status' => 'Underway',
                'latitude' => 9.20,
                'longitude' => -79.90, // Panama Canal Area
                'speed' => 8.5,
                'heading' => 180, // Southbound transit
                'destination' => 'Balboa',
                'port_id' => null
            ],

            // Passenger
            [
                'name' => 'QUEEN MARY 2',
                'imo' => '9241061',
                'type' => 'Passenger',
                'status' => 'Underway',
                'latitude' => 40.50,
                'longitude' => -73.80, // Approaching New York
                'speed' => 22.0,
                'heading' => 290,
                'destination' => 'New York',
                'port_id' => null
            ],
            [
                'name' => 'SYMPHONY OF THE SEAS',
                'imo' => '9744037',
                'type' => 'Passenger',
                'status' => 'Underway',
                'latitude' => 25.80,
                'longitude' => -79.90, // Florida Straits
                'speed' => 19.5,
                'heading' => 180, // Southbound
                'destination' => 'Nassau',
                'port_id' => null
            ],
            [
                'name' => 'WONDER OF THE SEAS',
                'imo' => '9838345',
                'type' => 'Passenger',
                'status' => 'Moored',
                'latitude' => 26.12,
                'longitude' => -80.11, // Port Everglades / Fort Lauderdale
                'speed' => 0.0,
                'heading' => 0,
                'destination' => 'Fort Lauderdale',
                'port_id' => null
            ]
        ];

        // Let's generate an additional 30 random vessels to have a rich map
        $names = [
            'OCEAN PROSPERITY', 'SEA CHALLENGER', 'MARITIME LEADER', 'PACIFIC MERMAID', 
            'GLOBAL VOYAGER', 'NORDIC EXPRESS', 'CAPE CONCORD', 'BLUE HORIZON',
            'ORIENT TREASURE', 'SUEZ VANGUARD', 'ALASKA RANGER', 'GIBRALTAR PRIDE',
            'SOUTHERN STAR', 'NEPTUNE COMMANDER', 'AURORA BOREALIS', 'PEGASUS SEAWAYS',
            'ATLANTIC TRADER', 'MAJESTIC WAVE', 'GOLDEN HORIZON', 'CENTURY VOYAGER',
            'ISLAND BREEZE', 'BALTIC SWIFT', 'PACIFIC CONQUEROR', 'ECO NAVIGATOR',
            'SOLAR VALOUR', 'CARIBBEAN DANCER', 'NORTHERN EXPLORER', 'DEEP WATER 1',
            'EMERALD TRANSPORTER', 'ZEPHYR ODYSSEY'
        ];

        $types = ['Container', 'Tanker', 'Bulk Carrier', 'Cargo', 'Passenger'];
        $statuses = ['Underway', 'Anchored', 'Moored'];

        // Major global hotspots coordinates to distribute random vessels
        $hotspots = [
            // Strait of Malacca
            ['lat_min' => 1.0, 'lat_max' => 6.0, 'lng_min' => 95.0, 'lng_max' => 103.0],
            // Suez Canal / Red Sea
            ['lat_min' => 15.0, 'lat_max' => 28.0, 'lng_min' => 35.0, 'lng_max' => 43.0],
            // English Channel / North Sea
            ['lat_min' => 49.0, 'lat_max' => 54.0, 'lng_min' => -5.0, 'lng_max' => 5.0],
            // Panama Canal / Caribbean
            ['lat_min' => 9.0, 'lat_max' => 15.0, 'lng_min' => -83.0, 'lng_max' => -75.0],
            // East China Sea
            ['lat_min' => 25.0, 'lat_max' => 35.0, 'lng_min' => 120.0, 'lng_max' => 127.0],
            // US West Coast
            ['lat_min' => 32.0, 'lat_max' => 38.0, 'lng_min' => -126.0, 'lng_max' => -119.0],
            // Gibraltar / Mediterranean
            ['lat_min' => 34.0, 'lat_max' => 38.0, 'lng_min' => -10.0, 'lng_max' => 0.0]
        ];

        foreach ($vessels as $vData) {
            Vessel::create($vData);
        }

        for ($i = 0; $i < 30; $i++) {
            $name = $names[$i % count($names)];
            $type = $types[$i % count($types)];
            $status = $statuses[rand(0, 2)];
            $hotspot = $hotspots[rand(0, count($hotspots) - 1)];

            $lat = rand($hotspot['lat_min'] * 100000, $hotspot['lat_max'] * 100000) / 100000;
            $lng = rand($hotspot['lng_min'] * 100000, $hotspot['lng_max'] * 100000) / 100000;

            Vessel::create([
                'name' => $name,
                'imo' => '9' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT),
                'type' => $type,
                'status' => $status,
                'latitude' => $lat,
                'longitude' => $lng,
                'speed' => $status === 'Underway' ? rand(100, 220) / 10 : ($status === 'Anchored' ? rand(1, 10) / 10 : 0.0),
                'heading' => rand(0, 359),
                'destination' => $status === 'Underway' ? 'Transit' : 'Anchorage',
                'port_id' => null
            ]);
        }
    }
}
