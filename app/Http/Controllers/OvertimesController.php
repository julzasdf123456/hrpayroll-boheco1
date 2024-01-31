<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOvertimesRequest;
use App\Http\Requests\UpdateOvertimesRequest;
use App\Repositories\OvertimesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Employees;
use App\Models\IDGenerator;
use App\Models\Overtimes;
use App\Models\OvertimeSignatories;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Flash;
use Response;

class OvertimesController extends AppBaseController
{
    /** @var  OvertimesRepository */
    private $overtimesRepository;

    public function __construct(OvertimesRepository $overtimesRepo)
    {
        $this->middleware('auth');
        $this->overtimesRepository = $overtimesRepo;
    }

    /**
     * Display a listing of the Overtimes.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $overtimes = $this->overtimesRepository->all();

        return view('overtimes.index')
            ->with('overtimes', $overtimes);
    }

    /**
     * Show the form for creating a new Overtimes.
     *
     * @return Response
     */
    public function create()
    {
        return view('overtimes.create', [
            'employees' => Employees::orderBy('FirstName')->get(),
        ]);
    }

    /**
     * Store a newly created Overtimes in storage.
     *
     * @param CreateOvertimesRequest $request
     *
     * @return Response
     */
    public function store(CreateOvertimesRequest $request)
    {
        $input = $request->all();

        $overtimes = $this->overtimesRepository->create($input);

        Flash::success('Overtimes saved successfully.');

        return redirect(route('overtimes.index'));
    }

    /**
     * Display the specified Overtimes.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $overtimes = $this->overtimesRepository->find($id);

        if (empty($overtimes)) {
            Flash::error('Overtimes not found');

            return redirect(route('overtimes.index'));
        }

        return view('overtimes.show')->with('overtimes', $overtimes);
    }

    /**
     * Show the form for editing the specified Overtimes.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $overtimes = $this->overtimesRepository->find($id);

        if (empty($overtimes)) {
            Flash::error('Overtimes not found');

            return redirect(route('overtimes.index'));
        }

        return view('overtimes.edit', [
            'employees' => Employees::all(),
        ])->with('overtimes', $overtimes);
    }

    /**
     * Update the specified Overtimes in storage.
     *
     * @param int $id
     * @param UpdateOvertimesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateOvertimesRequest $request)
    {
        $overtimes = $this->overtimesRepository->find($id);

        if (empty($overtimes)) {
            Flash::error('Overtimes not found');

            return redirect(route('overtimes.index'));
        }

        $overtimes = $this->overtimesRepository->update($request->all(), $id);

        Flash::success('Overtimes updated successfully.');

        return redirect(route('overtimes.index'));
    }

    /**
     * Remove the specified Overtimes from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $overtimes = $this->overtimesRepository->find($id);

        if (empty($overtimes)) {
            Flash::error('Overtimes not found');

            return redirect(route('overtimes.index'));
        }

        $this->overtimesRepository->delete($id);

        Flash::success('Overtimes deleted successfully.');

        return redirect(route('overtimes.index'));
    }

    public function save(Request $request) {
        $data = $request['Data'];

        $i = 0;
        foreach($data as $item) {
            $id = IDGenerator::generateID() . $item['Index'] . $i;

            // save overtime
            $overtime = new Overtimes;
            $overtime->id = $id;
            $overtime->EmployeeId = $item['EmployeeId'];
            $overtime->DateOfOT = $item['StartDate'];
            $overtime->DateOTEnded = $item['EndDate'];
            $overtime->From = $item['StartTime'];
            $overtime->To = $item['EndTime'];
            $overtime->Multiplier = $item['Multiplier'];
            $overtime->TypeOfDay = $item['TypeOfLeave'];
            $overtime->PurposeOfOT = $item['Purpose'];
            $overtime->TotalHours = $item['TotalHours'];
            $overtime->MaxHourThreshold = $item['MaxHours'];
            $overtime->UserId = Auth::id();
            $overtime->save();

            // FIND NEXT SIGNATORY
            $signatories = Employees::getSupers($item['EmployeeId'], []);

            $rank = 1;
            foreach($signatories as $signatory) {
                $sigs = new OvertimeSignatories;
                $sigs->id = IDGenerator::generateIDandRandString() . $rank;
                $sigs->OvertimeId = $id;
                $sigs->EmployeeId = $signatory['id'];
                $sigs->Rank = $rank;
                $sigs->save();

                $rank++;
            }
            
            $i++;
        }

        return response()->json($data);
    }

    public function myApprovals(Request $request) {
        $overtimes = DB::table('OvertimeSignatories')
            ->leftJoin('Overtimes', 'OvertimeSignatories.OvertimeId', '=', 'Overtimes.id')
            ->leftJoin('users', 'Overtimes.UserId', '=', 'users.id')
            ->leftJoin('Employees', 'Overtimes.EmployeeId', '=', 'Employees.id')
            ->whereRaw("OvertimeSignatories.EmployeeId='" . Auth::id() . "' AND (Overtimes.Status IS NULL OR Overtimes.Status='FILED') AND OvertimeSignatories.id IN 
                (SELECT TOP 1 x.id FROM OvertimeSignatories x WHERE x.OvertimeId=OvertimeSignatories.OvertimeId AND x.Status IS NULL ORDER BY x.Rank)")
            ->select('Overtimes.*',
                'OvertimeSignatories.id AS SignatoryId',
                'Employees.FirstName',
                'Employees.MiddleName',
                'Employees.LastName',
                'Employees.Suffix',
                'Employees.BiometricsUserId',
                'users.name')
            ->get();

        return view('/overtimes/my_approvals', [
            'overtimes' => $overtimes,
        ]);
    }

    public function getOvertimeAjax(Request $request) {
        $overtime = DB::table('Overtimes')
            ->leftJoin('Employees', 'Overtimes.EmployeeId', '=', 'Employees.id')
            ->leftJoin('users', 'Overtimes.UserId', '=', 'users.id')
            ->whereRaw("Overtimes.id='" . $request['id'] . "'")
            ->select('Overtimes.*',
                'Employees.FirstName',
                'Employees.MiddleName',
                'Employees.LastName',
                'Employees.Suffix',
                'Employees.BiometricsUserId',
                'users.name',
            )
            ->first();

        $signatories = DB::table('OvertimeSignatories')
            ->leftJoin('users', 'OvertimeSignatories.EmployeeId', '=', 'users.id')
            ->select(
                'OvertimeSignatories.*',
                'users.name'
            )
            ->where('OvertimeId', $request['id'])
            ->orderBy('Rank')
            ->get();

        $overtime->Logs = $signatories;

        return response()->json($overtime);
    }

    public function approve(Request $request) {
        $id = $request['id'];

        $overtime = Overtimes::find($id);

        if ($overtime != null) {
            // GET CURRENT APPROVER
            $currentSignatoryApprover = OvertimeSignatories::where('OvertimeId', $id)
                ->where('EmployeeId', Auth::id())
                ->first();

            if ($currentSignatoryApprover != null) {
                $currentSignatoryApprover->Status='APPROVED';
                $currentSignatoryApprover->save();

                // FIND NEXT SIGNATORY
                $nextSignatory = DB::table('OvertimeSignatories')
                    ->whereRaw("OvertimeId='" . $id . "' AND (Status IS NULL OR Status NOT IN('REMOVED')) AND Rank > " . $currentSignatoryApprover->Rank)
                    ->orderBy('Rank')
                    ->first();
                
                
                // CHECK IF ALL SIGNATORIES ARE COMPLETED
                if ($nextSignatory == null) {
                    // UPDATE STATUS IF COMPLETED
                    $overtime->Status='APPROVED';
                    $overtime->save();
                }
            }
        }

        return response()->json($overtime, 200);
    }

    public function reject(Request $request) {
        $id = $request['id'];
        $notes = $request['Notes'];

        $overtime = Overtimes::find($id);

        if ($overtime != null) {
            // GET CURRENT APPROVER
            $currentSignatoryApprover = OvertimeSignatories::where('OvertimeId', $id)
                ->where('EmployeeId', Auth::id())
                ->first();

            if ($currentSignatoryApprover != null) {
                $currentSignatoryApprover->Status = 'REJECTED';
                $currentSignatoryApprover->Notes = $notes;
                $currentSignatoryApprover->save();

                // FIND NEXT SIGNATORY
                $nextSignatory = DB::table('OvertimeSignatories')
                    ->whereRaw("OvertimeId='" . $id . "' AND (Status IS NULL OR Status NOT IN('REMOVED')) AND Rank > " . $currentSignatoryApprover->Rank)
                    ->orderBy('Rank')
                    ->get();
                
                foreach ($nextSignatory as $item) {
                    OvertimeSignatories::where('id', $item->id)
                        ->update(['Status' => 'REJECTED']);
                }
                
                // UPDATE REJECTED
                $overtime->Status = 'REJECTED';
                $overtime->Notes = $notes;
                $overtime->save();
            }
        }

        return response()->json($overtime, 200);
    }
}
