<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\LeaveApplicationsController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\TripTicketPassengersController;
use App\Http\Controllers\TripTicketsController;
use App\Http\Controllers\TripTicketGRSController;
use App\Http\Controllers\OffsetApplicationsController;
use App\Http\Controllers\AttendaneConfirmationsController;
use App\Http\Controllers\OvertimesController;
use App\Http\Controllers\PayrollIndexController;
use App\Http\Controllers\EmployeePayrollSchedulesController;
use App\Http\Controllers\LoansController;
use App\Http\Controllers\OtherPayrollDeductionsController;
use App\Http\Controllers\EmployeePayrollSundriesController;
use App\Http\Controllers\IncentivesAnnualProjectionController;
use App\Http\Controllers\EmployeeIncentiveAnnualProjectionsController;
use App\Http\Controllers\EmployeeBonusesController;
use App\Http\Controllers\PayrollExpandedDetailsController;
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

Route::post('/home/chat-reeve', [HomeController::class, 'chatReeve'])->name('home.chat-reeve');
Route::get('/home/reeve', [HomeController::class, 'reeve'])->name('home.reeve');

Route::get('/users/add_permissions/{id}', [UsersController::class, 'addRoles'])->name('users.add-roles');
Route::post('/users/create-roles', [UsersController::class, 'createRoles']);
Route::get('/users/switch-color-modes', [UsersController::class, 'switchColorModes'])->name('users.switch-color-modes');
Route::resource('users', App\Http\Controllers\UsersController::class);

Route::get('/register/get-employee-ajax', [App\Http\Controllers\Auth\RegisterController::class, 'getEmployeeAjax'])->name('register.get-employee-ajax');

Route::get('/employees/create-designations/{id}', [EmployeesController::class, 'createDesignations'])->name('employees.create-designations');
Route::get('/employees/get-search-results', [EmployeesController::class, 'getSearchResults'])->name('employees.get-search-results');
Route::get('/employees/update-ranking/{id}', [EmployeesController::class, 'updateRanking'])->name('employees.update-ranking');
Route::post('/employees/add-ranking', [EmployeesController::class, 'addRanking']);
Route::get('/employees/update-educational-attainment/{id}', [EmployeesController::class, 'updateEducationalAttainment'])->name('employees.update-educational-attainment');
Route::post('/employees/save-educatonal-attainment', [EmployeesController::class, 'saveEducationalAttainment']);
Route::get('/employees/download-file/{folder}/{type}/{file}', [EmployeesController::class, 'downloadFile'])->name('employees.download-file');
Route::get('/employees/update-third-party-ids/{id}', [EmployeesController::class, 'updateThirdPartyIDs'])->name('employees.update-third-party-ids');
Route::get('/employees/attendance/{id}', [EmployeesController::class, 'attendance'])->name('employees.attendance');
Route::get('/employees/get-attendance', [EmployeesController::class, 'getAttendance'])->name('employees.get-attendance');
Route::get('/employees/capture-image/{id}', [EmployeesController::class, 'captureImage'])->name('employees.capture-image');
Route::post('/employees/create-image', [EmployeesController::class, 'createImage'])->name('employees.create-image');
Route::get('/employees/get-image/{id}', [EmployeesController::class, 'getImage'])->name('employees.get-image');
Route::get('/employees/get-employee-ajax', [EmployeesController::class, 'getEmployeeAjax'])->name('employees.get-employee-ajax');
Route::get('/employees/get-employees-ajax', [EmployeesController::class, 'getEmployeesAjax'])->name('employees.get-employees-ajax');
Route::get('/employees/get-attendance-data-ajax', [EmployeesController::class, 'getAttendanceDataAjax'])->name('employees.get-attendance-data-ajax');
Route::get('/employees/allow-no-attendance', [EmployeesController::class, 'allowNoAttendance'])->name('employees.allow-no-attendance');
Route::get('/employees/save-payroll-sundries', [EmployeesController::class, 'savePayrollSundries'])->name('employees.save-payroll-sundries');
Route::get('/employees/update-office', [EmployeesController::class, 'updateOffice'])->name('employees.update-office');
Route::get('/employees/update-date-hired', [EmployeesController::class, 'updateDateHired'])->name('employees.update-date-hired');
Route::get('/employees/update-end', [EmployeesController::class, 'updateEnd'])->name('employees.update-end');
Route::get('/employees/update-biometrics-id', [EmployeesController::class, 'updateBiometricsId'])->name('employees.update-biometrics-id');
Route::resource('employees', EmployeesController::class);

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


Route::get('/leave_applications/create-step-two/{id}', [LeaveApplicationsController::class, 'createStepTwo'])->name('leaveApplications.create-step-two');
Route::post('/leave_applications/add-signatories', [LeaveApplicationsController::class, 'addSignatories'])->name('leaveApplications.add-signatories');
Route::get('/leave_applications/approvals/{id}', [LeaveApplicationsController::class, 'leaveApprovals'])->name('leaveApplications.approvals');
Route::get('/leave_applications/approve-leave/{id}/{signatoryId}', [LeaveApplicationsController::class, 'approveLeave'])->name('leaveApplications.approve-leave');
Route::get('/leave_applications/my-approvals', [LeaveApplicationsController::class, 'myApprovals'])->name('leaveApplications.my-approvals');
Route::get('/leave_applications/approve-ajax', [LeaveApplicationsController::class, 'approveAjax'])->name('leaveApplications.approve-ajax');
Route::get('/leave_applications/delete-leave', [LeaveApplicationsController::class, 'deleteLeave'])->name('leaveApplications.delete-leave');
Route::post('/leave_applications/add-image-attachments', [LeaveApplicationsController::class, 'addImageAttachments'])->name('leaveApplications.add-image-attachments');
Route::get('/leave_applications/remove-image', [LeaveApplicationsController::class, 'removeImage'])->name('leaveApplications.remove-image');
Route::get('/leave_applications/remove-leave-signatory', [LeaveApplicationsController::class, 'removeLeaveSignatory'])->name('leaveApplications.remove-leave-signatory');
Route::get('/leave_applications/reject-leave-ajax', [LeaveApplicationsController::class, 'rejectLeaveAjax'])->name('leaveApplications.reject-leave-ajax');
Route::resource('leaveApplications', LeaveApplicationsController::class);

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

Route::get('/payroll_indices/choose-payroll-type', [PayrollIndexController::class, 'choosePayrollType'])->name('payrollIndices.choose-payroll-type');
Route::post('/payroll_indices/validate-payroll-select-type', [PayrollIndexController::class, 'validatePayrollSelectType'])->name('payrollIndices.validate-payroll-select-type');
Route::get('/payroll_indices/process-payroll/{payrollIndexId}', [PayrollIndexController::class, 'processPayroll'])->name('payrollIndices.process-payroll');
Route::get('/payroll_indices/generate-payroll/{id}', [PayrollIndexController::class, 'generatePayroll'])->name('payrollIndices.generate-payroll');
Route::get('/payroll_indices/payslip/{id}', [PayrollIndexController::class, 'payslip'])->name('payrollIndices.payslip');
Route::get('/payroll_indices/payroll', [PayrollIndexController::class, 'payroll'])->name('payrollIndices.payroll');
Route::get('/payroll_indices/get-payroll-data', [PayrollIndexController::class, 'getPayrollData'])->name('payrollIndices.get-payroll-data');
Route::get('/payroll_indices/get-payroll-date-information', [PayrollIndexController::class, 'getPayrollDateInformation'])->name('payrollIndices.get-payroll-date-information');
Route::get('/payroll_indices/payroll-audit', [PayrollIndexController::class, 'payrollAudit'])->name('payrollIndices.payroll-audit');
Route::get('/payroll_indices/payroll-audit-review/{salaryPeriod}', [PayrollIndexController::class, 'payrollAuditReview'])->name('payrollIndices.payroll-audit-review');
Route::resource('payrollIndices', PayrollIndexController::class);


Route::resource('payrollDetails', App\Http\Controllers\PayrollDetailsController::class);


Route::get('/overtimes/get-overtime-ajax', [OvertimesController::class, 'getOvertimeAjax'])->name('overtimes.get-overtime-ajax');
Route::get('/overtimes/my-approvals', [OvertimesController::class, 'myApprovals'])->name('overtimes.my-approvals');
Route::get('/overtimes/save', [OvertimesController::class, 'save'])->name('overtimes.save');
Route::get('/overtimes/approve', [OvertimesController::class, 'approve'])->name('overtimes.approve');
Route::get('/overtimes/reject', [OvertimesController::class, 'reject'])->name('overtimes.reject');
Route::resource('overtimes', OvertimesController::class);

Route::get('/positions/update-super', [App\Http\Controllers\PositionsController::class, 'updateSuper'])->name('positions.update-super');
Route::resource('positions', App\Http\Controllers\PositionsController::class);


Route::get('/leaveDays/add-days', [App\Http\Controllers\LeaveDaysController::class, 'addDays'])->name('leaveDays.add-days');
Route::get('/leaveDays/update-longevity', [App\Http\Controllers\LeaveDaysController::class, 'updateLongevity'])->name('leaveDays.update-longevity');
Route::resource('leaveDays', App\Http\Controllers\LeaveDaysController::class);


Route::resource('biometricUsers', App\Http\Controllers\BiometricUsersController::class);

Route::get('/attendance_datas/fetch-by-employee-and-date', [App\Http\Controllers\AttendanceDataController::class, 'fetchByEmployeeAndDate'])->name('attendanceDatas.fetch-by-employee-and-date');
Route::resource('attendanceDatas', App\Http\Controllers\AttendanceDataController::class);


Route::get('/employee_payroll_schedules/create-schedule', [EmployeePayrollSchedulesController::class, 'createSchedule'])->name('employeePayrollSchedules.create-schedule');
Route::get('/employee_payroll_schedules/update-dayoff', [EmployeePayrollSchedulesController::class, 'updateDayOff'])->name('employeePayrollSchedules.update-dayoff');
Route::resource('employeePayrollSchedules', EmployeePayrollSchedulesController::class);


Route::resource('payrollSchedules', App\Http\Controllers\PayrollSchedulesController::class);


Route::resource('leaveBalances', App\Http\Controllers\LeaveBalancesController::class);


Route::resource('leaveBalanceDetails', App\Http\Controllers\LeaveBalanceDetailsController::class);


Route::resource('leaveImageAttachments', App\Http\Controllers\LeaveImageAttachmentsController::class);

Route::resource('biometricDevices', App\Http\Controllers\BiometricDevicesController::class);


Route::resource('holidaysLists', App\Http\Controllers\HolidaysListController::class);

Route::get('/trip_tickets/get-trip-ticket-ajax', [TripTicketsController::class, 'getTripTicketAjax'])->name('tripTickets.get-trip-ticket-ajax');
Route::get('/trip_tickets/get-signatories', [TripTicketsController::class, 'getSignatories'])->name('tripTickets.get-signatories');
Route::get('/trip_tickets/my-trip-tickets/{userId}', [TripTicketsController::class, 'myTripTickets'])->name('tripTickets.my-trip-tickets');
Route::get('/trip_tickets/my-approvals', [TripTicketsController::class, 'myApprovals'])->name('tripTickets.my-approvals');
Route::get('/trip_tickets/approve-trip-ticket', [TripTicketsController::class, 'approveTripTicket'])->name('tripTickets.approve-trip-ticket');
Route::get('/trip_tickets/reject-trip-ticket', [TripTicketsController::class, 'rejectTripTicket'])->name('tripTickets.reject-trip-ticket');
Route::get('/trip_tickets/request-grs', [TripTicketsController::class, 'requestGRS'])->name('tripTickets.request-grs');
Route::get('/trip_tickets/log-vehicle-trips', [TripTicketsController::class, 'logVehicleTrips'])->name('tripTickets.log-vehicle-trips');
Route::get('/trip_tickets/log-departure', [TripTicketsController::class, 'logDeparture'])->name('tripTickets.log-departure');
Route::get('/trip_tickets/log-vehicle-arrivals', [TripTicketsController::class, 'logVehicleArrivals'])->name('tripTickets.log-vehicle-arrivals');
Route::get('/trip_tickets/log-arrival', [TripTicketsController::class, 'logArrival'])->name('tripTickets.log-arrival');
Route::resource('tripTickets', TripTicketsController::class);

Route::get('/trip_ticket_destinations/remove-destination', [App\Http\Controllers\TripTicketDestinationsController::class, 'removeDestination'])->name('tripTicketDestinations.remove-destination');
Route::resource('tripTicketDestinations', App\Http\Controllers\TripTicketDestinationsController::class);

Route::get('/trip_ticket_passengers/remove-passenger-ajax', [TripTicketPassengersController::class, 'removePassengerAjax'])->name('tripTicketPassengers.remove-passenger-ajax');
Route::resource('tripTicketPassengers', TripTicketPassengersController::class);
Route::resource('vehicles', App\Http\Controllers\VehiclesController::class);
Route::resource('trip-ticket-signatories', App\Http\Controllers\TripTicketSignatoriesController::class);

Route::get('/trip_ticket_g_rs/print-grs/{ttId}/{grsId}', [TripTicketGRSController::class, 'printGRS'])->name('tripTicketGRS.print-grs');
Route::get('/trip_ticket_g_rs/save-grs', [TripTicketGRSController::class, 'saveGRS'])->name('tripTicketGRS.save-grs');
Route::get('/trip_ticket_g_rs/grs-requests', [TripTicketGRSController::class, 'grsRequests'])->name('tripTicketGRS.grs-requests');
Route::resource('tripTicketGRS', TripTicketGRSController::class);

Route::get('/offset_applications/reject', [OffsetApplicationsController::class, 'reject'])->name('offsetApplications.reject');
Route::get('/offset_applications/approve', [OffsetApplicationsController::class, 'approve'])->name('offsetApplications.approve');
Route::get('/offset_applications/my-approvals', [OffsetApplicationsController::class, 'myApprovals'])->name('offsetApplications.my-approvals');
Route::get('/offset_applications/save-offset-applications', [OffsetApplicationsController::class, 'saveOffsetApplications'])->name('offsetApplications.save-offset-applications');
Route::resource('offsetApplications', OffsetApplicationsController::class);

Route::resource('offsetSignatories', App\Http\Controllers\OffsetSignatoriesController::class);

Route::get('/attendane_confirmations/reject', [AttendaneConfirmationsController::class, 'reject'])->name('attendanceConfirmations.reject');
Route::get('/attendane_confirmations/approve', [AttendaneConfirmationsController::class, 'approve'])->name('attendanceConfirmations.approve');
Route::get('/attendane_confirmations/my-approvals', [AttendaneConfirmationsController::class, 'myApprovals'])->name('attendanceConfirmations.my-approvals');
Route::resource('attendanceConfirmations', AttendaneConfirmationsController::class);
Route::resource('attendanceConfirmationSignatories', App\Http\Controllers\AttendaneConfirmationSignatoriesController::class);

Route::resource('overtimeSignatories', App\Http\Controllers\OvertimeSignatoriesController::class);
Route::resource('dayOffSchedules', App\Http\Controllers\DayOffSchedulesController::class);
Route::resource('specialDutyDays', App\Http\Controllers\SpecialDutyDaysController::class);

Route::get('/employee_payroll_sundries/contributions', [EmployeePayrollSundriesController::class, 'contributions'])->name('employeePayrollSundries.contributions');
Route::get('/employee_payroll_sundries/get-contribution-data', [EmployeePayrollSundriesController::class, 'getContributionData'])->name('employeePayrollSundries.get-contribution-data');
Route::get('/employee_payroll_sundries/insert-contribution-data', [EmployeePayrollSundriesController::class, 'insertContributionData'])->name('employeePayrollSundries.insert-contribution-data');
Route::get('/employee_payroll_sundries/insert-all-contribution-data', [EmployeePayrollSundriesController::class, 'insertAllContributionData'])->name('employeePayrollSundries.insert-all-contribution-data');
Route::post('/employee_payroll_sundries/insert-all-contribution-array-data', [EmployeePayrollSundriesController::class, 'insertAllContributionArrayData'])->name('employeePayrollSundries.insert-all-contribution-array-data');
Route::resource('employeePayrollSundries', EmployeePayrollSundriesController::class);

Route::get('/loans/pag-ibig', [LoansController::class, 'pagIbig'])->name('loans.pag-ibig');
Route::get('/loans/save-pag-ibig-loans', [LoansController::class, 'savePagIbigLoans'])->name('loans.save-pag-ibig-loans');
Route::get('/loans/get-loan-details-ajax', [LoansController::class, 'getLoanDetailsAjax'])->name('loans.get-loan-details-ajax');
Route::get('/loans/sss', [LoansController::class, 'sss'])->name('loans.sss');
Route::get('/loans/save-sss-loans', [LoansController::class, 'saveSSSLoans'])->name('loans.save-sss-loans');
Route::get('/loans/motorcycle', [LoansController::class, 'motorcycle'])->name('loans.motorcycle');
Route::get('/loans/save-motorcycle-loans', [LoansController::class, 'saveMotorcycleLoans'])->name('loans.save-motorcycle-loans');
Route::get('/loans/other-loans', [LoansController::class, 'otherLoans'])->name('loans.other-loans');
Route::get('/loans/save-other-loans', [LoansController::class, 'saveOtherLoans'])->name('loans.save-other-loans');
Route::resource('loans', LoansController::class);

Route::resource('loanDetails', App\Http\Controllers\LoanDetailsController::class);

Route::get('/other_payroll_deductions/multiple-payroll-deductions', [OtherPayrollDeductionsController::class, 'multiplePayrollDeductions'])->name('otherPayrollDeductions.multiple-payroll-deductions');
Route::get('/other_payroll_deductions/get-other-deduction-multiple-data', [OtherPayrollDeductionsController::class, 'getOtherDeductionMultipleData'])->name('otherPayrollDeductions.get-other-deduction-multiple-data');
Route::get('/other_payroll_deductions/update-other-deduction-data', [OtherPayrollDeductionsController::class, 'updateOtherDeductionData'])->name('otherPayrollDeductions.update-other-deduction-data');
Route::resource('otherPayrollDeductions', OtherPayrollDeductionsController::class);

Route::get('/incentives_annual_projections/insert-datas', [IncentivesAnnualProjectionController::class, 'insertDatas'])->name('incentivesAnnualProjections.insert-datas');
Route::get('/incentives_annual_projections/get-incentives-per-year', [IncentivesAnnualProjectionController::class, 'getIncentivesPerYear'])->name('incentivesAnnualProjections.get-incentives-per-year');
Route::get('/incentives_annual_projections/update-data', [IncentivesAnnualProjectionController::class, 'updateData'])->name('incentivesAnnualProjections.update-data');
Route::get('/incentives_annual_projections/remove', [IncentivesAnnualProjectionController::class, 'remove'])->name('incentivesAnnualProjections.remove');
Route::resource('incentivesAnnualProjections', IncentivesAnnualProjectionController::class);

Route::get('/employee_incentive_annual_projections/project-all', [EmployeeIncentiveAnnualProjectionsController::class, 'projectAll'])->name('employeeIncentiveAnnualProjections.project-all');
Route::get('/employee_incentive_annual_projections/incentive-withholding-taxes', [EmployeeIncentiveAnnualProjectionsController::class, 'incentiveWithholdingTaxes'])->name('employeeIncentiveAnnualProjections.incentive-withholding-taxes');
Route::get('/employee_incentive_annual_projections/get-employee-projection', [EmployeeIncentiveAnnualProjectionsController::class, 'getEmployeeProjection'])->name('employeeIncentiveAnnualProjections.get-employee-projection');
Route::get('/employee_incentive_annual_projections/update-all-deduct-monthly', [EmployeeIncentiveAnnualProjectionsController::class, 'updateAllDeductMonthly'])->name('employeeIncentiveAnnualProjections.update-all-deduct-monthly');
Route::resource('employeeIncentiveAnnualProjections', EmployeeIncentiveAnnualProjectionsController::class);
Route::resource('employeeBonuses', EmployeeBonusesController::class);

Route::post('/payroll_expanded_details/bulk-save-payroll', [PayrollExpandedDetailsController::class, 'bulkSavePayroll'])->name('payrollExpandedDetails.bulk-save-payroll');
Route::resource('payrollExpandedDetails', PayrollExpandedDetailsController::class);