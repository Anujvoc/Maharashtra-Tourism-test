<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\ApplicationForm;
use App\Models\frontend\ApplicationForm\Application;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\frontend\ApplicationForm\AdventureApplicationRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Master\Enterprise;
use App\Models\Admin\master\Caravan\CaravanAmenity;
use App\Models\Admin\master\Caravan\CaravanOptionalFeature;
use App\Models\Admin\master\Caravan\CaravanType;
use App\Models\Admin\Master\Category;
use App\Models\frontend\ApplicationForm\AgricultureRegistration;
use App\Models\frontend\ApplicationForm\WomenCenteredTourismRegistration;
use App\Models\frontend\ApplicationForm\AdventureApplication;
use App\Models\frontend\ApplicationForm\ProvisionalRegistration;
use App\Models\frontend\ApplicationForm\EligibilityRegistration;
use App\Models\frontend\CaravanRegistration\CaravanRegistration;
use App\Models\frontend\ApplicationForm\StampDutyApplication;

class ApplicationFormController extends Controller
{

    public function index()
    {
        $data['forms'] = ApplicationForm::where('is_active',1)->get();

        return view('frontend.Application.index', $data);
    }

    public function create($slug)
    {
        if (!auth()->check()) {
            return redirect()->back()->with('error', 'Please login first.');
        }
        $application_form = ApplicationForm::where('is_active', 1)
            ->where('slug', $slug)
            ->firstOrFail();
            $application = ApplicationForm::where('is_active',1)->where('slug',$slug)
            ->first();
              $view = 'frontend.Application.'.$application->slug ?? '.default';

        $id = $application_form->id;

        if (in_array($id, [4, 5, 6, 7])) {
            $app = Application::where('user_id', auth()->id())
                ->where('application_form_id', $application->id)
                ->where('is_apply',true)
                ->first();

            if ($app) {
                if ($app->is_apply) {
                    return redirect()->back()->with('error', 'Already Registered this Application');
                }
                $currentStep = (int) $app->current_step;
                $nextStep = $currentStep <= 0 ? 1 : $currentStep;

                $app->update([
                    'is_apply'     => false,
                    'current_step' => $nextStep,
                ]);
            } else {

                $app = Application::create([
                    'user_id'             => auth()->id(),
                    'slug_id'             => (string) Str::ulid(),
                    'status'              => 'draft',
                    'is_apply'            => false,
                    'application_form_id' => $application->id,
                    'current_step'        => 1,
                ]);

                $nextStep = 1;
            }
            return redirect()->route('wizard.show', [$app, 'step' => $nextStep]);
        }

        if($application->slug == "granting-industrial-status-to-hospitality-sector"){
            $userId = auth()->id();
            $alreadyFinal = Application::where('user_id', $userId)
                ->where('application_form_id', $application->id)
                ->where('is_apply',true)
                ->exists();

            if ($alreadyFinal) {
                return redirect()->back()->with('error', 'Already Registered this Application');
            }

            $app = Application::where('user_id', $userId)
                ->where('application_form_id', $application->id)
                ->where('is_apply', false)
                ->first();

            if ($app) {
                $app->update([
                    'status'       => 'draft',
                    'is_apply'     => false,
                    'current_step' => 1,
                ]);
            } else {
                $app = Application::create([
                    'user_id'             => $userId,
                    'application_form_id' => $application->id,
                    'slug_id'             => (string) ('ind-' . Str::ulid()),
                    'status'              => 'draft',
                    'is_apply'            => false,
                    'current_step'        => 1,
                ]);
            }

            return redirect()->route('industrial.wizard.show', [
                'application' => $app->id,
                'step'        => 1,
            ]);

            $app = Application::where('user_id', auth()->id())
            ->where('application_form_id', $application->id)
            ->where('is_apply',true)
            ->first();
            if ($app) {
                if ($app->is_apply) {
                    return redirect()->back()->with('error', 'Already Registered this Application');
                }
            }
            $app = Application::where('user_id', auth()->id())
            ->where('application_form_id', $application->id)
            ->where('is_apply',false)
            ->first();

            if ($app) {
                $app->update([
                    'is_apply'     => false,
                    'current_step' => 1,
                ]);
            }
            $app = Application::create([
                'user_id'             => auth()->id(),
                'slug_id'             => (string) Str::ulid(),
                'status'              => 'draft',
                'is_apply'            => false,
                'application_form_id' => $application->id,
                'current_step'        => 1,
            ]);
            dd($application->slug);
        }

        if (in_array($id, [4, 5, 6, 7])) {
            $app = Application::where('user_id', auth()->id())
                ->where('application_form_id', $application_form->id)
                ->first();

            if ($app) {
                $app->update([
                    'status'        => 'draft',
                    'is_apply'      => false,
                    'current_step'  => 1,
                ]);
            } else {

                $app = Application::create([
                    'user_id'             => auth()->id(),
                    'slug_id'             => (string) Str::ulid(),
                    'status'              => 'draft',
                    'is_apply'            => false,
                    'application_form_id' => $application_form->id,
                    'current_step'        => 1,
                ]);
            }
            return redirect()->route('wizard.show', [$app, 'step' => 1]);
        }

        if($application->slug == "adventure-tourism-policy-registration"){
            $alreadyRegistered = AdventureApplication::where('application_form_id', $id)
                ->where('user_id', Auth::id())
                ->exists();

            if ($alreadyRegistered) {
                return redirect()->back()->with('error', 'Already Registered this Application');
            }
            $application = new AdventureApplication();
            $id =$id;
            $regions = DB::table('divisions')->select('id','name')->get();
            $enterprises = Enterprise::select('id','name')->get();
            $districts = DB::table('districts')->select('id','name')->get();
            $categories = ['Land Activity','Water Activity','Air Activity'];

            return view($view, compact('application','application_form','regions','districts','categories','enterprises','id'));
        }
        //  dd($application->slug);
        if($application->slug == "agricultural-tourism-policy-registration"){
            $alreadyRegistered = AgricultureRegistration::where('application_form_id', $id)
                ->where('user_id', Auth::id())
                ->exists();

            if ($alreadyRegistered) {
                return redirect()->back()->with('error', 'Already Registered this Application');
            }
            $application = new AgricultureRegistration();
            $id =$id;
            $regions = DB::table('divisions')->select('id','name')->get();
            $applicantTypes = Enterprise::select('id','name')->get();
            $districts = DB::table('districts')->select('id','name')->get();
            $categories = ['Land Activity','Water Activity','Air Activity'];
            return view($view, compact('application','application_form','regions','districts','categories','applicantTypes','id'));
        }
        if($application->slug == "women-centered-tourism-policy-registration"){
            $alreadyRegistered = WomenCenteredTourismRegistration::where('application_form_id', $id)
            ->where('user_id', Auth::id())
            ->exists();

            if ($alreadyRegistered) {
                return redirect()->back()->with('error', 'Already Registered this Application');
            }
            $application = new WomenCenteredTourismRegistration();
            $id =$id;
            $user = Auth::user();
            $regions = DB::table('divisions')->select('id','name')->get();
            $applicantTypes = Enterprise::select('id','name')->get();

            $districts = DB::table('districts')->select('id','name')->get();
            $categories = Category::all();
            return view($view, compact('application','application_form','user','regions','districts','categories','applicantTypes','id'));
        }
        if($application->slug == "caravan-tourism-policy-registration"){
            //     $alreadyRegistered = CaravanRegistration::where('application_form_id', $id)
            //     ->where('user_id', Auth::id())
            //     ->exists();

            // if ($alreadyRegistered) {
            //     return redirect()->back()->with('error', 'Already Registered this Application');
            // }
            $application = new CaravanRegistration();
            $id =$id;
            $user = Auth::user();
            $regions = DB::table('divisions')->select('id','name')->get();
            $applicantTypes = Enterprise::select('id','name')->get();
            $districts = DB::table('districts')->select('id','name')->get();

            $categories = Category::all();
            $enterprises = Enterprise::select('id', 'name')->get();
            $caravanTypes = CaravanType::all();

            $amenities = CaravanAmenity::all();
            $optionalFeatures = CaravanOptionalFeature::all();
            return view($view, compact('application','application_form','categories','enterprises','caravanTypes','amenities','optionalFeatures','user','regions','districts','categories','applicantTypes','id'));
        }
//    dd($application->slug);NoObjection
        if($application->slug == "issuance-of-eligibility-certificate"){
            $alreadyRegistered = EligibilityRegistration::where('application_form_id', $id)
            ->where('user_id', Auth::id())
            ->exists();

            if ($alreadyRegistered) {
                return redirect()->back()->with('error', 'Already Registered this Application');
            }
            $application = new EligibilityRegistration();
            $id =$id;
            $user = Auth::user();
            $regions = DB::table('divisions')->select('id','name')->get();
            $applicantTypes = Enterprise::select('id','name')->get();

            $districts = DB::table('districts')->select('id','name')->get();
            $categories = Category::all();
            return view($view, compact('application','application_form','user','regions','districts','categories','applicantTypes','id'));
        }
        if ($application_form->slug == "issuance-of-temporary-registration-certificate") {

            $userId = Auth::id();

            $alreadyFinal = ProvisionalRegistration::where('user_id', $userId)
                ->where('application_form_id', $application_form->id)
                ->where('is_apply', true)
                ->exists();

            if ($alreadyFinal) {
                return redirect()->back()->with('error', 'Already Registered this Application');
            }

            $userApplication = Application::where('user_id', $userId)
                ->where('application_form_id', $application_form->id)
                ->first();

            if ($userApplication) {

                if ($userApplication->is_apply) {
                    return redirect()->back()->with('error', 'Already Registered this Application');
                }

                $userApplication->update([
                    'status'       => 'draft',
                    'is_apply'     => false,
                    'current_step' => $userApplication->current_step ?? 1,
                ]);

            } else {
                $userApplication = Application::create([
                    'user_id'             => $userId,
                    'application_form_id' => $application_form->id,
                    'slug_id'             => 'ind-' . Str::ulid(),
                    'status'              => 'draft',
                    'is_apply'            => false,
                    'registration_id' => 'PVR-' . str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT),
                    'current_step'        => 1,
                    'submitted_at' => now(),
                ]);
            }

            $registration = ProvisionalRegistration::firstOrCreate(
                [
                    'application_id' => $userApplication->id,
                    'application_form_id' => $userApplication->application_form_id,
                    'user_id'        => $userId,
                    'registration_id' => 'PVR-' . str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT),
                    'slug_id' => 'PVR-' . str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT),
                    'submitted_at' => now(),
                ],
                [
                    'current_step' => 1,
                    'progress'     => ['done' => 0, 'total' => 6],
                ]
            );

            $step = $registration->current_step ?? 1;

            // Optional: sync Application ke current_step ko bhi
            $userApplication->current_step = $step;
            // $userApplication->progress     = $registration->progress;
            $userApplication->save();

            // 4. View ke liye data
            $user         = Auth::user();
            $regions      = DB::table('divisions')->select('id', 'name')->get();
            $districts    = DB::table('districts')->select('id', 'name')->get();
            $categories   = Category::all();
            $applicantTypes = Enterprise::select('id','name')->get();

            // IMPORTANT: variables ko clear naam se bhejo
            return view('frontend.Application.provisional.step1', [
                'application_form' => $application_form,
                'application'      => $userApplication,
                'registration'     => $registration,
                'user'             => $user,
                'regions'          => $regions,
                'districts'        => $districts,
                'categories'       => $categories,
                'applicantTypes'   => $applicantTypes,
                'id'               => $application_form->id,
                'step'             => $step,
            ]);
        }

    //    dd($application->slug);
    if ($application->slug == "issuance-of-no-objection-certificate") {
        $alreadyRegistered = StampDutyApplication::where('application_form_id', $id)
        ->where('is_completed',1)
        ->where('user_id', Auth::id())
        ->exists();

        if ($alreadyRegistered) {
            return redirect()->back()->with('error', 'Already Registered this Application');
        }

        $application = StampDutyApplication::where('application_form_id', $id)
        ->where('user_id', Auth::id())
        ->first();
        if($application){
           return redirect()->route('stamp-duty.wizard', ['id' => $application_form->id, 'step' => $application->current_step, 'application' => $application->id ?? null]);
        }
        return redirect()->route('stamp-duty.wizard', [
            'id'   => $application_form->id,
            'step' => 1,
        ]);
    }

       if($application->slug == "issuance-of-no-objection-certificate"){

        // $id =$id; {{ route('stamp-duty.wizard', ['id' => $application_form->id, 'step' => 5, 'application' => $application->id ?? null]
        // return redirect()->route('wizard', [ 'id' => $id]);
        // $alreadyRegistered = EligibilityRegistration::where('application_form_id', $id)
        // ->where('user_id', Auth::id())
        // ->exists();

        // if ($alreadyRegistered) {
        //     return redirect()->back()->with('error', 'Already Registered this Application');
        // }
        // $application = new EligibilityRegistration();

        $user = Auth::user();
        $regions = DB::table('divisions')->select('id','name')->get();
        $enterprises = Enterprise::select('id','name')->get();

        $districts = DB::table('districts')->select('id','name')->get();
        $states = DB::table('states')->select('id','name')->get();
        $categories = Category::all();
        return view($view, compact('application','application_form','user','regions','districts','categories','enterprises','id'));
    }

        return view($view, compact('application'));

        return view('frontend.Application.default', compact('application_form'));
    }

}
