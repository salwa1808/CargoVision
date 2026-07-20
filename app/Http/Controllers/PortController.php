<?php

namespace App\Http\Controllers;

use App\Models\Port;
use App\Models\Country;
use Illuminate\Http\Request;

class PortController extends Controller
{
    public function index(Request $request)
    {
        $query = Port::with('country');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('country')) {
            $query->where('country_id', $request->country);
        }

        $ports = $query
            ->orderBy('name')
            ->paginate(25)
            ->withQueryString();

        $countries = Country::orderBy('name')->get();

        return view('ports', compact('ports', 'countries'));
    }

    public function map()
    {
        return Port::with('country')
            ->select(
                'id',
                'country_id',
                'name',
                'latitude',
                'longitude'
            )
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();
    }
}