<?php

namespace App\Console\Commands;

use App\Models\Shipment;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class UpdateShipmentProgress extends Command
{
    protected $signature = 'shipments:update-progress';
    protected $description = 'Perbarui status, progress, dan posisi shipment simulasi';

    public function handle(): int
    {
        $updated = 0;
        Shipment::where('is_simulated', true)->with(['originPort','destinationPort','originCountry','destinationCountry','vessel'])
            ->where('status','!=','cancelled')->each(function (Shipment $shipment) use (&$updated) {
                if (! $shipment->departure_at || ! $shipment->estimated_arrival) return;
                [$status,$progress,$arrivedAt] = $this->stateFor($shipment, now());
                $shipment->update(['status'=>$status,'progress'=>$progress,'arrived_at'=>$arrivedAt]);
                $this->moveVessel($shipment, $progress); $updated++;
            });
        $this->info("{$updated} shipment simulasi diperbarui.");
        return self::SUCCESS;
    }

    private function stateFor(Shipment $s, Carbon $now): array
    {
        $departure=$s->departure_at; $arrival=$s->estimated_arrival;
        if ($now->lt($departure->copy()->subHours(6))) return ['pending',0,null];
        if ($now->lt($departure)) return ['loading',10,null];
        if ($now->gte($arrival->copy()->addHours(6))) return ['delivered',100,$arrival];
        if ($now->gte($arrival)) return ['arrived',95,$arrival];
        $ratio=$departure->diffInSeconds($now)/max(1,$departure->diffInSeconds($arrival));
        $progress=min(89,max(20,(int)round(20+$ratio*69)));
        return [$progress<=22?'departed':'in_transit',$progress,null];
    }

    private function moveVessel(Shipment $s, int $progress): void
    {
        if (!$s->vessel) return;
        $a=$s->originPort??$s->originCountry; $b=$s->destinationPort??$s->destinationCountry;
        if ($a?->latitude===null || $b?->latitude===null) return;
        $r=min(1,max(0,($progress-10)/85));
        $s->vessel->update(['latitude'=>(float)$a->latitude+((float)$b->latitude-(float)$a->latitude)*$r,
            'longitude'=>(float)$a->longitude+((float)$b->longitude-(float)$a->longitude)*$r,
            'status'=>$progress>=95?'Moored':($progress>=20?'Underway':'Anchored'),
            'speed'=>$progress>=20&&$progress<95?max(12,(float)$s->vessel->speed):0,
            'destination'=>$s->destinationPort?->name??$s->destinationCountry?->name]);
    }
}
