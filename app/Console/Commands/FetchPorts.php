<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Models\Port;
use Illuminate\Console\Command;

class FetchPorts extends Command
{
    protected $signature = 'fetch:ports';

    protected $description = 'Import Sea Ports from UN LOCODE';

    public function handle()
    {
        $file = storage_path('app/ports/code-list.csv');

        if (!file_exists($file)) {
            $this->error('Dataset tidak ditemukan.');
            return Command::FAILURE;
        }

        $this->info('Loading countries...');

        $countries = Country::pluck('id', 'cca2')->toArray();

        $this->info('Country loaded : ' . count($countries));

        $existing = Port::query()
            ->get(['country_id', 'name'])
            ->mapWithKeys(fn (Port $port) => [$port->country_id.'|'.$port->name => true])
            ->all();

        $handle = fopen($file, 'r');

        // Lewati baris pertama
        fgetcsv($handle, 0, ',');

        $success = 0;
        $skip = 0;
        $batch = [];
        $now = now();

        while (($row = fgetcsv($handle, 0, ',')) !== false) {

            if (count($row) < 11) {
                continue;
            }

            $countryCode = trim($row[1]);
            $name        = trim($row[3]);
            $function    = trim($row[6]);
            $coordinate  = trim($row[10]);

            if ($coordinate === '') {
                continue;
            }

            // Hanya Sea Port
            if (substr($function, 0, 1) !== '1') {
                continue;
            }

            if (!isset($countries[$countryCode])) {
                $skip++;
                continue;
            }

            // Pastikan string valid UTF-8
           // Perbaiki karakter yang rusak
$name = trim($row[3]);

$name = mb_convert_encoding(
    $name,
    'UTF-8',
    'UTF-8, ISO-8859-1, Windows-1252'
);

            [$lat, $lng] = $this->convertCoordinate($coordinate);

            if ($lat === null || $lng === null) {
                $skip++;
                continue;
            }

            $countryId = $countries[$countryCode];
            $key = $countryId.'|'.$name;

            if (isset($existing[$key])) {
                continue;
            }

            $batch[] = [
                'country_id' => $countryId,
                'name'       => $name,
                'latitude'   => $lat,
                'longitude'  => $lng,
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
            $existing[$key] = true;

            $success++;

            if (count($batch) >= 500) {
                Port::insert($batch);
                $batch = [];
                $this->info("Imported : {$success}");
            }
        }

        if ($batch !== []) {
            Port::insert($batch);
        }

        fclose($handle);

        $this->newLine();
        $this->info('========================');
        $this->info('Import selesai');
        $this->info("Berhasil : {$success}");
        $this->warn("Skip : {$skip}");
        $this->info('========================');

        return Command::SUCCESS;
    }

    private function convertCoordinate($coordinate)
    {
        $coordinate = str_replace(' ', '', $coordinate);

        preg_match('/(\d+)([NS])(\d+)([EW])/', $coordinate, $m);

        if (count($m) < 5) {
            return [null, null];
        }

        $latRaw = $m[1];
        $ns = $m[2];
        $lngRaw = $m[3];
        $ew = $m[4];

        $latDeg = substr($latRaw, 0, 2);
        $latMin = substr($latRaw, 2);

        $lat = $latDeg + ($latMin / 60);

        if ($ns === 'S') {
            $lat *= -1;
        }

        if (strlen($lngRaw) == 5) {
            $lngDeg = substr($lngRaw, 0, 3);
            $lngMin = substr($lngRaw, 3);
        } else {
            $lngDeg = substr($lngRaw, 0, 2);
            $lngMin = substr($lngRaw, 2);
        }

        $lng = $lngDeg + ($lngMin / 60);

        if ($ew === 'W') {
            $lng *= -1;
        }

        return [
            round($lat, 6),
            round($lng, 6),
        ];
    }
}
