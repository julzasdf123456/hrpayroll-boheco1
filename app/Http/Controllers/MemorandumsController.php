<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMemorandumsRequest;
use App\Http\Requests\UpdateMemorandumsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\MemorandumsRepository;
use Illuminate\Http\Request;
use App\Models\IDGenerator;
use App\Models\Employees;
use App\Models\MemorandumEmployees;
use App\Models\SMSNotifications;
use App\Models\Memorandums;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Flash;

class MemorandumsController extends AppBaseController
{
    /** @var MemorandumsRepository $memorandumsRepository*/
    private $memorandumsRepository;

    public function __construct(MemorandumsRepository $memorandumsRepo)
    {
        $this->middleware('auth');
        $this->memorandumsRepository = $memorandumsRepo;
    }

    /**
     * Display a listing of the Memorandums.
     */
    public function index(Request $request)
    {
        $memorandums = $this->memorandumsRepository->paginate(10);

        return view('memorandums.index')
            ->with('memorandums', $memorandums);
    }

    /**
     * Show the form for creating a new Memorandums.
     */
    public function create()
    {
        return view('memorandums.create');
    }

    /**
     * Store a newly created Memorandums in storage.
     */
    public function store(CreateMemorandumsRequest $request)
    {
        $input = $request->all();
        $input['UserId'] = Auth::id();

        $memorandums = $this->memorandumsRepository->create($input);

        // insert selected employees first
        MemorandumEmployees::where('MemoId', $input['id'])->delete();
        $employees = $input['SelectedEmployees'];
        if (isset($employees) && $employees != null) {
            foreach($employees as $item) {
                if ($item != null) {
                    MemorandumEmployees::create([
                        'id' => IDGenerator::generateIDandRandString(),
                        'MemoId' => $input['id'],
                        'EmployeeId' => $item['id'],
                    ]);
                }
            }
        }

        // insert by department
        $depts = $input['Departments'];
        if (isset($depts) && $depts != null) {
            foreach($depts as $item) {
                if ($item == 'SUB-OFFICE') {
                    $employees = DB::table('Employees')
                        ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
                        ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
                        ->select('Employees.*',
                        )
                        ->where('Employees.OfficeDesignation', $item)
                        ->whereRaw("(EmploymentStatus IS NULL OR EmploymentStatus NOT IN ('Resigned', 'Retired'))")
                        ->get();
                } else {
                    $employees = DB::table('Employees')
                        ->leftJoin('EmployeesDesignations', 'Employees.Designation', '=', 'EmployeesDesignations.id')
                        ->leftJoin('Positions', 'Positions.id', '=', 'EmployeesDesignations.PositionId')
                        ->select('Employees.*',
                        )
                        ->where('Positions.Department', $item)
                        ->whereRaw("Employees.OfficeDesignation NOT IN ('" . $item . "') AND (EmploymentStatus IS NULL OR EmploymentStatus NOT IN ('Resigned', 'Retired'))")
                        ->get();
                }

                if ($employees != null) {
                    foreach ($employees as $emp) {
                        MemorandumEmployees::create([
                            'id' => IDGenerator::generateIDandRandString(),
                            'MemoId' => $input['id'],
                            'EmployeeId' => $emp->id,
                        ]);
                    }
                }
            }
        }

        // send sms
        if (isset($input['SendSMS']) && ($input['SendSMS'] == true | $input['SendSMS'] === 'true')) {
            $recipients = DB::table('MemorandumEmployees')
                ->leftJoin('Employees', 'MemorandumEmployees.EmployeeId', '=', 'Employees.id')
                ->whereRaw("MemorandumEmployees.MemoId='" . $input['id'] . "' AND ContactNumbers IS NOT NULL AND LEN(ContactNumbers) > 9")
                ->select('Employees.*', 'MemorandumEmployees.id AS MemoId')
                ->get();

            if ($recipients != null) {
                foreach ($recipients as $item) {
                    if ($item != null && $item->ContactNumbers != null) {
                        SMSNotifications::sendSMS($item->ContactNumbers, 
                            "HRS Memo Info\n\nHello " . $item->FirstName . ", \n\n" . $input['MemoRawText'] . "\n\nMemo No.: " . $input['MemoNumber'],
                            "HR-Memo SMS",
                            $item->MemoId
                        );
                    }
                }
            }
        }

        return response()->json($memorandums, 200);
    }

    /**
     * Display the specified Memorandums.
     */
    public function show($id)
    {
        $memorandums = $this->memorandumsRepository->find($id);

        if (empty($memorandums)) {
            Flash::error('Memorandums not found');

            return redirect(route('memorandums.index'));
        }

        return view('memorandums.show')->with('memorandums', $memorandums);
    }

    /**
     * Show the form for editing the specified Memorandums.
     */
    public function edit($id)
    {
        $memorandums = $this->memorandumsRepository->find($id);

        if (empty($memorandums)) {
            Flash::error('Memorandums not found');

            return redirect(route('memorandums.index'));
        }

        return view('memorandums.edit')->with('memorandums', $memorandums);
    }

    /**
     * Update the specified Memorandums in storage.
     */
    public function update($id, UpdateMemorandumsRequest $request)
    {
        $memorandums = $this->memorandumsRepository->find($id);

        if (empty($memorandums)) {
            Flash::error('Memorandums not found');

            return redirect(route('memorandums.index'));
        }

        $memorandums = $this->memorandumsRepository->update($request->all(), $id);

        Flash::success('Memorandums updated successfully.');

        return redirect(route('memorandums.index'));
    }

    /**
     * Remove the specified Memorandums from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $memorandums = $this->memorandumsRepository->find($id);

        if (empty($memorandums)) {
            Flash::error('Memorandums not found');

            return redirect(route('memorandums.index'));
        }

        $this->memorandumsRepository->delete($id);

        Flash::success('Memorandums deleted successfully.');

        return redirect(route('memorandums.index'));
    }

    public function createMemo(Request $request) {
        return view('/memorandums/create_memo', [

        ]);
    }

    public function searchMemo(Request $request) {
        $params = $request['params'];

        if (!isset($params)) {
            $data = DB::table('Memorandums')
                ->whereRaw("Status NOT IN('TRASH')")
                ->select('Memorandums.*', DB::raw("(SELECT COUNT(id) FROM MemorandumEmployees WHERE MemoId=Memorandums.id) AS Count"))
                ->orderByDesc('created_at')
                ->paginate(12);
        } else {
            $data = DB::table('Memorandums')
                ->whereRaw("Status NOT IN('TRASH')")
                ->whereRaw("(MemoNumber LIKE '%" . $params . "%' OR MemoTitle LIKE '%" . $params . "%')")
                ->select('Memorandums.*', DB::raw("(SELECT COUNT(id) FROM MemorandumEmployees WHERE MemoId=Memorandums.id) AS Count"))
                ->orderByDesc('created_at')
                ->paginate(12);
        }

        return response()->json($data, 200);
    }

    public function printMemo($memoNo, $printEmployees) {
        $memo = Memorandums::find($memoNo);
        if ($memo != null) {
            $employees = DB::table('MemorandumEmployees')
                ->leftJoin('Employees', 'MemorandumEmployees.EmployeeId', '=', 'Employees.id')
                ->whereRaw("MemorandumEmployees.MemoId='" . $memo->id . "'")
                ->select('Employees.*', 'MemorandumEmployees.id AS MemoId')
                ->orderBy('Employees.LastName')
                ->get();
        } else {
            $employees = [];
        }

        return view('/memorandums/print_memo', [
            'memo' => $memo,
            'employees' => $employees,
            'printOption' => $printEmployees,
        ]);
    }

    public function trashMemo(Request $request) {
        $id = $request['id'];

        $memo = Memorandums::find($id);

        if ($memo != null) {
            $memo->Status = 'TRASH';
            $memo->UserId = Auth::id();
            $memo->save();
        }

        return response()->json($memo, 200);
    }

    public function getMemoRespondents(Request $request) {
        $id = $request['id'];

        $data = DB::table('MemorandumEmployees')
            ->leftJoin('Employees', 'MemorandumEmployees.EmployeeId', '=', 'Employees.id')
            ->whereRaw("MemorandumEmployees.MemoId='" . $id . "'")
            ->select('Employees.*', 'MemorandumEmployees.id AS MemoId')
            ->orderBy('LastName')
            ->get();

        return response()->json($data, 200);
    }
}
