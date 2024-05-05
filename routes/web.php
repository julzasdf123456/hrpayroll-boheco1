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
use App\Http\Controllers\IncentivesController;
use App\Http\Controllers\BempcController;
use App\Http\Controllers\LeaveConversionsController;
use App\Http\Controllers\IncentivesYearEndDetailsController;
use App\Http\Controllers\LeaveBalancesController;
use App\Http\Controllers\AttachedAccountsController;
use App\Http\Controllers\EmployeeDayOffsController;
use App\Http\Controllers\DependentsController;
use App\Http\Controllers\TravelOrdersController;
use App\Http\Controllers\PositionsController;
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
Route::get('/home/employee-finder', [HomeController::class, 'employee_finder'])->name('home.employee-finder');

Route::get('/users/add_permissions/{id}', [UsersController::class, 'addRoles'])->name('users.add-roles');
Route::post('/users/create-roles', [UsersController::class, 'createRoles']);
Route::get('/users/switch-color-modes', [UsersController::class, 'switchColorModes'])->name('users.switch-color-modes');

Route::get('/my_account/my-account-index/{employeeId}', [UsersController::class, 'myAccountIndex'])->name('users.my-account-index');
Route::get('/my_account/leave-credits/{employeeId}', [UsersController::class, 'leaveCredits'])->name('users.leave-credits');
Route::get('/my_account/view-leave/{leaveId}', [UsersController::class, 'viewLeave'])->name('users.view-leave');
Route::get('/my_account/payroll-dashboard', [UsersController::class, 'payrollDashboard'])->name('users.payroll-dashboard');
Route::get('/my_account/payroll-detailed-view', [UsersController::class, 'payrollDetailedView'])->name('users.payroll-detailed-view');
Route::get('/my_account/attach-boheco-account', [UsersController::class, 'attachBohecoAccount'])->name('users.attach-boheco-account');
Route::get('/my_account/search-boheco-accounts', [UsersController::class, 'searchBohecoAccounts'])->name('users.search-boheco-accounts');
Route::get('/my_account/personal-info', [UsersController::class, 'personalInfo'])->name('users.personal-info');
Route::get('/my_account/get-incentives-by-employee-id', [UsersController::class, 'getIncentivesByEmployeeId'])->name('users.get-incentives-by-employee-id');
Route::get('/my_account/staff-management', [UsersController::class, 'staffManagement'])->name('users.staff-management');
Route::get('/my_account/get-staff', [UsersController::class, 'getStaff'])->name('users.get-staff');
Route::get('/my_account/get-employees-by-department', [UsersController::class, 'getEmployeesByDepartment'])->name('users.get-employees-by-department');
Route::get('/my_account/staff-day-off-schedules/{employeeId}', [UsersController::class, 'staffDayOffSchedules'])->name('users.staff-day-off-schedules');
Route::get('/my_account/attendance-index', [UsersController::class, 'attendanceIndex'])->name('users.attendance-index');
Route::get('/my_account/staff-super-view/{id}', [UsersController::class, 'staffSuperView'])->name('users.staff-super-view');
Route::resource('users', UsersController::class);

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
Route::get('/employees/update-pitakard', [EmployeesController::class, 'updatePitakard'])->name('employees.update-pitakard');
Route::get('/employees/upload-file/{id}', [EmployeesController::class, 'uploadFile'])->name('employees.upload-file');
Route::post('/employees/save-uploaded-files', [EmployeesController::class, 'saveUploadedFiles'])->name('employees.save-uploaded-files');
Route::get('/employees/fetch-files', [EmployeesController::class, 'fetchFiles'])->name('employees.fetch-files');
Route::get('/employees/rename-file', [EmployeesController::class, 'renameFile'])->name('employees.rename-file');
Route::post('/employees/trash-file', [EmployeesController::class, 'trashFile'])->name('employees.trash-file');
Route::get('/employees/get-employee-full-ajax', [EmployeesController::class, 'getEmployeeFullAjax'])->name('employees.get-employee-full-ajax');
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
Route::get('/leave_applications/get-leaves-by-type', [LeaveApplicationsController::class, 'getLeavesByType'])->name('leaveApplications.get-leaves-by-type');
Route::get('/leave_applications/manual-entries', [LeaveApplicationsController::class, 'manualEntries'])->name('leaveApplications.manual-entries');
Route::get('/leave_applications/get-leave-balances-by-employee', [LeaveApplicationsController::class, 'getLeaveBalancesByEmployee'])->name('leaveApplications.get-leave-balances-by-employee');
Route::get('/leave_applications/manual-save', [LeaveApplicationsController::class, 'manualSave'])->name('leaveApplications.manual-save');
Route::get('/leave_applications/publish-leave/{id}', [LeaveApplicationsController::class, 'publishLeave'])->name('leaveApplications.publish-leave');
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
Route::get('/payroll_indices/audit-reject-payroll', [PayrollIndexController::class, 'auditRejectPayroll'])->name('payrollIndices.audit-reject-payroll');
Route::get('/payroll_indices/audit-approve-payroll', [PayrollIndexController::class, 'auditApprovePayroll'])->name('payrollIndices.audit-approve-payroll');
Route::get('/payroll_indices/view-payroll/{salaryPeriod}', [PayrollIndexController::class, 'viewPayroll'])->name('payrollIndices.view-payroll');
Route::get('/payroll_indices/remove-payroll', [PayrollIndexController::class, 'removePayroll'])->name('payrollIndices.remove-payroll');
Route::get('/payroll_indices/withholding-taxes', [PayrollIndexController::class, 'withholdingTaxes'])->name('payrollIndices.withholding-taxes');
Route::get('/payroll_indices/get-withholding-taxes-report-data', [PayrollIndexController::class, 'getWithholdingTaxesReportData'])->name('payrollIndices.get-withholding-taxes-report-data');
Route::get('/payroll_indices/view-payroll-without-deduction/{salaryPeriod}', [PayrollIndexController::class, 'viewPayrollWithoutDeduction'])->name('payrollIndices.view-payroll-without-deduction');
Route::get('/payroll_indices/view-payroll-deductions-only/{salaryPeriod}', [PayrollIndexController::class, 'viewPayrollDeductionsOnly'])->name('payrollIndices.view-payroll-deductions-only');
Route::get('/payroll_indices/download-fcb-template/{salaryPeriod}', [PayrollIndexController::class, 'downloadFCBTemplate'])->name('payrollIndices.download-fcb-template');
Route::get('/payroll_indices/print-fcb-submission/{salaryPeriod}', [PayrollIndexController::class, 'printFCBSubmission'])->name('payrollIndices.print-fcb-submission');
Route::get('/payroll_indices/print-payroll-final/{salaryPeriod}', [PayrollIndexController::class, 'printPayrollFinal'])->name('payrollIndices.print-payroll-final');
Route::get('/payroll_indices/get-payroll-monthly-data', [PayrollIndexController::class, 'getPayrollMonthlyData'])->name('payrollIndices.get-payroll-monthly-data');
Route::get('/payroll_indices/zero-out/{salaryPeriod}', [PayrollIndexController::class, 'zeroOut'])->name('payrollIndices.zero-out');
Route::get('/payroll_indices/print-zero-out/{salaryPeriod}', [PayrollIndexController::class, 'printZeroOut'])->name('payrollIndices.print-zero-out');
Route::get('/payroll_indices/get-withholding-tax-data', [PayrollIndexController::class, 'getWithholdingTaxData'])->name('payrollIndices.get-withholding-tax-data');
Route::resource('payrollIndices', PayrollIndexController::class);


Route::resource('payrollDetails', App\Http\Controllers\PayrollDetailsController::class);


Route::get('/overtimes/get-overtime-ajax', [OvertimesController::class, 'getOvertimeAjax'])->name('overtimes.get-overtime-ajax');
Route::get('/overtimes/my-approvals', [OvertimesController::class, 'myApprovals'])->name('overtimes.my-approvals');
Route::get('/overtimes/save', [OvertimesController::class, 'save'])->name('overtimes.save');
Route::get('/overtimes/approve', [OvertimesController::class, 'approve'])->name('overtimes.approve');
Route::get('/overtimes/reject', [OvertimesController::class, 'reject'])->name('overtimes.reject');
Route::get('/overtimes/manual-entry', [OvertimesController::class, 'manualEntry'])->name('overtimes.manual-entry');
Route::get('/overtimes/get-overtimes-by-employee', [OvertimesController::class, 'getOvertimesByEmployee'])->name('overtimes.get-overtimes-by-employee');
Route::resource('overtimes', OvertimesController::class);

Route::get('/positions/tree-view', [PositionsController::class, 'treeView'])->name('positions.tree-view');
Route::get('/positions/update-super', [PositionsController::class, 'updateSuper'])->name('positions.update-super');
Route::get('/positions/get-positions', [PositionsController::class, 'getPositions'])->name('positions.get-positions');
Route::resource('positions', PositionsController::class);


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

Route::get('/leave_balances/get-leave-data', [LeaveBalancesController::class, 'getLeaveData'])->name('leaveBalances.get-leave-data');
Route::get('/leave_balances/batch-edit', [LeaveBalancesController::class, 'batchEdit'])->name('leaveBalances.batch-edit');
Route::get('/leave_balances/get-merge-data', [LeaveBalancesController::class, 'getMergeData'])->name('leaveBalances.get-merge-data');
Route::post('/leave_balances/update-value', [LeaveBalancesController::class, 'updateValue'])->name('leaveBalances.update-value');
Route::resource('leaveBalances', LeaveBalancesController::class);


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
Route::get('/trip_tickets/manual-entry', [TripTicketsController::class, 'manualEntry'])->name('tripTickets.manual-entry');
Route::get('/trip_tickets/get-trip-tickets-by-employee', [TripTicketsController::class, 'getTripTicketsByEmployee'])->name('tripTickets.get-trip-tickets-by-employee');
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
Route::get('/offset_applications/manual-entry', [OffsetApplicationsController::class, 'manualEntry'])->name('offsetApplications.manual-entry');
Route::get('/offset_applications/get-offsets-by-employee', [OffsetApplicationsController::class, 'getOffsetsByEmployee'])->name('offsetApplications.get-offsets-by-employee');
Route::resource('offsetApplications', OffsetApplicationsController::class);

Route::resource('offsetSignatories', App\Http\Controllers\OffsetSignatoriesController::class);

Route::get('/attendane_confirmations/reject', [AttendaneConfirmationsController::class, 'reject'])->name('attendanceConfirmations.reject');
Route::get('/attendane_confirmations/approve', [AttendaneConfirmationsController::class, 'approve'])->name('attendanceConfirmations.approve');
Route::get('/attendane_confirmations/my-approvals', [AttendaneConfirmationsController::class, 'myApprovals'])->name('attendanceConfirmations.my-approvals');
Route::get('/attendane_confirmations/manual-entry', [AttendaneConfirmationsController::class, 'manualEntry'])->name('attendanceConfirmations.manual-entry');
Route::post('/attendane_confirmations/save-manual-entry', [AttendaneConfirmationsController::class, 'saveManualEntry'])->name('attendanceConfirmations.save-manual-entry');
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
Route::get('/other_payroll_deductions/addons-and-deductions', [OtherPayrollDeductionsController::class, 'addOnsAndDeductions'])->name('otherPayrollDeductions.addons-and-deductions');
Route::get('/other_payroll_deductions/get-addons-and-deductions', [OtherPayrollDeductionsController::class, 'getAddonsAndDeductions'])->name('otherPayrollDeductions.get-addons-and-deductions');
Route::get('/other_payroll_deductions/update-addons-and-deductions', [OtherPayrollDeductionsController::class, 'updateAddonsAndDeductions'])->name('otherPayrollDeductions.update-addons-and-deductions');
Route::get('/other_payroll_deductions/update-data', [OtherPayrollDeductionsController::class, 'updateData'])->name('otherPayrollDeductions.update-data');
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
Route::resource('employeeIncntvAnnualProjections', EmployeeIncentiveAnnualProjectionsController::class);
Route::resource('employeeBonuses', EmployeeBonusesController::class);

Route::post('/payroll_expanded_details/bulk-save-payroll', [PayrollExpandedDetailsController::class, 'bulkSavePayroll'])->name('payrollExpandedDetails.bulk-save-payroll');
Route::resource('payrollExpandedDetails', PayrollExpandedDetailsController::class);
Route::resource('userFootprints', App\Http\Controllers\UserFootprintsController::class);
Route::resource('otherAddonsDeductions', App\Http\Controllers\OtherAddonsDeductionsController::class);
Route::resource('employeeIncntvsProjectionTaxMarks', App\Http\Controllers\EmployeeIncntvsProjectionTaxMarkController::class);

Route::get('/incentives/thirteenth-month-pay', [IncentivesController::class, 'thirteenthMonthPay'])->name('incentives.thirteenth-month-pay');
Route::get('/incentives/get-thirteenth-month-data', [IncentivesController::class, 'getThirteenthMonthData'])->name('incentives.get-thirteenth-month-data');
Route::post('/incentives/save-thirteenth-month', [IncentivesController::class, 'saveThirteenthMonth'])->name('incentives.save-thirteenth-month');
Route::get('/incentives/view-incentives/{id}', [IncentivesController::class, 'viewIncentives'])->name('incentives.view-incentives');
Route::get('/incentives/delete', [IncentivesController::class, 'delete'])->name('incentives.delete');
Route::get('/incentives/lock', [IncentivesController::class, 'lock'])->name('incentives.lock');
Route::get('/incentives/other-bonuses', [IncentivesController::class, 'otherBonuses'])->name('incentives.other-bonuses');
Route::get('/incentives/get-incentives-list', [IncentivesController::class, 'getIncentivesList'])->name('incentives.get-incentives-list');
Route::get('/incentives/get-custom-incentives-data', [IncentivesController::class, 'getCustomIncentivesData'])->name('incentives.get-custom-incentives-data');
Route::post('/incentives/save-custom-bonus', [IncentivesController::class, 'saveCustomBonus'])->name('incentives.save-custom-bonus');
Route::get('/incentives/year-end-bonuses', [IncentivesController::class, 'yearEndBonuses'])->name('incentives.year-end-bonuses');
Route::get('/incentives/get-year-end-incentives-data', [IncentivesController::class, 'getYearEndIncentivesData'])->name('incentives.get-year-end-incentives-data');
Route::get('/incentives/view-year-end-incentives/{id}', [IncentivesController::class, 'viewYearEndIncentives'])->name('incentives.view-year-end-incentives');
Route::get('/incentives/lock-year-end-incentives', [IncentivesController::class, 'lockYearEndIncentives'])->name('incentives.lock-year-end-incentives');
Route::get('/incentives/print-year-end-final/{id}', [IncentivesController::class, 'printYearEndFinal'])->name('incentives.print-year-end-final');
Route::get('/incentives/print-year-end-signatures/{id}', [IncentivesController::class, 'printYearEndSignatures'])->name('incentives.print-year-end-signatures');
Route::get('/incentives/print-year-end-fcb/{id}', [IncentivesController::class, 'printYearEndFCB'])->name('incentives.print-year-end-fcb');
Route::get('/incentives/download-year-end-fcb-template/{id}', [IncentivesController::class, 'downloadYearEndFCBTemplate'])->name('incentives.download-year-end-fcb-template');
Route::resource('incentives', IncentivesController::class);

Route::get('/bempcs/upload', [BempcController::class, 'upload'])->name('bempcs.upload');
Route::post('/bempcs/upload', [BempcController::class, 'processUpload'])->name('bempcs.process-upload');
Route::get('/bempcs/view-upload/{for}/{date}', [BempcController::class, 'viewUploads'])->name('bempcs.view-upload');
Route::get('/bempcs/delete', [BempcController::class, 'delete'])->name('bempcs.delete');
Route::resource('bempcs', BempcController::class);

Route::resource('incentiveDetails', App\Http\Controllers\IncentiveDetailsController::class);

Route::post('/leave-conversions/request-multiple', [LeaveConversionsController::class, 'requestMultiple'])->name('leaveConversions.request-multiple');
Route::get('/leave-conversions/my-approvals', [LeaveConversionsController::class, 'myApprovals'])->name('leaveConversions.my-approvals');
Route::get('/leave-conversions/approve', [LeaveConversionsController::class, 'approve'])->name('leaveConversions.approve');
Route::get('/leave-conversions/reject', [LeaveConversionsController::class, 'reject'])->name('leaveConversions.reject');
Route::get('/leave-conversions/approved-sl-and-vl', [LeaveConversionsController::class, 'approvedSLandVL'])->name('leaveConversions.approved-sl-and-vl');
Route::get('/leave-conversions/print-all', [LeaveConversionsController::class, 'printAll'])->name('leaveConversions.print-all');
Route::get('/leave-conversions/print-single/{id}', [LeaveConversionsController::class, 'printSingle'])->name('leaveConversions.print-single');
Route::get('/leave-conversions/mark-as-done', [LeaveConversionsController::class, 'markAsDone'])->name('leaveConversions.mark-as-done');
Route::get('/leave-conversions/mark-all-as-done', [LeaveConversionsController::class, 'markAllAsDone'])->name('leaveConversions.mark-all-as-done');
Route::get('/leave-conversions/get-leave-conversions-data', [LeaveConversionsController::class, 'getLeaveConversionsData'])->name('leaveConversions.get-leave-conversions-data');
Route::resource('leaveConversions', LeaveConversionsController::class);

Route::post('/leave-incentives_year_end_details/save-year-end', [IncentivesYearEndDetailsController::class, 'saveYearEndData'])->name('incentivesYearEndDetails.save-year-end');
Route::resource('incentivesYearEndDetails', IncentivesYearEndDetailsController::class);

Route::get('/attached_accounts/get-connected-accounts', [AttachedAccountsController::class, 'getConnectedAccounts'])->name('attachedAccounts.get-connected-accounts');
Route::resource('attachedAccounts', AttachedAccountsController::class);
Route::resource('payrollBillsAttachments', App\Http\Controllers\PayrollBillsAttachmentsController::class);
Route::resource('paidBills', App\Http\Controllers\PaidBillsController::class);
Route::resource('bills', App\Http\Controllers\BillsController::class);

Route::get('/employee-day-offs/get-by-employee', [EmployeeDayOffsController::class, 'getByEmployee'])->name('employeeDayOffs.get-by-employee');
Route::get('/employee-day-offs/remove', [EmployeeDayOffsController::class, 'remove'])->name('employeeDayOffs.remove');
Route::resource('employeeDayOffs', EmployeeDayOffsController::class);
Route::resource('s-m-s-notifications', App\Http\Controllers\SMSNotificationsController::class);

Route::get('/dependents/add-dependents', [DependentsController::class, 'addDependents'])->name('dependents.add-dependents');
Route::get('/dependents/get-dependents', [DependentsController::class, 'getDependents'])->name('dependents.get-dependents');
Route::get('/dependents/remove-dependent', [DependentsController::class, 'removeDependent'])->name('dependents.remove-dependent');
Route::resource('dependents', DependentsController::class);

Route::post('/travel_orders/create-order', [TravelOrdersController::class, 'createOrder'])->name('travelOrders.create-order');
Route::get('/travel_orders/my-approvals', [TravelOrdersController::class, 'myAprovals'])->name('travelOrders.my-approvals');
Route::get('/travel_orders/approve-ajax', [TravelOrdersController::class, 'approveAjax'])->name('travelOrders.approve-ajax');
Route::get('/travel_orders/get-travel-orders-ajax', [TravelOrdersController::class, 'getTravelOrdersAjax'])->name('travelOrders.get-travel-orders-ajax');
Route::resource('travelOrders', TravelOrdersController::class);

Route::resource('travelOrderEmployees', App\Http\Controllers\TravelOrderEmployeesController::class);
Route::resource('travelOrderDays', App\Http\Controllers\TravelOrderDaysController::class);
Route::resource('travel-order-signatories', App\Http\Controllers\TravelOrderSignatoriesController::class);