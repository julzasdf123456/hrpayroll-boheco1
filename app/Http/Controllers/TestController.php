<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRankingsRequest;
use App\Http\Requests\UpdateRankingsRequest;
use App\Repositories\RankingsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class TestController extends AppBaseController
{
    public function __construct(RankingsRepository $rankingsRepo)
    {
        $this->rankingsRepository = $rankingsRepo;
    }

    public function itExam(Request $request) {
        return view('/test/it_exam');
    }
    
    public function accountView($accountNo) {
        return view('/test/account_view', ['accountNo' => $accountNo]);
    }
}