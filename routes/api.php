<?php

use App\Http\Controllers\TripTicketsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Biometrics;
use App\Http\Controllers\API\SMSNotificationsAPI;
use App\Http\Controllers\API\AuthOut;
use App\Http\Controllers\API\EmployeeInfo;
use App\Http\Controllers\API\Leave;
use App\Http\Controllers\API\TripTicketsAPI;
use App\Http\Controllers\API\NotificationsAPI;
use App\Http\Controllers\API\Offsets;
use App\Http\Controllers\API\AttendanceConfirmationAPI;
use App\Http\Controllers\API\Posts;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * BIOMETRICS
 */
Route::get('get-users', [Biometrics::class, 'getUsers']);
Route::get('get-attendance', [Biometrics::class, 'getAttendance']);
Route::get('get-version', [Biometrics::class, 'getVersion']);

/**
 * SMS NOTIFICATIONS
 */
Route::get('get-random-notification', [SMSNotificationsAPI::class, 'getRandomNotification']);
Route::get('update-sms', [SMSNotificationsAPI::class, 'updateSMSNotification']);
Route::get('insert-bio-attendance', [SMSNotificationsAPI::class, 'insertSMSNotifForBiometricAttendance']);

/**
 * AUTH
 */
Route::post('login', [AuthOut::class, 'login']);
Route::get('check-employee-id', [AuthOut::class, 'checkEmployeeId']);
Route::get('verify-otp', [AuthOut::class, 'verifyOTP']);
Route::get('resend-otp', [AuthOut::class, 'resendOTP']);
Route::post('reset-password', [AuthOut::class, 'resetPassword']);

/**
 * EMPLOYEE DATA
 */
Route::get('get-employee-information', [EmployeeInfo::class, 'getEmployeeInformation']);
Route::get('get-attendance-data', [EmployeeInfo::class, 'getAttendanceData']);
Route::get('get-signatories', [EmployeeInfo::class, 'getSignatories']);
Route::get('get-my-approvals', [EmployeeInfo::class, 'getMyApprovals']);
Route::get('get-employee-designations', [EmployeeInfo::class, 'getEmployeeDesignations']);
Route::post('upload-profile-image', [EmployeeInfo::class, 'uploadProfileImage']);

/**
 * LEAVE
 */
Route::get('get-leave-signatories', [Leave::class, 'getLeaveSignatories']);
Route::post('post-leave', [Leave::class, 'postLeave']);
Route::get('get-all-leave', [Leave::class, 'getAllLeave']);
Route::post('delete-leave', [Leave::class, 'deleteLeave']);
Route::get('get-leave-credit-logs', [Leave::class, 'getLeaveCreditLogs']);
Route::get('get-leave', [Leave::class, 'getLeave']);
Route::post('approve-leave', [Leave::class, 'approveLeave']);
Route::post('reject-leave', [Leave::class, 'rejectLeave']);

/**
 * Trip Tickets
 */
Route::get('get-trip-ticket-dependencies', [TripTicketsAPI::class, 'getTripTicketDependencies']);
Route::post('post-trip-ticket', [TripTicketsAPI::class, 'postTripTicket']);
Route::get('get-all-trip-tickets', [TripTicketsAPI::class, 'getAllTripTickets']);
Route::post('delete-trip-ticket', [TripTicketsAPI::class, 'deleteTripTicket']);
Route::get('get-tt', [TripTicketsAPI::class, 'getTT']);
Route::post('approve-tt', [TripTicketsAPI::class, 'approveTripTicket']);
Route::post('reject-tt', [TripTicketsAPI::class, 'rejectTripTicket']);
Route::post('request-grs', [TripTicketsAPI::class, 'requestGRS']);

/**
 * Notifications
 */
Route::get('get-notifications', [NotificationsAPI::class, 'getNotifications']);
Route::post('mark-as-read', [NotificationsAPI::class, 'markAsRead']);
Route::post('mark-all-as-read', [NotificationsAPI::class, 'markAllAsRead']);

/**
 * Offsets
 */
Route::post('post-offset', [Offsets::class, 'postOffset']);
Route::get('get-offset', [Offsets::class, 'getOffset']);
Route::post('approve-offset', [Offsets::class, 'approveOffset']);
Route::post('reject-offset', [Offsets::class, 'rejectOffset']);
Route::get('get-all-offsets', [Offsets::class, 'getAllOffsets']);
Route::post('delete-offset', [Offsets::class, 'deleteOffset']);

/**
 * Attendance Confirmation
 */
Route::post('post-attendance-confirmation', [AttendanceConfirmationAPI::class, 'postAttendanceConfirmation']);
Route::get('get-attendance-confirmation', [AttendanceConfirmationAPI::class, 'getAttendanceConfirmation']);
Route::post('approve-attendance-confirmation', [AttendanceConfirmationAPI::class, 'approveAttendanceConfirmation']);
Route::get('get-all-attendance-confirmations', [AttendanceConfirmationAPI::class, 'getAllAttendanceConfirmations']);
Route::post('delete-attendance-confirmation', [AttendanceConfirmationAPI::class, 'deleteAttendanceConfirmation']);

/**
 * Feed and Posts
 */
Route::get('get-posts', [Posts::class, 'getPosts']);


// for the security guards api BY DOMZ
Route::post('auth/login', [AuthOut::class, "loginUser"]);
Route::post('auth/register', [AuthOut::class, "registerUser"]);

Route::prefix("user")->middleware("auth:sanctum")->group(function () {
    Route::get("verify", [AuthOut::class, "verifyUser"]);
});


// Route::get("trip-tickets/today", [TripTicketsAPI::class, "getAllTodaysTickets"]);
// Route::get("trip-tickets/all", [TripTicketsAPI::class, "getTripTickets"]);

Route::prefix("trip-tickets")->middleware("auth:sanctum")->group(function () {
    Route::get("today", [TripTicketsAPI::class, "getAllTodaysTickets"]);
    Route::get("all", [TripTicketsAPI::class, "getTripTickets"]);
    Route::get("", [TripTicketsAPI::class, "getTripTicket"]);
});

Route::prefix("trip-logs")->middleware("auth:sanctum")->group(function () {
    Route::post("/approve", [TripTicketsAPI::class, "approveTripLog"]);
    Route::get("/{id}", [TripTicketsAPI::class,"getTripLogs"]);
});