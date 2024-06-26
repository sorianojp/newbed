<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ChildrenDataController;
use App\Http\Controllers\CivilServiceController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DistinctionRecognitionController;
use App\Http\Controllers\EducationalAttainmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeePersonalDataController;
use App\Http\Controllers\EmploymentRecordController;
use App\Http\Controllers\GroupAffiliationController;
use App\Http\Controllers\JobSkillController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\OvertimeTypeController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegularScheduleController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SeminarTrainingController;
use App\Http\Controllers\TeachingScheduleController;
use App\Http\Controllers\TenureshipController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/search-employees', [EmployeeController::class, 'searchEmployees']);
    Route::resource('employees', EmployeeController::class);
    Route::post('employees/{employee}/storePersonalData', [EmployeePersonalDataController::class, 'storePersonalData'])->name('employees.storePersonalData');
    Route::post('employees/{employee}/storeEmploymentRecord', [EmploymentRecordController::class, 'storeEmploymentRecord'])->name('employees.storeEmploymentRecord');
    Route::post('employees/{employee}/storeEducationalAttainment', [EducationalAttainmentController::class, 'storeEducationalAttainment'])->name('employees.storeEducationalAttainment');
    Route::post('employees/{employee}/storeCivilService', [CivilServiceController::class, 'storeCivilService'])->name('employees.storeCivilService');
    Route::post('employees/{employee}/storeSeminarTraining', [SeminarTrainingController::class, 'storeSeminarTraining'])->name('employees.storeSeminarTraining');
    Route::post('employees/{employee}/storeDistinctionRecognition', [DistinctionRecognitionController::class, 'storeDistinctionRecognition'])->name('employees.storeDistinctionRecognition');
    Route::post('employees/{employee}/storeGroupAffiliation', [GroupAffiliationController::class, 'storeGroupAffiliation'])->name('employees.storeGroupAffiliation');
    Route::post('employees/{employee}/storeJobSkill', [JobSkillController::class, 'storeJobSkill'])->name('employees.storeJobSkill');
    Route::post('employees/{employee}/storeChildrenData', [ChildrenDataController::class, 'storeChildrenData'])->name('employees.storeChildrenData');
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

    Route::get('/get-attendances', [AttendanceController::class, 'getAttendances']);
    Route::resource('attendances', AttendanceController::class);

    Route::get('/get-regular-schedules', [RegularScheduleController::class, 'getSchedules']);
    Route::resource('regular-schedules', RegularScheduleController::class)->parameters([
        'regular-schedules' => 'regularSchedule',
    ]);
    Route::get('/get-teaching-schedules', [TeachingScheduleController::class, 'getSchedules']);
    Route::resource('teaching-schedules', TeachingScheduleController::class)->parameters([
        'teaching-schedules' => 'teachingSchedule',
    ]);

    Route::resource('overtime-types', OvertimeTypeController::class);

    Route::get('/get-overtime', [OvertimeController::class, 'getOvertime']);
    Route::resource('overtimes', OvertimeController::class);

    Route::get('/test-payroll', [PayrollController::class, 'index']);
});

require __DIR__ . '/auth.php';
