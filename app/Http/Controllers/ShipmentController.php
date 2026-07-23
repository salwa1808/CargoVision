<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ShipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Shipment::query()->with(['originCountry', 'destinationCountry', 'originPort', 'destinationPort', 'vessel', 'booking']);

        $query->when($request->filled('search'), function ($query) use ($request) {
            $search = $request->string('search')->trim();
            $query->where(function ($query) use ($search) {
                $query->where('tracking_number', 'like', "%{$search}%")
                    ->orWhereHas('originCountry', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('destinationCountry', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('vessel', fn ($q) => $q->where('name', 'like', "%{$search}%"));
            });
        });

        foreach (['status', 'transport_mode'] as $filter) {
            $query->when($request->filled($filter) && $request->input($filter) !== 'all',
                fn ($query) => $query->where($filter, $request->input($filter))
            );
        }

        $shipments = $query->latest()->paginate(10)->withQueryString();
        $statistics = [
            'total' => Shipment::count(),
            'active' => Shipment::whereIn('status', ['loading', 'departed', 'in_transit'])->count(),
            'delivered' => Shipment::where('status', 'delivered')->count(),
            'delayed' => Shipment::whereNotIn('status', ['arrived', 'delivered', 'cancelled'])
                ->where('estimated_arrival', '<', now())->count(),
        ];

        return view('shipments.index', compact('shipments', 'statistics'));
    }

    public function show(Shipment $shipment)
    {
        $shipment->load(['originCountry', 'destinationCountry', 'originPort', 'destinationPort', 'vessel', 'booking', 'events.updater']);

        return view('shipments.show', compact('shipment'));
    }

    public function updateStatus(Request $request, Shipment $shipment)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(self::nextStatuses($shipment->status))],
            'notes' => ['required', 'string', 'max:1000'],
            'occurred_at' => ['required', 'date', 'before_or_equal:now'],
        ]);
        $progress = ['pending'=>0, 'loading'=>10, 'departed'=>20, 'in_transit'=>60, 'arrived'=>90, 'delivered'=>100, 'cancelled'=>$shipment->progress][$data['status']];
        $shipment->update(['status' => $data['status'], 'progress' => $progress,
            'arrived_at' => $data['status'] === 'arrived' ? $data['occurred_at'] : $shipment->arrived_at]);
        $shipment->events()->create($data + ['updated_by' => $request->user()->id]);

        return back()->with('success', 'Status operasional shipment berhasil diperbarui.');
    }

    private static function nextStatuses(string $status): array
    {
        return match ($status) {
            'pending' => ['loading', 'cancelled'], 'loading' => ['departed', 'cancelled'],
            'departed' => ['in_transit', 'cancelled'], 'in_transit' => ['arrived', 'cancelled'],
            'arrived' => ['delivered'], default => [],
        };
    }
}
