<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\frontend\Api\TrackController;

use App\Http\Controllers\Admin\Master\EnterpriseController;
use App\Http\Controllers\Admin\Master\TermsAndConditionController;
use App\Http\Controllers\Admin\Master\UndertakingController;
use App\Http\Controllers\Admin\Master\DivisionController;
use App\Http\Controllers\Admin\Master\Accomodation\AdditionalFeatureController;
use App\Http\Controllers\Admin\Master\Accomodation\GeneralRequirementController;
use App\Http\Controllers\Admin\Master\Accomodation\GuestServiceController;
use App\Http\Controllers\Admin\Master\Accomodation\SafetyAndSecurityController;

use App\Http\Controllers\Admin\Master\classificationZone\Area\AreaController;
use App\Http\Controllers\Admin\Master\classificationZone\Zone\ZoneController;
use App\Http\Controllers\Admin\Master\projectCategory\ProjectTypeController;
use App\Http\Controllers\Admin\Master\projectCategory\ProjectCategoryController;
use App\Http\Controllers\Admin\Master\ownershipBusiness\OwnershipOfBusinessController;


use App\Http\Controllers\Admin\Master\Caravan\CaravanTypeController;
use App\Http\Controllers\Admin\Master\Caravan\CaravanAmenityController;
use App\Http\Controllers\Admin\Master\Caravan\CaravanOptionalFeatureController;

use App\Http\Controllers\Admin\Master\CategoryController;

use App\Http\Controllers\Admin\Master\CountryController;
use App\Http\Controllers\Admin\Master\StateController;
use App\Http\Controllers\Admin\Master\DistrictController;
use App\Http\Controllers\Admin\Master\TourismfacilityController;
use App\Http\Controllers\frontend\Api\ApplicationFormApi\TourismRegistrationController;
use App\Http\Controllers\frontend\Api\ApplicationFormApi\ProvisionalRegistrationApiController;
use App\Http\Controllers\frontend\Api\ApplicationFormApi\StampDutyRegistrationApiController;
use App\Http\Controllers\frontend\Api\ApplicationFormApi\IndustrialRegistrationApiController;


use App\Http\Controllers\frontend\ApplicationForm\WomenCenteredTourismRegistrationController;
use App\Http\Controllers\frontend\ApplicationForm\AgricultureRegistrationController;
use App\Http\Controllers\frontend\ApplicationForm\EligibilityRegistrationController;
use App\Http\Controllers\frontend\CaravanRegistration\CaravanRegistrationController;

use App\Http\Controllers\Api\TestController;
Route::get('/test-new', [TestController::class, 'index']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/application-forms', function (Request $request) {
    return "test";
});

Route::prefix('maitri')->group(function () {
    Route::get('/countries', [CountryController::class, 'index']);
    Route::get('/states', [StateController::class,'state']);
    Route::get('/districts', [DistrictController::class,'district']);
    Route::get('/categories', [CategoryController::class,'category']);
    Route::get('/divisions', [DivisionController::class,'division']);
    Route::get('get_Region_District/{id}', [DivisionController::class, 'get_Region_District'])->name('get_Region_District');
    Route::get('/enterprises-type', [EnterpriseController::class,'enterprise']);
    Route::get('/tourism-facility', [TourismfacilityController::class,'Tourismfacility']);

    //master
    Route::prefix('industrial')->group(function () {
    Route::get('/safety-and-security', [IndustrialRegistrationApiController::class, 'SafetyAndSecurity']);
    Route::get('/additional-feature', [IndustrialRegistrationApiController::class, 'AdditionalFeature']);
    Route::get('/general-requirement', [IndustrialRegistrationApiController::class, 'GeneralRequirement']);
    Route::get('/guest-service', [IndustrialRegistrationApiController::class, 'GuestService']);
    });

    // CaravanTypeController

    Route::get('/CaravanType', [CaravanTypeController::class,'CaravanType']);
    Route::get('caravan/amenities', [CaravanAmenityController::class,'Amenities']);
    Route::get('caravan/OptionalFeature', [CaravanOptionalFeatureController::class,'OptionalFeature']);


    // Submit four types of application forms
    // Handle submission for all tourism application forms (villa, apartment, homestay, hostel)registration-of-tourism-villas
  
     Route::get('application-forms', [TourismRegistrationController::class,'index']);
     Route::get('tourism-villas-application-forms', [TourismRegistrationController::class,'TourismVillasForm']);
     Route::get('tourism-apartments-application-forms', [TourismRegistrationController::class,'TourismApartmentsForm']);
     Route::get('homestays-application-forms', [TourismRegistrationController::class,'HomestaysForm']);
     Route::get('vacation-homes-application-forms', [TourismRegistrationController::class,'VacationHomesForm']);
    Route::post('apply/{slug}', [TourismRegistrationController::class,'apply']);

    Route::get('women-centered-tourism-policy', [WomenCenteredTourismRegistrationController::class,'WomenCenteredTourismForm']);
    Route::post('women-centered-tourism-policy/store', [WomenCenteredTourismRegistrationController::class,'store']);
    Route::get('women-centered-tourism-policy/store1', [WomenCenteredTourismRegistrationController::class,'apply1']);
    
    Route::get('agricultural-tourism-policy-registration', [AgricultureRegistrationController::class,'AgriculturalTourismForm']);
     Route::post('agricultural-tourism-policy-registration/store', [AgricultureRegistrationController::class,'store']);

     Route::get('caravan-tourism-policy-registration', [CaravanRegistrationController::class,'CaravanTourismForm']);
     Route::post('caravan-tourism-policy-registration/store', [CaravanRegistrationController::class,'store']);

     Route::get('issuance-of-eligibility-certificate', [EligibilityRegistrationController::class,'EligibilityForm']);
     Route::post('issuance-of-eligibility-certificate/store', [EligibilityRegistrationController::class,'store']);

     Route::get('issuance-of-temporary-registration-certificate', [ProvisionalRegistrationApiController::class,'ProvisionalForm']);
     Route::post('issuance-of-temporary-registration-certificate/store', [ProvisionalRegistrationApiController::class,'store']);

     Route::get('issuance-of-no-objection-certificate', [StampDutyRegistrationApiController::class,'StampDutyForm']);
     Route::post('issuance-of-no-objection-certificate/store', [StampDutyRegistrationApiController::class,'store']);
});

Route::post('/track-application-status', [TrackController::class, 'trackStatus']);

// API for Aaple Sarkar RTS Dashboard Integration
Route::get('/rts/tourism-dashboard', [TrackController::class, 'index']);


