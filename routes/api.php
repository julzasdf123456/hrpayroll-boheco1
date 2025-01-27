<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Biometrics;
use App\Http\Controllers\API\SMSNotificationsAPI;
use App\Http\Controllers\API\AuthOut;
use App\Http\Controllers\API\EmployeeInfo;
use App\Http\Controllers\API\Leave;
use App\Http\Controllers\API\TripTicketsAPI;
use App\Http\Controllers\API\NotificationsAPI;

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

/**
 * EMPLOYEE DATA
 */
Route::get('get-employee-information', [EmployeeInfo::class, 'getEmployeeInformation']);
Route::get('get-attendance-data', [EmployeeInfo::class, 'getAttendanceData']);

/**
 * LEAVE
 */
Route::get('get-leave-signatories', [Leave::class, 'getLeaveSignatories']);
Route::post('post-leave', [Leave::class, 'postLeave']);
Route::get('get-all-leave', [Leave::class, 'getAllLeave']);
Route::post('delete-leave', [Leave::class, 'deleteLeave']);
Route::get('get-leave-credit-logs', [Leave::class, 'getLeaveCreditLogs']);
Route::get('get-leave', [Leave::class, 'getLeave']);

/**
 * Trip Tickets
 */
Route::get('get-trip-ticket-dependencies', [TripTicketsAPI::class, 'getTripTicketDependencies']);
Route::post('post-trip-ticket', [TripTicketsAPI::class, 'postTripTicket']);
Route::get('get-all-trip-tickets', [TripTicketsAPI::class, 'getAllTripTickets']);
Route::post('delete-trip-ticket', [TripTicketsAPI::class, 'deleteTripTicket']);

/**
 * Notifications
 */
Route::get('get-notifications', [NotificationsAPI::class, 'getNotifications']);
