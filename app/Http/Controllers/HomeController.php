<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveApplications;
use Illuminate\Support\Facades\Auth;
use App\Models\Notifications;
use Illuminate\Support\Facades\Http;

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
        if (Auth::user()->hasPermissionTo('admin ui')) {
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
        } else {
            return redirect(route('users.my-account-index', [Auth::user()->employee_id]));
        }
    }

    public function reeve() {
        return view('reeve', [

        ]);
    }

    public function chatReeve(Request $request) {
        $query = $request['prompt'];
        $apiKey = env('REEVE_API');

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $apiKey,
            ])->post('https://api.openai.com/v1/chat/completions', [
                'messages' => [
                    [
                        "role" => "user", 
                        "content" => $query
                    ]
                ],
                // 'max_tokens' => 1500, // Customize parameters as needed
                'model' => 'gpt-3.5-turbo',
            ]);

            // echo $response;
            // $data = $response->json();
            return response()->json(['generatedText' => $response['choices'][0]['message']['content']]);
        } catch (\Exception $e) {
            // echo $e->getMessage();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
