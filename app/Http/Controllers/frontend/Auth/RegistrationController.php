<?php

namespace App\Http\Controllers\frontend\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class RegistrationController extends Controller
{
    public function sendOtp(Request $request)
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(['email','phone','aadhar'])],
            'value' => 'required|string'
        ]);

        // simple rate limit
        if ($request->session()->has('last_otp_sent_at')) {
            $last = Carbon::parse($request->session()->get('last_otp_sent_at'));
            if ($last->diffInSeconds(now()) < 10) {
                return response()->json(['status' => 'error', 'message' => 'Wait before requesting another OTP'], 429);
            }
        }

        $request->session()->put('otp_type', $data['type']);
        $request->session()->put('otp_value', $data['value']);
        $request->session()->put('otp_sent_at', now()->toDateTimeString());
        $request->session()->put('last_otp_sent_at', now()->toDateTimeString());

        // DEMO ONLY
        $request->session()->put('otp_code', '1234');

        if ($data['type'] === 'phone') {
            $user = User::where('phone', $data['value'])->first();
            if ($user) { $user->last_otp_sent_at = now(); $user->save(); }
        } elseif ($data['type'] === 'email') {
            $user = User::where('email', $data['value'])->first();
            if ($user) { $user->last_otp_sent_at = now(); $user->save(); }
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'OTP sent (demo). Use 1234 to verify in this demo.',
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(['email','phone','aadhar'])],
            'otp' => 'required|string',
            'value' => 'required|string',
        ]);

        $expected = $request->session()->get('otp_code', '1234');

        if ($data['otp'] !== $expected) {
            return response()->json(['status' => 'error', 'message' => 'Invalid OTP.'], 422);
        }

        $key = match ($data['type']) {
            'email' => 'is_email_verified',
            'phone' => 'is_phone_verified',
            'aadhar' => 'is_aadhar_verified',
        };

        $request->session()->put($key, true);
        $request->session()->put($key . '_at', now()->toDateTimeString());

        return response()->json([
            'status' => 'ok',
            'message' => ucfirst($data['type']) . ' verified (demo).'
        ]);
    }

    public function register(Request $request)
    {
        $payload = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:100|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|digits:10|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
            'aadhar' => 'nullable|digits:12|unique:users,aadhar',
            'role' => ['nullable', Rule::in(['admin','vendor','user'])],
        ]);

        $isEmailVerified = (bool) $request->session()->get('is_email_verified', false);
        $isPhoneVerified = (bool) $request->session()->get('is_phone_verified', false);
        $isAadharVerified = (bool) $request->session()->get('is_aadhar_verified', false);

        $regId = $this->generateUniqueRegistrationId();

        $user = User::create([
            'name' => $payload['name'],
            'username' => $payload['username'] ?? null,
            'registration_id' => $regId,
            'image' => null,
            'phone' => $payload['phone'] ?? null,
            'email' => $payload['email'],
            'role' => $payload['role'] ?? 'user',
            'status' => 'active',
            'email_verified_at' => $isEmailVerified ? now() : null,
            'password' => Hash::make($payload['password']),
            'is_email_verified' => $isEmailVerified,
            'is_phone_verified' => $isPhoneVerified,
            'is_aadhar_verified' => $isAadharVerified,
            'phone_verified_at' => $isPhoneVerified ? now() : null,
            'aadhar_verified_at' => $isAadharVerified ? now() : null,
            'aadhar' => $payload['aadhar'] ?? null,
        ]);

        $request->session()->forget([
            'otp_code','otp_type','otp_value','otp_sent_at',
            'is_email_verified','is_phone_verified','is_aadhar_verified',
        ]);

        // Return JSON; frontend redirects to /login
        return response()->json([
            'status' => 'ok',
            'message' => 'Registration completed',
            'redirect_url' => url('/login'),
            'user' => $user
        ], 201);
    }

    protected function generateUniqueRegistrationId($prefix = 'MV')
    {
        do {
            $id = strtoupper($prefix . '-' . Str::upper(Str::random(8)));
        } while (User::where('registration_id', $id)->exists());
        return $id;
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     {{--

    //         if ($ReportFreeze) {
    //             $language = $ReportFreeze->language;
    //             $useFreezeTable = true;
    //         } else {
    //             $useFreezeTable = ReleaseQuotaFreeze::where('month', $request->month)
    //                 ->where('sugar_season', $request->sugar_season)
    //                 ->exists();
    //             if (!$useFreezeTable) {
    //                 $language = $request->language;
    //             }
    //         }

    //         --}}

    // }


    // if ($useFreezeTable) {
    //     $baseTable = 'release_quotas_freezed_data_report';
    //     $query = ReleaseQuotaFreeze::with('plant')
    //         ->select("{$baseTable}.*")
    //         ->where("{$baseTable}.month", $request->month)
    //         ->where("{$baseTable}.sugar_season", $request->sugar_season)
    //         ->leftJoin('sugar_mill_data as plant', "{$baseTable}.plant_code", '=', 'plant.plant_code')
    //         ->leftJoin('states as state', 'plant.state_id', '=', 'state.id')
    //         ->addSelect(
    //             'plant.state_id',
    //             'state.state_name',
    //             'plant.plant_name'
    //         )
    //         ->orderBy('state.state_name', 'asc')
    //         ->orderBy('plant.plant_name', 'asc');
    // } else {
    //     $baseTable = 'release_quotas';
    //     $query = ReleaseQuota::with('plant')
    //         ->select("{$baseTable}.*")
    //         ->where("{$baseTable}.month", $request->month)
    //         ->where("{$baseTable}.sugar_season", $request->sugar_season)
    //         ->leftJoin('sugar_mill_data as plant', "{$baseTable}.plant_code", '=', 'plant.plant_code')
    //         ->leftJoin('states as state', 'plant.state_id', '=', 'state.id')
    //         ->addSelect(
    //             'plant.state_id',
    //             'state.state_name',
    //             'plant.plant_name'
    //         )
    //         ->orderBy('state.state_name', 'asc')
    //         ->orderBy('plant.plant_name', 'asc');
    // }

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
