<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateIncentivesYearEndDetailsRequest;
use App\Http\Requests\UpdateIncentivesYearEndDetailsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\IncentivesYearEndDetailsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Employees;
use App\Models\Incentives;
use App\Models\IncentiveDetails;
use App\Models\IDGenerator;
use App\Models\UserFootprints;
use App\Models\EmployeeIncentiveAnnualProjections;
use App\Models\IncentivesAnnualProjection;
use App\Models\IncentivesYearEndDetails;
use Flash;

class IncentivesYearEndDetailsController extends AppBaseController
{
    /** @var IncentivesYearEndDetailsRepository $incentivesYearEndDetailsRepository*/
    private $incentivesYearEndDetailsRepository;

    public function __construct(IncentivesYearEndDetailsRepository $incentivesYearEndDetailsRepo)
    {
        $this->middleware('auth');
        $this->incentivesYearEndDetailsRepository = $incentivesYearEndDetailsRepo;
    }

    /**
     * Display a listing of the IncentivesYearEndDetails.
     */
    public function index(Request $request)
    {
        $incentivesYearEndDetails = $this->incentivesYearEndDetailsRepository->paginate(10);

        return view('incentives_year_end_details.index')
            ->with('incentivesYearEndDetails', $incentivesYearEndDetails);
    }

    /**
     * Show the form for creating a new IncentivesYearEndDetails.
     */
    public function create()
    {
        return view('incentives_year_end_details.create');
    }

    /**
     * Store a newly created IncentivesYearEndDetails in storage.
     */
    public function store(CreateIncentivesYearEndDetailsRequest $request)
    {
        $input = $request->all();

        $incentivesYearEndDetails = $this->incentivesYearEndDetailsRepository->create($input);

        Flash::success('Incentives Year End Details saved successfully.');

        return redirect(route('incentivesYearEndDetails.index'));
    }

    /**
     * Display the specified IncentivesYearEndDetails.
     */
    public function show($id)
    {
        $incentivesYearEndDetails = $this->incentivesYearEndDetailsRepository->find($id);

        if (empty($incentivesYearEndDetails)) {
            Flash::error('Incentives Year End Details not found');

            return redirect(route('incentivesYearEndDetails.index'));
        }

        return view('incentives_year_end_details.show')->with('incentivesYearEndDetails', $incentivesYearEndDetails);
    }

    /**
     * Show the form for editing the specified IncentivesYearEndDetails.
     */
    public function edit($id)
    {
        $incentivesYearEndDetails = $this->incentivesYearEndDetailsRepository->find($id);

        if (empty($incentivesYearEndDetails)) {
            Flash::error('Incentives Year End Details not found');

            return redirect(route('incentivesYearEndDetails.index'));
        }

        return view('incentives_year_end_details.edit')->with('incentivesYearEndDetails', $incentivesYearEndDetails);
    }

    /**
     * Update the specified IncentivesYearEndDetails in storage.
     */
    public function update($id, UpdateIncentivesYearEndDetailsRequest $request)
    {
        $incentivesYearEndDetails = $this->incentivesYearEndDetailsRepository->find($id);

        if (empty($incentivesYearEndDetails)) {
            Flash::error('Incentives Year End Details not found');

            return redirect(route('incentivesYearEndDetails.index'));
        }

        $incentivesYearEndDetails = $this->incentivesYearEndDetailsRepository->update($request->all(), $id);

        Flash::success('Incentives Year End Details updated successfully.');

        return redirect(route('incentivesYearEndDetails.index'));
    }

    /**
     * Remove the specified IncentivesYearEndDetails from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $incentivesYearEndDetails = $this->incentivesYearEndDetailsRepository->find($id);

        if (empty($incentivesYearEndDetails)) {
            Flash::error('Incentives Year End Details not found');

            return redirect(route('incentivesYearEndDetails.index'));
        }

        $this->incentivesYearEndDetailsRepository->delete($id);

        Flash::success('Incentives Year End Details deleted successfully.');

        return redirect(route('incentivesYearEndDetails.index'));
    }

    public function saveYearEndData(Request $request) {
        $data = $request['Data'];
        $employeeType = $request['EmployeeType'];
        $department = $request['Department'];
        $incentiveName = $request['IncentiveName'];

        $incentive = Incentives::where('IncentiveName', $incentiveName)
            ->where('Year', date('Y'))
            ->first();

        if ($incentive != null) {
            $id = $incentive->id;

            $incentive->UserId = Auth::id();
        } else {
            $id = IDGenerator::generateID();

            $incentive = new Incentives;
            $incentive->id = $id;
            $incentive->IncentiveName = $incentiveName;
            $incentive->UserId = Auth::id();
            $incentive->Year = date('Y');
            $incentive->ReleaseType = 'Full';
        }
        $incentive->save();

        foreach ($data as $item) {
            $iDetails = IncentivesYearEndDetails::where('IncentivesId', $id)
                ->where('EmployeeId', $item['id'])
                ->first();

            if ($iDetails == null) {
                // create new
                $iDetails = new IncentivesYearEndDetails;
                $iDetails->id = IDGenerator::generateIDandRandString();
                $iDetails->IncentivesId = $id;
                $iDetails->EmployeeId = $item['id'];
            }

            $iDetails->FourteenthMonthPay = $item['FourteenthMonth'];
            $iDetails->ThirteenthMonthDifferential = $item['ThirteenthMonthDifferential'];
            $iDetails->CashGift = $item['CashGift'];
            $iDetails->VacationLeave = $item['VL'];
            $iDetails->SickLeave = $item['SL'];
            $iDetails->LoyaltyAward = $item['LoyaltyAward'];
            $iDetails->SubTotal = $item['SubTotal'];
            $iDetails->AROthers = $item['AROthers'];
            $iDetails->BEMPC = $item['BEMPC'];
            $iDetails->WithholdingTaxes = 0;
            $iDetails->NetPay = $item['NetPay'];
            $iDetails->UserId = Auth::id();
            $iDetails->EmployeeType = $employeeType;
            $iDetails->Department = $department;
            $iDetails->save();
        }

        UserFootprints::logSource('Generated Year-end Incentives for ' . date('Y'), 
            "Submitted " . $department . " Year-end Incentives draft for " . date('Y') . " for auditing.",
            $id); 

        return response()->json($incentive, 200);
    }
}
