<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\District;
use App\Models\Country;
class DistrictController extends Controller
{
    public function index()
    {
        $q = trim((string) request('q', ''));

        $districts = District::query()
            ->with(['state.country'])
            ->whereHas('state', function ($q1) {   
                $q1->where('country_id', 1);
            })
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                       ->orWhereHas('state', function ($qs) use ($q) {
                           $qs->where('name', 'like', "%{$q}%")
                              ->orWhere('code', 'like', "%{$q}%"); // keep if your states have a code
                       });
                });
            })
            ->orderBy('name')
            ->get(); // no pagination

        $countryName = Country::find(1)?->name; // optional header usage

        return view('admin.master.districts.index', [
            'districts'    => $districts,
            'q'            => $q,
            'countryName'  => $countryName,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
