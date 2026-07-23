<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Country;
use App\Models\Port;
use App\Models\Shipment;
use App\Models\Vessel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function index()
    {
        return view('bookings.index', [
            'bookings' => Booking::with(['originCountry', 'destinationCountry', 'vessel', 'shipment'])->latest()->paginate(10),
            'countries' => Country::orderBy('name')->get(),
            'ports' => Port::with('country')->orderBy('name')->get(),
            'vessels' => Vessel::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['booking_number'] = 'BKG-'.now()->format('Ymd').'-'.str_pad((string) (Booking::max('id') + 1), 4, '0', STR_PAD_LEFT);
        $data['created_by'] = $request->user()->id;
        Booking::create($data);

        return back()->with('success', 'Booking tersimpan dan menunggu konfirmasi.');
    }

    public function confirm(Booking $booking, Request $request)
    {
        if ($booking->status !== 'draft') {
            return back()->with('error', 'Hanya booking draft yang dapat dikonfirmasi.');
        }

        DB::transaction(function () use ($booking, $request) {
            $booking->update(['status' => 'confirmed', 'confirmed_at' => now()]);
            $shipment = Shipment::create([
                'booking_id' => $booking->id,
                'tracking_number' => 'SHP-'.str_replace('BKG-', '', $booking->booking_number),
                'origin_country_id' => $booking->origin_country_id,
                'destination_country_id' => $booking->destination_country_id,
                'origin_port_id' => $booking->origin_port_id,
                'destination_port_id' => $booking->destination_port_id,
                'vessel_id' => $booking->vessel_id,
                'transport_mode' => 'ship', 'status' => 'pending', 'progress' => 0,
                'departure_at' => $booking->departure_at,
                'estimated_arrival' => $booking->estimated_arrival,
                'is_simulated' => false,
            ]);
            $shipment->events()->create([
                'status' => 'pending', 'notes' => 'Shipment dibuat otomatis dari booking yang dikonfirmasi.',
                'updated_by' => $request->user()->id, 'occurred_at' => now(),
            ]);
        });

        return redirect()->route('shipments.index')->with('success', 'Booking dikonfirmasi dan shipment otomatis tercatat.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'], 'customer_email' => ['nullable', 'email'],
            'cargo_description' => ['required', 'string', 'max:255'], 'cargo_weight' => ['nullable', 'numeric', 'min:0'],
            'origin_country_id' => ['required', 'exists:countries,id', 'different:destination_country_id'],
            'destination_country_id' => ['required', 'exists:countries,id'],
            'origin_port_id' => ['nullable', 'exists:ports,id', 'different:destination_port_id'],
            'destination_port_id' => ['nullable', 'exists:ports,id'], 'vessel_id' => ['nullable', 'exists:vessels,id'],
            'departure_at' => ['nullable', 'date'], 'estimated_arrival' => ['nullable', 'date', 'after:departure_at'],
        ]);
        foreach (['origin', 'destination'] as $side) {
            if (($data["{$side}_port_id"] ?? null) && ! Port::whereKey($data["{$side}_port_id"])->where('country_id', $data["{$side}_country_id"])->exists()) {
                throw ValidationException::withMessages(["{$side}_port_id" => 'Pelabuhan harus sesuai dengan negara yang dipilih.']);
            }
        }
        return $data;
    }
}
