<?php

use App\Http\Controllers\AdditionalController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ChildrenDataController;
use App\Http\Controllers\CivilServiceController;
use App\Http\Controllers\ContributionSettingController;
use App\Http\Controllers\DeductionController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DistinctionRecognitionController;
use App\Http\Controllers\EducationalAttainmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeePersonalDataController;
use App\Http\Controllers\EmployeeSettingController;
use App\Http\Controllers\EmploymentRecordController;
use App\Http\Controllers\GroupAffiliationController;
use App\Http\Controllers\GroupingController;
use App\Http\Controllers\JobSkillController;
use App\Http\Controllers\NoDailyTimeRecordController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\OvertimeTypeController;
use App\Http\Controllers\PagibigController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PayrollScheduleController;
use App\Http\Controllers\PayrollTypeController;
use App\Http\Controllers\PhilhealthController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegularScheduleController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SeminarTrainingController;
use App\Http\Controllers\SSSBracketController;
use App\Http\Controllers\SSSController;
use App\Http\Controllers\TaxBracketController;
use App\Http\Controllers\TaxController;
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
    Route::get('/get-schedules', [AttendanceController::class, 'getSchedules']);
    Route::get('/checker-attendance', [AttendanceController::class, 'checkerReport'])->name('attendances.checker');
    Route::post('/checker-attendance', [AttendanceController::class, 'checkerReportStore'])->name('checker.store');
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

    Route::prefix('/payroll')->group(function () {
        Route::get('/schedules/get', [PayrollScheduleController::class, 'getSchedules']);
        Route::get('/additionals/get', [AdditionalController::class, 'getAdditionals']);
        Route::get('/deductions/get', [DeductionController::class, 'getDeductions']);
        Route::get('/settings/get-employee-settings', [EmployeeSettingController::class, 'getEmployeeConfig']);

        Route::resource('schedules', PayrollScheduleController::class)->parameters([
            'schedules' => 'payrollSchedule',
        ]);

        Route::get('/additionals/employee', [AdditionalController::class, 'indexEmployee'])->name('additional.employee');
        Route::post('/additionals/employee', [AdditionalController::class, 'storeEmployee'])->name('additional.employee.store');
        Route::get('/deductions/employee', [DeductionController::class, 'indexEmployee'])->name('deduction.employee');
        Route::post('/deductions/employee', [DeductionController::class, 'storeEmployee'])->name('deduction.employee.store');
        Route::resource('additionals', AdditionalController::class);
        Route::resource('deductions', DeductionController::class);

        Route::get('/individual', [PayrollController::class, 'showIndividual'])->name('computations.individual');
        Route::get('/individual/computations', [PayrollController::class, 'computePayroll']);
        Route::post('/individual/create-payslip', [PayrollController::class, 'createPayslip']);

        Route::get('/employee-settings', [EmployeeSettingController::class, 'index'])->name('employee.settings');
        Route::put('/employee-settings', [EmployeeSettingController::class, 'store'])->name('employee.settings.store');

        Route::resource('taxes', TaxController::class);
        Route::resource('taxes.tax-brackets', TaxBracketController::class)->parameters([
            'tax-brackets' => 'taxBracket',
        ])->shallow();
        Route::resource('sss', SSSController::class);
        Route::resource('sss.sss-brackets', SSSBracketController::class)->parameters([
            'sss-brackets' => 'sssBracket',
        ])->shallow();

        Route::resource('pagibig', PagibigController::class);
        Route::resource('philhealth', PhilhealthController::class);

        Route::resource('contribution-settings', ContributionSettingController::class)->parameters([
            'contribution-settings' => 'contributionSetting',
        ]);
    });

    Route::resource('no-dtr', NoDailyTimeRecordController::class);
    Route::resource('payroll-types', PayrollTypeController::class);
    Route::resource('groupings', GroupingController::class);
    Route::put('groupings/{grouping}/employees', [GroupingController::class, 'addEmployees'])->name('addEmployees');
});

require __DIR__ . '/auth.php';
