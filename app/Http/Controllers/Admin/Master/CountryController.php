<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use Illuminate\Validation\Rule;

class CountryController extends Controller
{


    public function show(string $id)
    {
        //
    }


public function index()
{
    $perPage = (int) request('per_page', 10);
    $q = trim((string) request('q', ''));

    $countries = \App\Models\Country::query()
        ->when($q, function ($query) use ($q) {
            $query->where(function ($qq) use ($q) {
                $qq->where('name', 'like', "%{$q}%")
                   ->orWhere('iso_code', 'like', "%{$q}%");
            });
        })
        ->orderBy('name')
        ->paginate($perPage)
        ->withQueryString();

    return view('admin.master.countries.index', compact('countries', 'q', 'perPage'));
}


    public function create() { return view('admin.master.countries.create'); }

    public function store(Request $request) {
        $validated = $request->validate([
            'name'     => ['required','string','max:100','unique:countries,name'],
            'iso_code' => ['nullable','string','max:3','unique:countries,iso_code'],
            'is_active'=> ['sometimes','boolean'],
        ]);
        Country::create($validated + ['is_active' => $request->boolean('is_active', true)]);
        return redirect()->route('admin.master.countries.index')->with('success','Country created');
    }

    public function edit(Country $country) {
        return view('admin.master.countries.edit', compact('country'));
    }

    public function update(Request $request, Country $country) {
        $validated = $request->validate([
            'name'     => ['required','string','max:100', Rule::unique('countries','name')->ignore($country->id)],
            'iso_code' => ['nullable','string','max:3', Rule::unique('countries','iso_code')->ignore($country->id)],
            'is_active'=> ['sometimes','boolean'],
        ]);
        $country->update($validated + ['is_active' => $request->boolean('is_active', true)]);
        return redirect()->route('admin.master.countries.index')->with('success','Country updated');
    }

    public function destroy(Country $country) {
        $country->delete();
        return back()->with('success','Country deleted');
    }
}
