<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\ApplicationForm;
use App\Models\frontend\ApplicationForm\TourismApartment;
use App\Models\frontend\ApplicationForm\AdventureApplication;
use App\Models\frontend\ApplicationForm\AgricultureRegistration;
use App\Models\frontend\ApplicationForm\Application;
use App\Models\frontend\ApplicationForm\WomenCenteredTourismRegistration;

class DashboardController extends Controller
{


    public function index()
    {
        $user   = Auth::user();
        $userId = $user->id;
        $totalApplications    = ApplicationForm::count();
        $approvedApplications = ApplicationForm::where('is_active', true)->count();
        $pendingApplications  = ApplicationForm::where('is_active', false)->count();

        $recentActivities = [];

        $models = [
            Application::class,
            AdventureApplication::class,
            AgricultureRegistration::class,
            WomenCenteredTourismRegistration::class,
        ];

        $totalUserApplications = 0;
        $statusCounts = [
            'draft'     => 0,
            'submitted' => 0,
            'approved'  => 0,
            'rejected'  => 0,
            'pending'  => 0,
        ];

        foreach ($models as $modelClass) {
            $counts = $modelClass::where('user_id', $userId)
                ->select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status');
            foreach ($counts as $status => $count) {
                if (array_key_exists($status, $statusCounts)) {
                    $statusCounts[$status] += $count;
                }
            }
            $totalUserApplications += $counts->sum();
        }

        return view('frontend.dashboard', compact(
            'totalApplications',
            'approvedApplications',
            'pendingApplications',
            'recentActivities',
            'totalUserApplications',
            'statusCounts'
        ));
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')
            ->with('status', 'You have been logged out successfully.');
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
