<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CountriesService
{
    public function getCountries()
    {
        return Http::timeout(60)
            ->retry(3, 1000)
            ->acceptJson()
            ->get('https://raw.githubusercontent.com/mledoze/countries/master/countries.json')
            ->throw()
            ->json();
    }
}
