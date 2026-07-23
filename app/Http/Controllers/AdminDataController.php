<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class AdminDataController extends Controller
{
    public function sync()
    {
        Artisan::call('fetch:all');
        return back()->with('success', 'Sinkronisasi seluruh sumber data telah dijalankan.');
    }

    public function recalculateRisk()
    {
        Artisan::call('calculate:risk');
        return back()->with('success', 'Risk score seluruh negara telah dihitung ulang.');
    }
}
