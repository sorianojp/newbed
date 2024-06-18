<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeePersonalDataController;
use App\Http\Controllers\EmploymentRecordController;
use App\Http\Controllers\EducationalAttainmentController;
use App\Http\Controllers\CivilServiceController;
use App\Http\Controllers\SeminarTrainingController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\TenureshipController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group(['middleware' => ['auth']], function() {
    Route::resource('employees', EmployeeController::class);
    Route::post('employees/{employee}/storePersonalData', [EmployeePersonalDataController::class, 'storePersonalData'])->name('employees.storePersonalData');
    Route::post('employees/{employee}/storeEmploymentRecord', [EmploymentRecordController::class, 'storeEmploymentRecord'])->name('employees.storeEmploymentRecord');
    Route::post('employees/{employee}/storeEducationalAttainment', [EducationalAttainmentController::class, 'storeEducationalAttainment'])->name('employees.storeEducationalAttainment');
    Route::post('employees/{employee}/storeCivilService', [CivilServiceController::class, 'storeCivilService'])->name('employees.storeCivilService');
    Route::post('employees/{employee}/storeSeminarTraining', [SeminarTrainingController::class, 'storeSeminarTraining'])->name('employees.storeSeminarTraining');
    Route::resource('roles', RoleController::class);
    Route::resource('positions', PositionController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('tenureships', TenureshipController::class);
    Route::get('/createPermission', [RoleController::class, 'createPermission'])->name('createPermission');
    Route::post('/storePermission', [RoleController::class, 'storePermission'])->name('storePermission');
    Route::resource('users', UserController::class);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__.'/auth.php';
