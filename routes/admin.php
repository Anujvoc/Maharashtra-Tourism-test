<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\Forms\ApplicationFormsController;
use App\Http\Controllers\frontend\Auth\RegistrationController;

use App\Http\Controllers\Admin\Master\EnterpriseController;
use App\Http\Controllers\Admin\Master\TermsAndConditionController;
use App\Http\Controllers\Admin\Master\UndertakingController;
use App\Http\Controllers\Admin\Master\DivisionController;
use App\Http\Controllers\Admin\Master\Accomodation\AdditionalFeatureController;
use App\Http\Controllers\Admin\Master\Accomodation\GeneralRequirementController;
use App\Http\Controllers\Admin\Master\Accomodation\GuestServiceController;
use App\Http\Controllers\Admin\Master\Accomodation\SafetyAndSecurityController;

use App\Http\Controllers\Admin\Master\Caravan\CaravanTypeController;
use App\Http\Controllers\Admin\Master\Caravan\CaravanAmenityController;
use App\Http\Controllers\Admin\Master\Caravan\CaravanOptionalFeatureController;

use App\Http\Controllers\Admin\Master\CategoryController;

use App\Http\Controllers\Admin\Master\CountryController;
use App\Http\Controllers\Admin\Master\StateController;
use App\Http\Controllers\Admin\Master\DistrictController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\RolesAndPermissions\RoleController;
use App\Http\Controllers\Backend\RolesAndPermissions\PermissionController;
use App\Http\Controllers\Backend\RolesAndPermissions\AdminUserController;
use App\Http\Controllers\Backend\ApplicationFormController;

// Route::middleware(['auth', 'role:admin'])->group(function () {
//     Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
//         ->name('admin.dashboard');
// });

Route::middleware(['auth','is_role:admin']) // adjust as needed
    ->prefix('admin/master')
    ->as('admin.master.')
    ->group(function () {
        Route::resource('countries', CountryController::class);
        Route::resource('states', StateController::class);
        Route::resource('districts', DistrictController::class);
            //divisions Routes
      Route::resource('divisions',DivisionController::class);
      Route::get('divisions-data', [DivisionController::class, 'data'])
      ->name('divisions.data');

      // Caravan

      Route::resource('amenities', CaravanAmenityController::class);
      Route::get('amenities-data', [CaravanAmenityController::class, 'data'])
      ->name('amenities.data');
      Route::resource('optionalfeatures', CaravanOptionalFeatureController::class);
      Route::get('optionalfeatures-data', [CaravanOptionalFeatureController::class, 'data'])
      ->name('optionalfeatures.data');
      Route::resource('types', CaravanTypeController::class);
      Route::get('types-data', [CaravanTypeController::class, 'data'])
      ->name('types.data');


 //Industrial Accomodation Routes
                     //for general requirements
         Route::resource('generalRequirement',GeneralRequirementController::class);
         Route::get('generalRequirement-data',[GeneralRequirementController::class, 'data'])
         ->name('generalRequirement.data');

                    //for safety and security
        Route::resource('safetyAndSecurity',SafetyAndSecurityController::class);
        Route::get('safetyAndSecurity-data',[SafetyAndSecurityController::class,'data'])
        ->name('safetyAndSecurity.data');

                    //for guest service
        Route::resource('guestService',GuestServiceController::class);
        Route::get('guestService-data',[GuestServiceController::class,'data'])->
        name('guestService.data');

                    //for additional feature
        Route::resource('additionalFeature',AdditionalFeatureController::class);
        Route::get('additionalFeature-data',[AdditionalFeatureController::class, 'data'])
        ->name('additionalFeature.data');
    });

        Route::middleware(['auth', 'is_role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');
        // role & Permissions
        Route::resource('permissions', PermissionController::class)->except(['show']);
        Route::resource('roles', RoleController::class);
        Route::get('/add/roles/permission' ,[RoleController::class, 'AddRolesPermission'])->name('add.roles.permission');
        Route::get('/edit/roles/permission/{id}' ,[RoleController::class, 'editRolesPermission'])->name('edit.roles.permission');
        Route::get('/all/roles/permission' ,[RoleController::class, 'AllRolesPermission'])->name('all.roles.permission');
        Route::post('/role/permission/store' , [RoleController::class, 'RolePermissionStore'])->name('role.permission.store');
        Route::put('/role/permission/update/{id}' , [RoleController::class, 'RolePermissionUpdate'])->name('role.permission.update');
        Route::delete('/role/permission/delete/{id}' , [RoleController::class, 'RolePermissionDelete'])->name('role.permission.delete');
        Route::get('roles/{roleId}/give-permissions', [RoleController::class, 'addPermissionToRole'])->name('roles.give-permissions');
        Route::put('roles/{roleId}/give-permissions', [RoleController::class,'givePermissionToRole']);

        Route::resource('users', AdminUserController::class);
        Route::get('users-data', [AdminUserController::class, 'data'])
        ->name('users.data');
        // Application Forms
        Route::resource('application-forms', ApplicationFormController::class);
        Route::get('application-forms-data', [ApplicationFormController::class, 'data'])
    ->name('application-forms.data');

      //Enterprise Routes
      Route::resource('enterprises',EnterpriseController::class);
      Route::get('enterprises-data', [EnterpriseController::class, 'data'])
      ->name('enterprises.data');

     //Category Routes
     Route::resource('categories',CategoryController::class);
     Route::get('categories-data', [CategoryController::class, 'data'])
     ->name('categories.data');


     //TermsAndCondition Master Routes
     Route::resource('TermsAndCondition',TermsAndConditionController::class);
     Route::get('TermsAndCondition-data', [TermsAndConditionController::class, 'data'])
     ->name('TermsAndCondition.data');
     Route::get('terms-and-condition/{id}/full-description', [TermsAndConditionController::class, 'fullDescription'])
    ->name('TermsAndCondition.fullDescription');

    //Undertaking Master Routes
    Route::resource('undertaking',UndertakingController::class);
    Route::get('undertaking-data', [UndertakingController::class, 'data'])
    ->name('undertaking.data');
    Route::get('undertaking/{id}/full-description', [UndertakingController::class, 'fullDescription'])
    ->name('undertaking.fullDescription');

     //Tourism Facility Routes
     Route::resource('tourism-facilities',App\Http\Controllers\Admin\Master\TourismFacilityController::class);
     Route::get('tourism-facilities-data', [App\Http\Controllers\Admin\Master\TourismFacilityController::class, 'data'])
    ->name('tourism-facilities.data');


   //  ApplicationFormsController controller ApplicationFormsController
    Route::resource('ApplicationForms',ApplicationFormsController::class);
    Route::get('/ApplicationForms/show/{model}/{id}', [ApplicationFormsController::class, 'show'])
    ->name('ApplicationForms.model.show');




    });
