<?php

namespace App\Http\Controllers\frontend\ApplicationForm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\frontend\ApplicationForm\TouristVillaRegistration;
use App\Models\frontend\ApplicationForm\TourismApartment;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class TourismApartmentRegistrationController extends Controller
{

    public function index()
    {
        //
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

       $request->validate([
        'name' => 'required|regex:/^[a-zA-Z\s]+$/',
        'mno' => 'required|digits:10',
        'email' => 'required|email',
        'business' => 'required|regex:/^[a-zA-Z\s]+$/',
        'type' => 'required',
        'pan' => 'required|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
        'aadhar' => 'required|digits:12',
        'proof' => 'required|file|mimes:pdf,jpg,jpeg,png',
        'agreement' => 'required|file|mimes:pdf,jpg,jpeg,png',
        'pname' => 'required|regex:/^[a-zA-Z\s]+$/',
        'prop' => 'required',
        'opname' => 'required_if:prop,Yes|regex:/^[a-zA-Z\s]+$/',
        'ops' => 'required',
        'year' => 'required_if:ops,Yes|digits:4|numeric',
        'pradd' => 'required|file|mimes:jpg,jpeg,png',
        'padd' => 'required|min:10',
        'atinfo' => 'required',
        'dbin' => 'required',
        'aroad' => 'required',
        'areq' => 'required',
        'pay' => 'required',
        'co' => 'required',
        'ct' => 'required',
        'cth' => 'required',
        'cf' => 'required',
        'cfi' => 'required',
        'cs' => 'required',
        'cse' => 'required',
        'ce' => 'required',
        'cn' => 'required',
        'cte' => 'required',
        'cel' => 'required',
        'ctw' => 'required',
        'cthr' => 'required',
        'pancard' => 'required|file|mimes:jpg,jpeg,png,pdf',
        'bregcert' => 'required|file|mimes:jpg,jpeg,png,pdf',
        'profown' => 'required|file|mimes:jpg,jpeg,png,pdf',
        'charcert' => 'required|file|mimes:jpg,jpeg,png,pdf',
        'noc' => 'required|file|mimes:jpg,jpeg,png,pdf',
        'permicert' => 'required|file|mimes:jpg,jpeg,png,pdf',
        'graschallan' => 'required|file|mimes:jpg,jpeg,png,pdf',
        'utaking' => 'required|file|mimes:jpg,jpeg,png,pdf',
        'prophoto.*' => 'required|file|mimes:jpg,jpeg,png,pdf',
        'sign' => 'required|file|mimes:jpg,jpeg,png,pdf',
        'aname' => 'required|regex:/^[a-zA-Z\s]+$/|min:3',
        'date' => 'required|date',
        'ftype' => 'required',
        'guestno' => 'required|numeric|min:0',
        'area' => 'required|numeric|min:0',
        'regn' => 'required|numeric|min:0',
        'gc' => 'required|url',
    ], [
        'opname.required_if' => 'Operator name is required when "Yes" is selected.',
        'year.required_if' => 'Year is required when "Yes" is selected for operational status.',
        'prophoto.*.required' => 'Please upload at least 5 property photos.',
    ]);


    try {
        DB::beginTransaction();

       // Initialize new record
        $apartment = new TourismApartment();
        $apartment->name = $request->name;
        $apartment->mno = $request->mno;
        $apartment->application_id     = $request->input('application_id')?? 123;
        $apartment->user_id            = Auth::id();
        $apartment->email = $request->email;
        $apartment->business = $request->business;
        $apartment->type = $request->type;
        $apartment->pan = $request->pan;
        $apartment->pname = $request->pname;
        $apartment->aadhar = $request->aadhar;
        $apartment->padd = $request->padd;
        $apartment->pradd = $request->pradd;
        $apartment->gc = $request->gc;
        $apartment->opname = $request->opname ?? null;
        $apartment->year = $request->year ?? null;
        $apartment->ftype = $request->ftype;
        $apartment->guestno = $request->guestno;
        $apartment->area = $request->area;
        $apartment->regn = $request->regn;


        $apartment->fno = $request->fno ?? null;
        $apartment->farea = $request->farea ?? null;
        $apartment->atinfo = $request->atinfo ?? null;
        $apartment->dbin = $request->dbin ?? null;
        $apartment->aroad = $request->aroad ?? null;
        $apartment->areq = $request->areq ?? null;
        $apartment->pay = $request->pay ?? null;
        $apartment->co = $request->co ?? null;
        $apartment->ct = $request->ct ?? null;
        $apartment->cth = $request->cth ?? null;
        $apartment->cf = $request->cf ?? null;
        $apartment->cfi = $request->cfi ?? null;
        $apartment->cs = $request->cs ?? null;
        $apartment->cse = $request->cse ?? null;
        $apartment->ce = $request->ce ?? null;
        $apartment->cb = $request->cb ?? null;
        $apartment->cn = $request->cn ?? null;
        $apartment->cte = $request->cte ?? null;
        $apartment->cel = $request->cel ?? null;
        $apartment->ctw = $request->ctw ?? null;
        $apartment->cthr = $request->cthr ?? null;
        $apartment->aname = $request->aname ?? null;
        $apartment->date = $request->date ?? null;

        // Store single file fields not handled later
        if ($request->hasFile('pradd')) {
            $apartment->property_address_photo = $request->file('pradd')->store('uploads/tourismapartment/property_address', 'public');
        }
        if ($request->hasFile('bregcert')) {
            $apartment->business_registration_certificate = $request->file('bregcert')->store('uploads/tourismapartment/business_registration', 'public');
        }
        if ($request->hasFile('profown')) {
            $apartment->proprietor_ownership_document = $request->file('profown')->store('uploads/tourismapartment/proprietor_docs', 'public');
        }
        if ($request->hasFile('charcert')) {
            $apartment->character_certificate = $request->file('charcert')->store('uploads/tourismapartment/character_certificate', 'public');
        }
        if ($request->hasFile('noc')) {
            $apartment->noc_document = $request->file('noc')->store('uploads/tourismapartment/noc', 'public');
        }
        if ($request->hasFile('permicert')) {
            $apartment->permit_certificate = $request->file('permicert')->store('uploads/tourismapartment/permit_certificate', 'public');
        }
        if ($request->hasFile('graschallan')) {
            $apartment->challan_document = $request->file('graschallan')->store('uploads/tourismapartment/challans', 'public');
        }
        if ($request->hasFile('utaking')) {
            $apartment->utaking_document = $request->file('utaking')->store('uploads/tourismapartment/utaking', 'public');
        }

        // Other single file uploads (checked before storing)
        if ($request->hasFile('aadharcard')) {
            $apartment->aadhar_card = $request->file('aadharcard')->store('uploads/tourismapartment/aadharcard', 'public');
        }
        if ($request->hasFile('uaadharcert')) {
            $apartment->additional_aadhar_certificate = $request->file('uaadharcert')->store('uploads/tourismapartment/aadhar_additional', 'public');
        }
        if ($request->hasFile('pancard')) {
            $apartment->pan_card = $request->file('pancard')->store('uploads/tourismapartment/pancard', 'public');
        }
        if ($request->hasFile('bpancard')) {
            $apartment->business_pan_card = $request->file('bpancard')->store('uploads/tourismapartment/business_pancard', 'public');
        }
        if ($request->hasFile('proof')) {
            $apartment->ownership_proof = $request->file('proof')->store('uploads/tourismapartment/proof', 'public');
        }
        if ($request->hasFile('agreement')) {
            $apartment->rental_agreement = $request->file('agreement')->store('uploads/tourismapartment/agreement', 'public');
        }
        if ($request->hasFile('sign')) {
            $apartment->sign = $request->file('sign')->store('uploads/tourismapartment/signature', 'public');
        }
        if ($request->hasFile('contract')) {
            $apartment->contract_document = $request->file('contract')->store('uploads/tourismapartment/contracts', 'public');
        }

        // Store property photos
        if ($request->hasFile('prophoto')) {
            $photos = [];
            foreach ($request->file('prophoto') as $photo) {
                $photos[] = $photo->store('uploads/tourismapartment/property_photos', 'public');
            }
            $apartment->property_photos = json_encode($photos);
        }

        $apartment->save();

        DB::commit();
        return back()->with('success', 'Form submitted successfully!');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
    }

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
