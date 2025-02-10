<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\IDGenerator;
use App\Models\Users;
use App\Models\Employees;
use App\Models\SMSNotifications;

class Posts extends Controller {
    public function getPosts(Request $request) {
        $posts = DB::table('Posts')
            ->leftJoin('users', 'Posts.PostUserId', '=', 'users.id')
            ->leftJoin('Employees', 'users.employee_id', '=', 'Employees.id')
            ->leftJoin('EmployeesDesignations', 'EmployeesDesignations.id', '=', 'Employees.Designation')
            ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
            ->whereRaw("Posts.Privacy='Show to Everyone'")
            ->select(
                'Posts.*', 
                'Employees.FirstName', 
                'Employees.LastName', 
                'Positions.Position',
                'Employees.ProfilePicture',
                'Employees.id AS EmployeeId',
                'EmployeesDesignations.Status as EmploymentStatus',
            )
            ->orderByDesc('Posts.created_at')
            ->simplePaginate(15);

        return response()->json($posts->items(), 200);
    }
}