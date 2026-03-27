<?php

namespace App\Http\Controllers\frontend\ApplicationForm\AdventureApplication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\frontend\ApplicationForm\Application;
use App\Models\frontend\ApplicationForm\ApplicantDetail;
use App\Models\frontend\ApplicationForm\PropertyDetail;
use App\Models\frontend\ApplicationForm\Accommodation;
use App\Models\frontend\ApplicationForm\Facility;
use App\Models\frontend\ApplicationForm\PhotosSignature;
use App\Models\frontend\ApplicationForm\ProvisionalRegistration;
use App\Models\frontend\ApplicationForm\Enclosure;
use App\Models\frontend\ApplicationForm\Document;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\ApplicationForm;
use App\Models\District;
use App\Models\Country;
use App\Models\State;
use App\Models\Admin\master\Enterprise;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Admin\master\Category;
use App\Models\Admin\master\Divisions;
use Illuminate\Http\UploadedFile;
use App\Models\frontend\Api\ApplicationMovement;
use App\Models\frontend\ApplicationForm\TourismApartment;
use App\Models\frontend\ApplicationForm\AdventureApplication;
use App\Http\Requests\frontend\ApplicationForm\AdventureApplicationRequest;

class AdventureProvisionalController extends Controller
{ 
    public function index()
{
    $application = new AdventureApplication();
    $id = 1;
    $step = 2;

    $categories = ['Land Activity','Water Activity','Air Activity'];

    return view('frontend.Application.AdventureApplications.provisional.step' . $step, [
        'step'        => $step,
        'id'          => $id,
        'enterprises' => Enterprise::select('id','name')->get(),
        'regions'     => DB::table('divisions')->select('id','name')->get(),
        'districts'   => DB::table('districts')->where('state_id', 14)->get(),
        'states'      => DB::table('states')->where('id', 14)->get(),
        'categories'  => $categories,
        'application' => $application,
    ]);
}
}