<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveApplications;
use Illuminate\Support\Facades\Auth;
use App\Models\Notifications;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leaves = LeaveApplications::where('EmployeeId', Auth::user()->employee_id)->orderByDesc('created_at')
            ->limit(5)
            ->get();
            
        $info = Notifications::where('UserId', Auth::id())
            ->where('Type', 'INFO')
            ->limit(5)
            ->get();

        return view('home', [
            'leaves' => $leaves,
            'info' => $info,
        ]);
    }
}
