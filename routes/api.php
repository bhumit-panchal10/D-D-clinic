<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClinicApiController;
use App\Http\Controllers\Api\TreatmentApiController;
use App\Http\Controllers\Api\MedicineApiController;
use App\Http\Controllers\Api\DosageApiController;
use App\Http\Controllers\Api\LabApiController;
use App\Http\Controllers\Api\ConsentFormApiController;

use Illuminate\Support\Facades\Artisan;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    return 'Cache is cleared';
});

Route::post('/Cliniclogin', [ClinicApiController::class, 'Cliniclogin']);
Route::post('/Treatment/Add', [TreatmentApiController::class, 'AddTreatment'])->name('Treatmentadd');
Route::post('/Treatment/list', [TreatmentApiController::class, 'Treatmentlist'])->name('Treatmentlist');
Route::post('/Treatment/Update', [TreatmentApiController::class, 'TreatmentUpdate'])->name('TreatmentUpdate');
Route::post('/Treatment/delete', [TreatmentApiController::class, 'Treatmentdelete'])->name('Treatmentdelete');

Route::post('/Medicine/Add', [MedicineApiController::class, 'AddMedicine'])->name('Medicineadd');
Route::post('/Medicine/list', [MedicineApiController::class, 'Medicinelist'])->name('Medicinelist');
Route::post('/Medicine/Update', [MedicineApiController::class, 'MedicineUpdate'])->name('MedicineUpdate');
Route::post('/Medicine/delete', [MedicineApiController::class, 'Medicinedelete'])->name('Medicinedelete');
Route::post('/dosage/list', [MedicineApiController::class, 'dosagelist'])->name('dosagelist');

Route::post('/Dosage/Add', [DosageApiController::class, 'AddDosage'])->name('Dosageadd');
Route::post('/Dosage/Update', [DosageApiController::class, 'DosageUpdate'])->name('DosageUpdate');
Route::post('/Dosage/delete', [DosageApiController::class, 'Dosagedelete'])->name('Dosagedelete');

Route::post('/Lab/Add', [LabApiController::class, 'AddLab'])->name('AddLab');
Route::post('/Lab/list', [LabApiController::class, 'Lablist'])->name('Lablist');
Route::post('/Lab/Update', [LabApiController::class, 'LabUpdate'])->name('LabUpdate');
Route::post('/Lab/delete', [LabApiController::class, 'Labdelete'])->name('Labdelete');

Route::post('/ConsetForm/Add', [ConsentFormApiController::class, 'AddConsentForm'])->name('AddConsentForm');
Route::post('/ConsetForm/list', [ConsentFormApiController::class, 'ConsentFormlist'])->name('ConsentFormlist');
Route::post('/ConsetForm/Update', [ConsentFormApiController::class, 'ConsentFormUpdate'])->name('ConsentFormUpdate');
Route::post('/ConsetForm/delete', [ConsentFormApiController::class, 'ConsentFormdelete'])->name('ConsentFormdelete');
