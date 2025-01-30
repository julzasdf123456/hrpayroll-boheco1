<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\IDGenerator;
use App\Models\Employees;
use App\Models\Notifications;
use App\Models\Users;
use App\Models\Vehicles;

class NotificationsAPI extends Controller {
    public function getNotifications(Request $request) {
        $userId = $request['UserId'];

        $data =  Notifications::whereRaw("UserId='" . $userId . "'")
            ->orderByDesc('created_at')
            ->simplePaginate(15);

        return response()->json($data->items(), 200);
    }

    public function markAsRead(Request $request) {
        $id = $request['id'];

        Notifications::where('id', $id)
            ->update(['Status' => 'READ']);

        return response()->json('marked', 200);
    }
}