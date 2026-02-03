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
use Mews\Captcha\Facades\Captcha;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\SendOtpMail;
use App\Services\SMSService;

class RegistrationController extends Controller
{
    public function sendOtp(Request $request)
    {

        $request->validate([
            'email' => 'required|email' . ($request->type !== 'resend' ? '|unique:users,email' : ''),
            'phone' => 'nullable|digits:10' . ($request->type !== 'resend' ? '|unique:users,phone' : ''),
        ]);

        // 1. Rate Limiting (retained from original, as snippet didn't explicitly remove it but changed validation)
        if ($request->session()->has('last_otp_sent_at')) {
            $last = Carbon::parse($request->session()->get('last_otp_sent_at'));
            if ($last->diffInSeconds(now()) < 30) {
                return response()->json(['status' => 'error', 'message' => 'Please wait 30 seconds before resending OTP.'], 429);
            }
        }

        // 2. Generate OTP
        //$otp = rand(1000, 9999);
        $otp = 1234;
        $email = $request->email;
        $phone = $request->phone;

        // 3. Store OTP in Session
        $request->session()->put('otp_code', (string) $otp);
        $request->session()->put('otp_email', $email);
        $request->session()->put('otp_phone', $phone);
        $request->session()->put('otp_expires_at', now()->addMinutes(10)); // New expiry time
        $request->session()->put('last_otp_sent_at', now()->toDateTimeString()); // Retained for rate limiting

        // 4. Send Email
        $mailError = null;
        try {
            Mail::to($email)->send(new SendOtpMail($otp));
        } catch (\Exception $e) {
            Log::error("Mail Sending Failed: " . $e->getMessage());
            $mailError = "Email could not be sent. ";
        }

        // 5. Send SMS
        $smsError = null;
        if ($phone) {
            try {
                $smsService = new SMSService();
                $smsResult = $smsService->sendSMS($phone, $otp);
                if (!isset($smsResult['success']) || !$smsResult['success']) { // Check for success key and its value
                    Log::error("SMS Sending Failed: " . ($smsResult['message'] ?? 'Unknown SMS error'));
                    $smsError = "SMS gateway error. ";
                }
            } catch (\Exception $e) {
                Log::error("SMS Exception: " . $e->getMessage());
                $smsError = "SMS failed. ";
            }
        }

        // 6. Handle response based on sending status
        if ($mailError && $smsError) {
            return response()->json(['status' => 'error', 'message' => 'Failed to send OTP to both Email and Phone. Please try again later.'], 500);
        }

        $msg = 'OTP sent successfully.';
        if ($mailError) {
            $msg = "OTP sent to Phone only. (Email failed)";
        }
        if ($smsError) {
            $msg = "OTP sent to Email only. (SMS failed)";
        }

        // Reset verification flags
        $request->session()->forget(['is_verified']);

        return response()->json([
            'status' => 'ok',
            'message' => $msg,
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'otp' => 'required|string',
        ]);

        $expected = $request->session()->get('otp_code');

        if (!$expected || $data['otp'] !== $expected) {
            return response()->json(['status' => 'error', 'message' => 'Invalid OTP.'], 422);
        }

        // mark verified
        $request->session()->put('is_verified', true);

        return response()->json([
            'status' => 'ok',
            'message' => 'OTP Verified Successfully.'
        ]);
    }

    public function register(Request $request)
    {
        $payload = $request->validate([
            'name' => 'nullable|string|max:255', // handled as username in form
            'username' => 'required|string|max:100|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|digits:10|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
            'aadhar' => 'nullable|digits:12|unique:users,aadhar', // Aadhar is now optional
            'captcha' => 'required|captcha',
        ]);

        // Check if OTP was verified
        if (!$request->session()->get('is_verified')) {
            return response()->json(['status' => 'error', 'message' => 'Please verify OTP first.'], 422);
        }

        // Integrity check: make sure email/phone matches what was OTP verified
        if (
            $payload['email'] !== $request->session()->get('otp_email') ||
            $payload['phone'] !== $request->session()->get('otp_phone')
        ) {
            return response()->json(['status' => 'error', 'message' => 'Email/Phone changed after verification. Please verify again.'], 422);
        }

        $regId = $this->generateUniqueRegistrationId();

        $user = User::create([
            'name' => $payload['username'], // Using username as name if name not provided
            'username' => $payload['username'],
            'registration_id' => $regId,
            'image' => null,
            'phone' => $payload['phone'],
            'email' => $payload['email'],
            'role' => 'user',
            'status' => 'active',
            'email_verified_at' => now(),
            'phone_verified_at' => now(), // Both verified by single OTP
            'is_email_verified' => true,
            'is_phone_verified' => true,
            'is_aadhar_verified' => false, // No OTP for Aadhar
            'password' => Hash::make($payload['password']),
            'aadhar' => $payload['aadhar'],
        ]);

        // Clear session
        $request->session()->forget([
            'otp_code',
            'otp_email',
            'otp_phone',
            'last_otp_sent_at',
            'is_verified'
        ]);

        return response()->json([
            'status' => 'ok',
            'message' => 'Registration completed',
            'redirect_url' => url('/login'),
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
