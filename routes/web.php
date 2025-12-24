<?php

use App\Http\Controllers\UploadController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\WizardController;


use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\frontend\Auth\RegistrationController;
use App\Http\Controllers\frontend\DashboardController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\frontend\CaravanRegistration\CaravanRegistrationController;

use App\Http\Controllers\frontend\ApplicationFormController;
use App\Http\Controllers\frontend\ApplicationForm\TouristVillaRegistrationController;
use App\Http\Controllers\frontend\ApplicationForm\TourismApartmentRegistrationController;
use App\Http\Controllers\frontend\ApplicationForm\AdventureApplicationController;
use App\Http\Controllers\frontend\ApplicationForm\AgricultureRegistrationController;
use App\Http\Controllers\frontend\ApplicationForm\WomenCenteredTourismRegistrationController;
use App\Http\Controllers\frontend\ApplicationForm\IndustrialWizardController;
use App\Http\Controllers\frontend\ApplicationForm\ProvisionalRegistrationController;
use App\Http\Controllers\frontend\ApplicationForm\EligibilityRegistrationController;
use App\Http\Controllers\frontend\ApplicationForm\StampDutyWizardController;


Route::get('/', function () {
    return view('frontend.index');
    return view('welcome');
});


Route::get('/tourism-villa', function () {
    return view('frontend.tourist_villa');

});

Route::get('/tourism/registration', function () {
    return view('frontend.registration');

});
Route::get('/test-qrcode', function () {
    $svg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(300)->generate('Hello');

    return response($svg, 200, [
        'Content-Type' => 'image/svg+xml',
        'Content-Disposition' => 'attachment; filename="qr.svg"',
    ]);

    return response(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(300)->generate('Hello World'), 200, [
        'Content-Type' => 'image/png',
        'Content-Disposition' => 'attachment; filename="test.png"',
    ]);
});


Route::prefix('registration')->group(function () {
    Route::post('send-otp', [RegistrationController::class, 'sendOtp'])->name('registration.sendOtp');
    Route::post('verify-otp', [RegistrationController::class, 'verifyOtp'])->name('registration.verifyOtp');
    Route::post('register', [RegistrationController::class, 'register'])->name('registration.register');

    // (Optional) CRUD for admins:
    Route::get('users', [RegistrationController::class, 'index'])->name('registration.index');
    Route::get('users/{user}', [RegistrationController::class, 'show'])->name('registration.show');
    Route::put('users/{user}', [RegistrationController::class, 'update'])->name('registration.update');
    Route::delete('users/{user}', [RegistrationController::class, 'destroy'])->name('registration.destroy');
});

// routes/web.php
Route::middleware(['auth'])->group(function () {

    // routes/web.php

    Route::get('/provisional-registration', function () {
        return view('frontend.create');
    })->name('provisional.registration.create');


    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{type}/{id}/remarks', [ApplicationController::class, 'getRemarks'])->name('applications.remarks');
    Route::get('/applications/reports', [ApplicationController::class, 'reports'])->name('applications.reports');
    Route::get('/applications/{id}/report', [ApplicationController::class, 'generateReport'])->name('applications.report');
    Route::get('/applications/{id}/report/download', [ApplicationController::class, 'generateReport'])->name('applications.report.download');
    // Certificate Download for User
    Route::get('/applications/certificate/{type}/{id}/download', [ApplicationController::class, 'downloadCertificate'])->name('applications.certificate.download');
    Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');

    Route::prefix('apply/{application:slug_id}')->name('wizard.')->group(function () {
        Route::get('/step/{step}', [WizardController::class, 'show'])->name('show');

        Route::post('/step/1', [WizardController::class, 'saveApplicant'])->name('applicant.save');
        Route::post('/step/2', [WizardController::class, 'saveProperty'])->name('property.save');
        Route::post('/step/3', [WizardController::class, 'saveAccommodation'])->name('accommodation.save');
        Route::post('/step/4', [WizardController::class, 'saveFacilities'])->name('facilities.save');
        Route::post('/step/5', [WizardController::class, 'savePhotos'])->name('photos.save');
        Route::post('/step/6', [WizardController::class, 'saveEnclosures'])->name('enclosures.save');

        Route::post('/upload', [UploadController::class, 'store'])->name('upload');
        Route::delete('/upload/{document}', [UploadController::class, 'destroy'])->name('upload.destroy');

        Route::post('/review-submit', [WizardController::class, 'submit'])->name('submit');
    });
});

Route::get('/frontend/applications/{slug}', [ApplicationFormController::class, 'create'])->name('frontend.application.create');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth'])->prefix('frontend')->as('frontend.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    Route::post('/logout', [DashboardController::class, 'logout'])->name('logout');

    Route::resource('application-forms', ApplicationFormController::class);

    Route::get('/applications/store', [ApplicationFormController::class, 'store'])->name('application.store');

    Route::resource('villa-registrations', TouristVillaRegistrationController::class);
    Route::get('/TourismApartments/create', [TourismApartmentRegistrationController::class, 'create'])->name('TourismApartments.create');
    Route::post('/TourismApartments/store', [TourismApartmentRegistrationController::class, 'store'])->name('TourismApartments.store');

    //adventure
    Route::resource('adventure-applications', AdventureApplicationController::class);
    Route::get('get_Region_District/{id}', [AdventureApplicationController::class, 'get_Region_District'])->name('get_Region_District');
    Route::get('/applications/adventure/{id}/report', [AdventureApplicationController::class, 'report'])
        ->name('applications.adventure.report');

    Route::get('/applications/tourism/{id}/report', [TourismApartmentRegistrationController::class, 'report'])
        ->name('applications.tourism.report');

    //WomenCenteredTourismRegistrationController
    Route::resource('women-tourism-registrations', WomenCenteredTourismRegistrationController::class);

    Route::resource('/caravan-registrations', CaravanRegistrationController::class);

});

Route::middleware(['auth'])->group(function () {
    Route::get('/agriculture-registrations/create', [AgricultureRegistrationController::class, 'create'])
        ->name('agriculture-registrations.create');

    Route::post('/agriculture-registrations', [AgricultureRegistrationController::class, 'store'])
        ->name('agriculture-registrations.store');

    Route::get('/agriculture-registrations', [AgricultureRegistrationController::class, 'index'])
        ->name('agriculture-registrations.index');
    Route::get('/applications/Agriculture/tourism/{id}/report', [AgricultureRegistrationController::class, 'report'])
        ->name('applications.Agriculture.tourism.report');


    Route::resource('/caravan-registrations', CaravanRegistrationController::class);
    // Route::post('/caravan-registration/store', [CaravanRegistrationController::class, 'store'])->name('caravan.store');

    //distict
    // Route::get('get_Region_District/{id}', [CaravanRegistrationController::class, 'get_Region_District'])->name('get_Region_District');
    //     Route::get('/region/{id}/districts', [CaravanRegistrationController::class, 'getDistricts']);
});



Route::middleware('auth')->prefix('industrial')->name('industrial.')->group(function () {

    // NEW: start wizard for a given application_form
    Route::post(
        '/wizard/start/{application_form}',
        [IndustrialWizardController::class, 'start']
    )->name('wizard.start');

    Route::get(
        '/wizard/{application}/step/{step}',
        [IndustrialWizardController::class, 'show']
    )->name('wizard.show');

    Route::post(
        '/wizard/{application}/step/1',
        [IndustrialWizardController::class, 'storeStep1']
    )->name('wizard.step1.store');

    Route::post(
        '/wizard/{application}/step/2',
        [IndustrialWizardController::class, 'storeStep2']
    )->name('wizard.step2.store');

    Route::post(
        '/wizard/{application}/step/3',
        [IndustrialWizardController::class, 'storeStep3']
    )->name('wizard.step3.store');

    // agar storeStep4 hai to yahan
    Route::post(
        '/wizard/{application}/step/4',
        [IndustrialWizardController::class, 'storeStep4']
    )->name('wizard.step4.store');

    Route::post(
        '/wizard/{application}/final-submit',
        [IndustrialWizardController::class, 'finalSubmit']
    )->name('wizard.final-submit');
    // new 
    Route::get(
        '/hotel/report/{id}',
        [IndustrialWizardController::class, 'report']
    )->name('registration.report');
});


// routes/web.php
Route::middleware(['auth'])->group(function () {
    // Provisional Registration Wizard
    Route::prefix('provisional/{application}')->group(function () {
        Route::get('/wizard/{step}', [ProvisionalRegistrationController::class, 'show'])
            ->name('provisional.wizard.show');

        Route::post('/wizard/{step}/save', [ProvisionalRegistrationController::class, 'saveStep'])
            ->name('provisional.wizard.save');
    });

    // View submitted application
    Route::get('/applications/{application}/provisional', [ProvisionalRegistrationController::class, 'showApplication'])
        ->name('applications.provisional.show');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/eligibility-registrations/create/{application_form}', [EligibilityRegistrationController::class, 'create'])
        ->name('eligibility-registrations.create');

    Route::post('/eligibility-registrations', [EligibilityRegistrationController::class, 'store'])
        ->name('eligibility-registrations.store');

    Route::get('/eligibility-registrations/{registration}', [EligibilityRegistrationController::class, 'show'])
        ->name('eligibility-registrations.show');
});

Route::middleware(['auth'])->group(function () {

    Route::prefix('stamp-duty')->name('stamp-duty.')->group(function () {

        // Wizard entry (step1 by default)
        Route::get('/wizard/{id}/{step?}/{application?}', [StampDutyWizardController::class, 'create'])
            ->whereNumber('step')
            ->name('wizard');

        // Handle step POST (create + update)
        Route::post('/wizard/{step}', [StampDutyWizardController::class, 'store'])
            ->whereNumber('step')
            ->name('wizard.store');

        // Final review page
        Route::get('/review/{application}', [StampDutyWizardController::class, 'review'])
            ->name('review');

        // Final submit
        Route::post('/submit/{application}', [StampDutyWizardController::class, 'submit'])
            ->name('submit');

        Route::get('/reports/{id}', [StampDutyWizardController::class, 'reports'])
            ->name('reports');
    });
});

require_once base_path('routes/admin.php');
// User Document Re-upload
Route::post('/user/documents/{id}/update', [App\Http\Controllers\frontend\UserDocumentController::class, 'update'])->name('user.documents.update')->middleware('auth');

require __DIR__ . '/auth.php';

Route::get('vendor/dashboard', [AdminController::class, 'dashboard'])->name('vendor.dashboard');

