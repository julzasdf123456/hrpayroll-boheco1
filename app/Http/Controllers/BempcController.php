<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBempcRequest;
use App\Http\Requests\UpdateBempcRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\BempcRepository;
use Illuminate\Http\Request;
use App\Models\Bempc;
use App\Imports\BEMPCUploads;
use App\Models\IncentivesAnnualProjection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Flash;

class BempcController extends AppBaseController
{
    /** @var BempcRepository $bempcRepository*/
    private $bempcRepository;

    public function __construct(BempcRepository $bempcRepo)
    {
        $this->middleware('auth');
        $this->bempcRepository = $bempcRepo;
    }

    /**
     * Display a listing of the Bempc.
     */
    public function index(Request $request)
    {
        $bempcs = DB::table('BEMPC')
            ->select(
                'DeductionFor',
                'DeductionSchedule',
                DB::raw("TRY_CAST(created_at AS DATE) AS DateCreated"),
                DB::raw("COUNT(id) AS Count"),
                DB::raw("SUM(Amount) AS Total"),
            )
            ->groupByRaw('DeductionFor, DeductionSchedule, TRY_CAST(created_at AS DATE)')
            ->get();

        return view('bempcs.index')
            ->with('bempcs', $bempcs);
    }

    /**
     * Show the form for creating a new Bempc.
     */
    public function create()
    {
        return view('bempcs.create');
    }

    /**
     * Store a newly created Bempc in storage.
     */
    public function store(CreateBempcRequest $request)
    {
        $input = $request->all();

        $bempc = $this->bempcRepository->create($input);

        Flash::success('Bempc saved successfully.');

        return redirect(route('bempcs.index'));
    }

    /**
     * Display the specified Bempc.
     */
    public function show($id)
    {
        $bempc = $this->bempcRepository->find($id);

        if (empty($bempc)) {
            Flash::error('Bempc not found');

            return redirect(route('bempcs.index'));
        }

        return view('bempcs.show')->with('bempc', $bempc);
    }

    /**
     * Show the form for editing the specified Bempc.
     */
    public function edit($id)
    {
        $bempc = $this->bempcRepository->find($id);

        if (empty($bempc)) {
            Flash::error('Bempc not found');

            return redirect(route('bempcs.index'));
        }

        return view('bempcs.edit')->with('bempc', $bempc);
    }

    /**
     * Update the specified Bempc in storage.
     */
    public function update($id, UpdateBempcRequest $request)
    {
        $bempc = $this->bempcRepository->find($id);

        if (empty($bempc)) {
            Flash::error('Bempc not found');

            return redirect(route('bempcs.index'));
        }

        $bempc = $this->bempcRepository->update($request->all(), $id);

        Flash::success('Bempc updated successfully.');

        return redirect(route('bempcs.index'));
    }

    /**
     * Remove the specified Bempc from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $bempc = $this->bempcRepository->find($id);

        if (empty($bempc)) {
            Flash::error('Bempc not found');

            return redirect(route('bempcs.index'));
        }

        $this->bempcRepository->delete($id);

        // Flash::success('Bempc deleted successfully.');

        // return redirect(route('bempcs.index'));
        return response()->json('ok', 200);
    }

    public function upload(Request $request) {
        return view('/bempcs/upload', [
            'bonuses' => IncentivesAnnualProjection::whereNotIn('Incentive', ['Rice and Laundry'])->where('Year', date('Y'))->get(),
        ]);
    }

    public function processUpload(Request $request) {
        $file = $request->file('file');
        $for = $request['For'];
        $incentives = $request['Incentives'];
        $payrollSchedule = $request['PayrollSchedule'];

        if ($for === 'Bonus') {
            $deductionFor = $incentives;
            $payrollSchedule = null;
        } else {
            $deductionFor = 'Payroll';
        }

        $fileUpload = new BEMPCUploads($deductionFor, Auth::id(), $payrollSchedule);
        Excel::import($fileUpload, $file);

        Flash::success('File uploaded succcessfully!');

        return redirect(route('bempcs.index'));
    }

    public function viewUploads($for, $date) {
        return view('/bempcs/view_uploads', [
            'for' => urldecode($for),
            'date' => $date,
            'data' => DB::table('BEMPC')
                ->leftJoin('Employees', 'BEMPC.EmployeeId', '=', 'Employees.id')
                ->select(
                    'FirstName',
                    'MiddleName',
                    'LastName',
                    'Suffix',
                    'BEMPC.*'
                )
                ->whereRaw("DeductionFor='" . urldecode($for) . "' AND TRY_CAST(BEMPC.created_at AS DATE)='" . $date . "'")
                ->orderBy('LastName')
                ->get(),
        ]);
    }

    public function delete(Request $request) {
        $for = $request['For'];
        $date = $request['Date'];

        Bempc::where('DeductionFor', $for)
            ->whereRaw("TRY_CAST(created_at AS DATE)='" . $date . "'")
            ->delete();

        return response()->json('ok', 200);
    }
}
