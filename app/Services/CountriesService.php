<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CountriesService
{
    public function getCountries()
    {
        return Http::get('https://countries.dev/countries')->json();
    }
}