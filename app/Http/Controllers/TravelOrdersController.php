<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTravelOrdersRequest;
use App\Http\Requests\UpdateTravelOrdersRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\TravelOrdersRepository;
use Illuminate\Http\Request;
use App\Models\Employees;
use App\Models\TravelOrders;
use App\Models\IDGenerator;
use App\Models\TravelOrderEmployees;
use App\Models\TravelOrderDays;
use App\Models\TravelOrderSignatories;
use App\Models\SMSNotifications;
use App\Models\Users;
use App\Models\Notifications;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Flash;

class TravelOrdersController extends AppBaseController
{
    /** @var TravelOrdersRepository $travelOrdersRepository*/
    private $travelOrdersRepository;

    public function __construct(TravelOrdersRepository $travelOrdersRepo)
    {
        $this->middleware('auth');
        $this->travelOrdersRepository = $travelOrdersRepo;
    }

    /**
     * Display a listing of the TravelOrders.
     */
    public function index(Request $request)
    {
        $travelOrders = $this->travelOrdersRepository->paginate(10);

        return view('travel_orders.index')
            ->with('travelOrders', $travelOrders);
    }

    /**
     * Show the form for creating a new TravelOrders.
     */
    public function create()
    {
        return view('travel_orders.create', [
            'employees' => Employees::orderBy('LastName')->get(),
        ]);
    }

    /**
     * Store a newly created TravelOrders in storage.
     */
    public function store(CreateTravelOrdersRequest $request)
    {
        $input = $request->all();

        $travelOrders = $this->travelOrdersRepository->create($input);

        Flash::success('Travel Orders saved successfully.');

        return redirect(route('travelOrders.index'));
    }

    /**
     * Display the specified TravelOrders.
     */
    public function show($id)
    {
        $travelOrders = $this->travelOrdersRepository->find($id);

        if (empty($travelOrders)) {
            Flash::error('Travel Orders not found');

            return redirect(route('travelOrders.index'));
        }

        return view('travel_orders.show')->with('travelOrders', $travelOrders);
    }

    /**
     * Show the form for editing the specified TravelOrders.
     */
    public function edit($id)
    {
        $travelOrders = $this->travelOrdersRepository->find($id);

        if (empty($travelOrders)) {
            Flash::error('Travel Orders not found');

            return redirect(route('travelOrders.index'));
        }

        return view('travel_orders.edit')->with('travelOrders', $travelOrders);
    }

    /**
     * Update the specified TravelOrders in storage.
     */
    public function update($id, UpdateTravelOrdersRequest $request)
    {
        $travelOrders = $this->travelOrdersRepository->find($id);

        if (empty($travelOrders)) {
            Flash::error('Travel Orders not found');

            return redirect(route('travelOrders.index'));
        }

        $travelOrders = $this->travelOrdersRepository->update($request->all(), $id);

        Flash::success('Travel Orders updated successfully.');

        return redirect(route('travelOrders.index'));
    }

    /**
     * Remove the specified TravelOrders from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $travelOrders = $this->travelOrdersRepository->find($id);

        if (empty($travelOrders)) {
            Flash::error('Travel Orders not found');

            return redirect(route('travelOrders.index'));
        }

        $this->travelOrdersRepository->delete($id);

        Flash::success('Travel Orders deleted successfully.');

        return redirect(route('travelOrders.index'));
    }

    public function createOrder(Request $request) {
        $dateFiled = $request['DateFiled'];
        $destination = $request['Destination'];
        $purpose = $request['Purpose'];
        $dates = $request['Dates'];
        $employees = $request['Employees'];

        $id = IDGenerator::generateID();
        $travel = new TravelOrders;
        $travel->id = $id;
        $travel->DateFiled = date('Y-m-d', strtotime($dateFiled));
        $travel->Destination = $destination;
        $travel->Purpose = $purpose;
        $travel->UserId = Auth::id();
        $travel->save();

        // save employees
        foreach($employees as $item) {
            $travelEmployee = new TravelOrderEmployees;
            $travelEmployee->id = IDGenerator::generateIDandRandString();
            $travelEmployee->TravelOrderId = $id;
            $travelEmployee->EmployeeId = $item['id'];
            $travelEmployee->save();
        }

        // save dates
        foreach($dates as $item) {
            $travelDate = new TravelOrderDays;
            $travelDate->id = IDGenerator::generateIDandRandString();
            $travelDate->TravelOrderId = $id;
            $travelDate->Day = $item;
            $travelDate->save();
        }

        // signatories
        // FIND NEXT SIGNATORY
        $signatories = Employees::getSupers(Auth::user()->employee_id, ['Manager', 'General Manager']);

        $rank = 1;
        foreach($signatories as $signatory) {
            $sigs = new TravelOrderSignatories;
            $sigs->id = IDGenerator::generateIDandRandString() . $rank;
            $sigs->TravelOrderId = $id;
            $sigs->UserId = $signatory['id'];
            $sigs->Rank = $rank;
            $sigs->save();

            if ($rank == 1) {
                $employee = Employees::find($signatory['EmployeeId']);

                if ($employee != null) {
                    /**
                     * =========================================================================
                     * SEND SMS
                     * =========================================================================
                     */
                    if ($employee != null && $employee->ContactNumbers != null) {
                        SMSNotifications::sendSMS($employee->ContactNumbers, 
                            "HR System - Travel Order Approval:\n\n" . Auth::user()->name . " has filed a new Travel Order for " . $purpose . " in " . $destination . " that needs your approval.",
                            "HR-Travel Order",
                            $id
                        );
                    }
                }
            }

            $rank++;
        }

        return response()->json($travel, 200);
    }

    public function myAprovals(Request $request) {
        $travels = DB::table('TravelOrderSignatories')
            ->leftJoin('TravelOrders', 'TravelOrderSignatories.TravelOrderId', '=', 'TravelOrders.id')
            ->leftJoin('users', 'TravelOrders.UserId', '=', 'users.id')
            ->leftJoin('Employees', 'users.employee_id', '=', 'Employees.id')
            ->whereRaw("TravelOrderSignatories.UserId='" . Auth::id() . "' AND TravelOrderSignatories.Status IS NULL AND TravelOrderSignatories.id IN 
                (SELECT TOP 1 x.id FROM TravelOrderSignatories x WHERE x.TravelOrderId=TravelOrderSignatories.TravelOrderId AND x.Status IS NULL ORDER BY x.Rank)")
            ->select('TravelOrders.*',
                'TravelOrderSignatories.id AS SignatoryId',
                'Employees.FirstName',
                'Employees.MiddleName',
                'Employees.LastName',
                'Employees.Suffix',)
            ->get();

        return view('/travel_orders/my_approvals', [
            'travels' => $travels
        ]);
    }
    
    public function approveAjax(Request $request) {
        $id = $request['id'];
        $signatoryId = $request['SignatoryId'];

        $travel = TravelOrders::find($id);
        $travelSignatory = TravelOrderSignatories::find($signatoryId);

        // UPDATE SIGNATORIES
        $travelSignatory->Status = 'APPROVED';
        $travelSignatory->save();

        // GET USER
        $user = Users::find($travel->UserId);
        $employee = Employees::find($user->employee_id);

        // ADD NOTIFICATION FOR THE REQUISITIONER
        $notifications = new Notifications;
        $notifications->UserId = $user != null ? $user->id : '';
        $notifications->Type = 'TRAVEL_INFO';
        $notifications->Content = Users::find($travelSignatory->UserId)->name . ' has approved your travel order request.';
        $notifications->Notes = $id;
        $notifications->Status = "UNREAD";
        $notifications->save();

        // ADD NOTIFICATIONS FOR NEXT SIGNATORY
        // ADD NOTIFICATIONS FOR NEXT SIGNATORY
        $nextSignatory = DB::table('TravelOrderSignatories')
            ->whereRaw("TravelOrderId='" . $id . "' AND (Status IS NULL OR Status NOT IN('REMOVED')) AND Rank > " . $travelSignatory->Rank)
            ->orderBy('Rank')
            ->first();

        if ($nextSignatory != null) {
            // IF LEAVE IS STILL NOTE COMPLETED SIGNING
            $notifications = new Notifications;
            $notifications->UserId = $nextSignatory->UserId;
            $notifications->Type = 'TRAVEL_ORDER_APPROVAL';
            $notifications->Content = ($employee != null ? Employees::getMergeName($employee) : 'Someone ') . " has filed a leave. Check it out to approve.";
            $notifications->Notes = $id;
            $notifications->Status = "UNREAD";
            $notifications->save();

            // UPDATE LEAVE STATUS
            $travel->Status = 'Partially Approved';
            $travel->save();
            
            /**
             * =========================================================================
             * SEND SMS
             * =========================================================================
             */
            if ($employee != null && $employee->ContactNumbers != null) {
                SMSNotifications::sendSMS($employee->ContactNumbers, 
                    "HR System - Travel Order Approval:\n\n" . Users::find($travelSignatory->UserId)->name . " has APPROVED your travel order request. 
                        Your travel order is now forwarded to the next signatory.",
                    "HR-Travel Order",
                    $id
                );
            }
        } else {
            $travel->Status = 'APPROVED';
            $travel->save();

            /**
             * =========================================================================
             * SEND SMS
             * =========================================================================
             */
            if ($employee != null && $employee->ContactNumbers != null) {
                SMSNotifications::sendSMS($employee->ContactNumbers, 
                    "HR System - Travel Order Approval:\n\n" . Users::find($travelSignatory->UserId)->name . " has APPROVED your travel order request",
                    "HR-Travel Order",
                    $id
                );
            }            
        }
        
        return response()->json('ok', 200);
    }

    public function getTravelOrdersAjax(Request $request) {
        $employeeId = $request['EmployeeId'];
        $start = $request['Start'];

        $data = DB::table('TravelOrderEmployees')
            ->leftJoin('TravelOrders', 'TravelOrderEmployees.TravelOrderId', '=', 'TravelOrders.id')
            ->whereRaw("TravelOrderEmployees.EmployeeId='" . $employeeId . "' AND TravelOrders.DateFiled <= '" . $start . "'")
            ->select(
                'TravelOrders.*'
            )
            ->orderByDesc('TravelOrders.DateFiled')
            ->paginate(6);

        foreach($data as $item) {
            $item->Days = DB::table('TravelOrderDays')
                ->whereRaw("TravelOrderId='" . $item->id . "'")
                ->select('Day')
                ->get();
        }

        return response()->json($data, 200);
    }
}
