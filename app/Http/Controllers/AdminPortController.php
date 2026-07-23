<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Port;
use Illuminate\Http\Request;

class AdminPortController extends Controller
{
    public function index(Request $request)
    {
        $ports = Port::with('country')->when($request->filled('search'), fn($q)=>$q->where('name','like','%'.$request->search.'%'))
            ->latest()->paginate(15)->withQueryString();
        $countries = Country::orderBy('name')->get();
        return view('admin.ports.index', compact('ports','countries'));
    }
    public function store(Request $request) { Port::create($this->data($request)); return back()->with('success','Pelabuhan ditambahkan.'); }
    public function update(Request $request, Port $port) { $port->update($this->data($request)); return back()->with('success','Pelabuhan diperbarui.'); }
    public function destroy(Port $port) { $port->delete(); return back()->with('success','Pelabuhan dihapus.'); }
    private function data(Request $request): array { return $request->validate(['country_id'=>['required','exists:countries,id'],'name'=>['required','string','max:255'],'latitude'=>['nullable','numeric','between:-90,90'],'longitude'=>['nullable','numeric','between:-180,180']]); }
}
