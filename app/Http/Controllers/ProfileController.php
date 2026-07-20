<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Country;
use App\Models\RiskScore;
use App\Models\WeatherSnapshot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Stats from existing models
        $countriesManaged = Country::count();
        $riskUpdates = RiskScore::count();
        $weatherUpdates = WeatherSnapshot::count();

        return view('profile', compact('user', 'countriesManaged', 'riskUpdates', 'weatherUpdates'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect.'
            ], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully!'
        ]);
    }
}
