<?php

namespace App\Http\Controllers;

use App\Models\NewsCache;
use App\Models\Country;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsCache::with('country');

        if ($request->filled('country')) {
            $query->where('country_id', $request->country);
        }

        if ($request->filled('sentiment')) {
            $query->where('sentiment', $request->sentiment);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $news = $query->latest()->paginate(20);

        $countries = Country::orderBy('name')->get();

        return view('news', compact('news', 'countries'));
    }
}