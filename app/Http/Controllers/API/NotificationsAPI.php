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
        $type = $request['Type'];
        $search = $request['Search'];

        if ($search != null) {
            if ($type != null) {
                if ($type === 'Unread') {
                    $data =  Notifications::whereRaw("UserId='" . $userId . "' AND Status='UNREAD' AND Content LIKE '%" . $search . "%'")
                        ->orderByDesc('created_at')
                        ->simplePaginate(15);
                } else {
                    $data =  Notifications::whereRaw("UserId='" . $userId . "' AND Status='READ' AND Content LIKE '%" . $search . "%'")
                        ->orderByDesc('created_at')
                        ->simplePaginate(15);
                }
            } else {
                $data =  Notifications::whereRaw("UserId='" . $userId . "' AND Content LIKE '%" . $search . "%'")
                    ->orderByDesc('created_at')
                    ->simplePaginate(15);
            }
        } else {
            if ($type != null) {
                if ($type === 'Unread') {
                    $data =  Notifications::whereRaw("UserId='" . $userId . "' AND Status='UNREAD'")
                        ->orderByDesc('created_at')
                        ->simplePaginate(15);
                } else {
                    $data =  Notifications::whereRaw("UserId='" . $userId . "' AND Status='READ'")
                        ->orderByDesc('created_at')
                        ->simplePaginate(15);
                }
            } else {
                $data =  Notifications::whereRaw("UserId='" . $userId . "'")
                    ->orderByDesc('created_at')
                    ->simplePaginate(15);
            }
        }
        

        return response()->json($data->items(), 200);
    }

    public function markAsRead(Request $request) {
        $id = $request['id'];

        Notifications::where('id', $id)
            ->update(['Status' => 'READ']);

        return response()->json('marked', 200);
    }

    public function markAllAsRead(Request $request) {
        $userId = $request['UserId'];

        Notifications::where('UserId', $userId)
            ->update(['Status' => 'READ']);

        return response()->json('marked as unread', 200);
    }
}