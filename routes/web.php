<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UsersController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('home');
});

Auth::routes();

Route::get('/home', [
    HomeController::class, 'index'
])->name('home');

Route::get('/users/add_permissions/{id}', [UsersController::class, 'addRoles'])->name('users.add-roles');
Route::post('/users/create-roles', [UsersController::class, 'createRoles']);
Route::resource('users', App\Http\Controllers\UsersController::class);

Route::get('/register/get-employee-ajax', [App\Http\Controllers\Auth\RegisterController::class, 'getEmployeeAjax'])->name('register.get-employee-ajax');

Route::get('/employees/create-designations/{id}', [App\Http\Controllers\EmployeesController::class, 'createDesignations'])->name('employees.create-designations');
Route::get('/employees/get-search-results', [App\Http\Controllers\EmployeesController::class, 'getSearchResults'])->name('employees.get-search-results');
Route::get('/employees/update-ranking/{id}', [App\Http\Controllers\EmployeesController::class, 'updateRanking'])->name('employees.update-ranking');
Route::post('/employees/add-ranking', [App\Http\Controllers\EmployeesController::class, 'addRanking']);
Route::get('/employees/update-educational-attainment/{id}', [App\Http\Controllers\EmployeesController::class, 'updateEducationalAttainment'])->name('employees.update-educational-attainment');
Route::post('/employees/save-educatonal-attainment', [App\Http\Controllers\EmployeesController::class, 'saveEducationalAttainment']);
Route::get('/employees/download-file/{folder}/{type}/{file}', [App\Http\Controllers\EmployeesController::class, 'downloadFile'])->name('employees.download-file');
Route::get('/employees/update-third-party-ids/{id}', [App\Http\Controllers\EmployeesController::class, 'updateThirdPartyIDs'])->name('employees.update-third-party-ids');
Route::get('/employees/attendance/{id}', [App\Http\Controllers\EmployeesController::class, 'attendance'])->name('employees.attendance');
Route::get('/employees/get-attendance', [App\Http\Controllers\EmployeesController::class, 'getAttendance'])->name('employees.get-attendance');
Route::get('/employees/capture-image/{id}', [App\Http\Controllers\EmployeesController::class, 'captureImage'])->name('employees.capture-image');
Route::post('/employees/create-image', [App\Http\Controllers\EmployeesController::class, 'createImage'])->name('employees.create-image');
Route::get('/employees/get-image/{id}', [App\Http\Controllers\EmployeesController::class, 'getImage'])->name('employees.get-image');
Route::get('/employees/get-employee-ajax', [App\Http\Controllers\EmployeesController::class, 'getEmployeeAjax'])->name('employees.get-employee-ajax');
Route::get('/employees/get-attendance-data-ajax', [App\Http\Controllers\EmployeesController::class, 'getAttendanceDataAjax'])->name('employees.get-attendance-data-ajax');
Route::resource('employees', App\Http\Controllers\EmployeesController::class);

Route::resource('permissions', App\Http\Controllers\PermissionController::class);


Route::resource('roles', App\Http\Controllers\RoleController::class);
Route::get('/roles/add-permissions/{id}', [RoleController::class, 'addPermissions'])->name('roles.add_permissions');
Route::post('/roles/create-role-permissions', [RoleController::class, 'createRolePermissions']);

Route::resource('towns', App\Http\Controllers\TownsController::class);

Route::get('/barangays/get-barangays-json/{townId}', [App\Http\Controllers\BarangaysController::class, 'getBarangaysJSON']);
Route::resource('barangays', App\Http\Controllers\BarangaysController::class);


Route::resource('employeesDesignations', App\Http\Controllers\EmployeesDesignationsController::class);


Route::resource('rankingRepositories', App\Http\Controllers\RankingRepositoryController::class);


Route::resource('rankings', App\Http\Controllers\RankingsController::class);


Route::resource('educationalAttainments', App\Http\Controllers\EducationalAttainmentController::class);


Route::resource('professionalIDs', App\Http\Controllers\ProfessionalIDsController::class);


Route::get('/leave_applications/create-step-two/{id}', [App\Http\Controllers\LeaveApplicationsController::class, 'createStepTwo'])->name('leaveApplications.create-step-two');
Route::post('/leave_applications/add-signatories', [App\Http\Controllers\LeaveApplicationsController::class, 'addSignatories'])->name('leaveApplications.add-signatories');
Route::get('/leave_applications/approvals/{id}', [App\Http\Controllers\LeaveApplicationsController::class, 'leaveApprovals'])->name('leaveApplications.approvals');
Route::get('/leave_applications/approve-leave/{id}/{signatoryId}', [App\Http\Controllers\LeaveApplicationsController::class, 'approveLeave'])->name('leaveApplications.approve-leave');
Route::get('/leave_applications/my-approvals', [App\Http\Controllers\LeaveApplicationsController::class, 'myApprovals'])->name('leaveApplications.my-approvals');
Route::get('/leave_applications/approve-ajax', [App\Http\Controllers\LeaveApplicationsController::class, 'approveAjax'])->name('leaveApplications.approve-ajax');
Route::get('/leave_applications/delete-leave', [App\Http\Controllers\LeaveApplicationsController::class, 'deleteLeave'])->name('leaveApplications.delete-leave');
Route::post('/leave_applications/add-image-attachments', [App\Http\Controllers\LeaveApplicationsController::class, 'addImageAttachments'])->name('leaveApplications.add-image-attachments');
Route::get('/leave_applications/remove-image', [App\Http\Controllers\LeaveApplicationsController::class, 'removeImage'])->name('leaveApplications.remove-image');
Route::resource('leaveApplications', App\Http\Controllers\LeaveApplicationsController::class);

Route::resource('leaveSignatories', App\Http\Controllers\LeaveSignatoriesController::class);

Route::get('/notifications/get-all-notifications', [App\Http\Controllers\NotificationsController::class, 'getAllNotifications'])->name('notifications.get-all-notifications');
Route::get('/notifications/get-notif-counter', [App\Http\Controllers\NotificationsController::class, 'getNotifCounter'])->name('notifications.get-notif-counter');
Route::get('/notifications/mark-as-read/{id}', [App\Http\Controllers\NotificationsController::class, 'markAsRead'])->name('notifications.mark-as-read');
Route::resource('notifications', App\Http\Controllers\NotificationsController::class);


Route::resource('leaveSignatoriesRepositories', App\Http\Controllers\LeaveSignatoriesRepositoryController::class);


Route::resource('attendances', App\Http\Controllers\AttendancesController::class);


Route::resource('attendanceRules', App\Http\Controllers\AttendanceRulesController::class);


Route::resource('leaveAttendanceDates', App\Http\Controllers\LeaveAttendanceDatesController::class);


Route::resource('employeeImages', App\Http\Controllers\EmployeeImagesController::class);

Route::get('/payroll_indices/choose-payroll-type', [App\Http\Controllers\PayrollIndexController::class, 'choosePayrollType'])->name('payrollIndices.choose-payroll-type');
Route::post('/payroll_indices/validate-payroll-select-type', [App\Http\Controllers\PayrollIndexController::class, 'validatePayrollSelectType'])->name('payrollIndices.validate-payroll-select-type');
Route::get('/payroll_indices/process-payroll/{payrollIndexId}', [App\Http\Controllers\PayrollIndexController::class, 'processPayroll'])->name('payrollIndices.process-payroll');
Route::get('/payroll_indices/generate-payroll/{id}', [App\Http\Controllers\PayrollIndexController::class, 'generatePayroll'])->name('payrollIndices.generate-payroll');
Route::get('/payroll_indices/payslip/{id}', [App\Http\Controllers\PayrollIndexController::class, 'payslip'])->name('payrollIndices.payslip');
Route::resource('payrollIndices', App\Http\Controllers\PayrollIndexController::class);


Route::resource('payrollDetails', App\Http\Controllers\PayrollDetailsController::class);


Route::resource('overtimes', App\Http\Controllers\OvertimesController::class);


Route::resource('positions', App\Http\Controllers\PositionsController::class);


Route::get('/leaveDays/add-days', [App\Http\Controllers\LeaveDaysController::class, 'addDays'])->name('leaveDays.add-days');
Route::get('/leaveDays/update-longevity', [App\Http\Controllers\LeaveDaysController::class, 'updateLongevity'])->name('leaveDays.update-longevity');
Route::resource('leaveDays', App\Http\Controllers\LeaveDaysController::class);


Route::resource('biometricUsers', App\Http\Controllers\BiometricUsersController::class);


Route::resource('attendanceDatas', App\Http\Controllers\AttendanceDataController::class);


Route::get('/employee_payroll_schedules/create-schedule', [App\Http\Controllers\EmployeePayrollSchedulesController::class, 'createSchedule'])->name('employeePayrollSchedules.create-schedule');
Route::resource('employeePayrollSchedules', App\Http\Controllers\EmployeePayrollSchedulesController::class);


Route::resource('payrollSchedules', App\Http\Controllers\PayrollSchedulesController::class);


Route::resource('leaveBalances', App\Http\Controllers\LeaveBalancesController::class);


Route::resource('leaveBalanceDetails', App\Http\Controllers\LeaveBalanceDetailsController::class);


Route::resource('leaveImageAttachments', App\Http\Controllers\LeaveImageAttachmentsController::class);

Route::resource('biometric-devices', App\Http\Controllers\BiometricDevicesController::class);