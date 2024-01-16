<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTripTicketGRSRequest;
use App\Http\Requests\UpdateTripTicketGRSRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\TripTicketGRSRepository;
use Illuminate\Http\Request;
use Flash;
use App\Models\Employees;
use App\Models\Towns;
use App\Models\Vehicles;
use App\Models\TripTickets;
use App\Models\TripTicketDestinations;
use App\Models\TripTicketSignatories;
use App\Models\TripTicketGRS;
use App\Models\IDGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TripTicketGRSController extends AppBaseController
{
    /** @var TripTicketGRSRepository $tripTicketGRSRepository*/
    private $tripTicketGRSRepository;

    public function __construct(TripTicketGRSRepository $tripTicketGRSRepo)
    {
        $this->middleware('auth');
        $this->tripTicketGRSRepository = $tripTicketGRSRepo;
    }

    /**
     * Display a listing of the TripTicketGRS.
     */
    public function index(Request $request)
    {
        $tripTicketGRs = $this->tripTicketGRSRepository->paginate(10);

        return view('tripTicketGRS.index')
            ->with('tripTicketGRs', $tripTicketGRs);
    }

    /**
     * Show the form for creating a new TripTicketGRS.
     */
    public function create()
    {
        return view('tripTicketGRS.create');
    }

    /**
     * Store a newly created TripTicketGRS in storage.
     */
    public function store(CreateTripTicketGRSRequest $request)
    {
        $input = $request->all();

        $tripTicketGRS = $this->tripTicketGRSRepository->create($input);

        Flash::success('Trip Ticket G R S saved successfully.');

        return redirect(route('tripTicketGRs.index'));
    }

    /**
     * Display the specified TripTicketGRS.
     */
    public function show($id)
    {
        $tripTicketGRS = $this->tripTicketGRSRepository->find($id);

        if (empty($tripTicketGRS)) {
            Flash::error('Trip Ticket G R S not found');

            return redirect(route('tripTicketGRs.index'));
        }

        return view('tripTicketGRS.show')->with('tripTicketGRS', $tripTicketGRS);
    }

    /**
     * Show the form for editing the specified TripTicketGRS.
     */
    public function edit($id)
    {
        $tripTicketGRS = $this->tripTicketGRSRepository->find($id);

        if (empty($tripTicketGRS)) {
            Flash::error('Trip Ticket G R S not found');

            return redirect(route('tripTicketGRs.index'));
        }

        return view('tripTicketGRS.edit')->with('tripTicketGRS', $tripTicketGRS);
    }

    /**
     * Update the specified TripTicketGRS in storage.
     */
    public function update($id, UpdateTripTicketGRSRequest $request)
    {
        $tripTicketGRS = $this->tripTicketGRSRepository->find($id);

        if (empty($tripTicketGRS)) {
            Flash::error('Trip Ticket G R S not found');

            return redirect(route('tripTicketGRs.index'));
        }

        $tripTicketGRS = $this->tripTicketGRSRepository->update($request->all(), $id);

        Flash::success('Trip Ticket G R S updated successfully.');

        return redirect(route('tripTicketGRs.index'));
    }

    /**
     * Remove the specified TripTicketGRS from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $tripTicketGRS = $this->tripTicketGRSRepository->find($id);

        if (empty($tripTicketGRS)) {
            Flash::error('Trip Ticket G R S not found');

            return redirect(route('tripTicketGRs.index'));
        }

        $this->tripTicketGRSRepository->delete($id);

        Flash::success('Trip Ticket G R S deleted successfully.');

        return redirect(route('tripTicketGRs.index'));
    }

    public function grsRequests(Request $request) {
        $tripTickets = DB::table('TripTickets')
            ->leftJoin('Employees', 'TripTickets.Driver', '=', 'Employees.id')
            ->leftJoin(DB::raw("Employees AS e"), 'TripTickets.EmployeeId', '=', DB::raw("e.id"))
            ->leftJoin('TripTicketSignatories', 'TripTickets.id', '=', 'TripTicketSignatories.TripTicketId')
            ->whereRaw("TripTickets.RequestGRS='Yes' AND TripTickets.Status NOT IN ('Trash')")
            ->select(
                'TripTickets.*',
                'Employees.FirstName AS DriverFirstName',
                'Employees.MiddleName AS DriverMiddleName',
                'Employees.LastName AS DriverLastName',
                'Employees.Suffix AS DriverSuffix',
                'e.FirstName',
                'e.MiddleName',
                'e.LastName',
                'e.Suffix',
            )
            ->orderByDesc('DatetimeFiled')
            ->paginate(25);

        return view('/trip_ticket_g_rs/grs_requests', [
            'tripTickets' => $tripTickets,
        ]);
    }

    public function saveGRS(Request $request) {
        $typeOfFuel = $request['TypeOfFuel'];
        $noOfLiters = $request['TotalLiters'];
        $tripTicketId = $request['TripTicketId'];
        $purpose = $request['Purpose'];

        $id = IDGenerator::generateIDandRandString();
        $grs = new TripTicketGRS;
        $grs->id = $id;
        $grs->TypeOfFuel = $typeOfFuel;
        $grs->TotalLiters = $noOfLiters;
        $grs->TripTicketId = $tripTicketId;
        $grs->Purpose = $purpose;
        $grs->save();

        // UPDATE TripTickets
        TripTickets::where('id', $tripTicketId)
            ->update(['RequestGRS' => 'Added']);

        $grs->id = $id;
        return response()->json($grs, 200);
    }

    public function printGRS($ttId, $grsId) {
        $tripTicket = DB::table('TripTickets')
            ->leftJoin('Employees', 'TripTickets.Driver', '=', 'Employees.id')
            ->leftJoin(DB::raw("Employees AS e"), 'TripTickets.EmployeeId', '=', DB::raw("e.id"))
            ->whereRaw("TripTickets.id='" . $ttId . "'")
            ->select(
                'TripTickets.*',
                'Employees.FirstName AS DriverFirstName',
                'Employees.MiddleName AS DriverMiddleName',
                'Employees.LastName AS DriverLastName',
                'Employees.Suffix AS DriverSuffix',
                'e.FirstName',
                'e.MiddleName',
                'e.LastName',
                'e.Suffix',
            )
            ->first();

        $grs = TripTicketGRS::find($grsId);

        $signatory = DB::table('TripTicketSignatories')
            ->leftJoin('users', 'TripTicketSignatories.EmployeeId', '=', 'users.id')
            ->whereRaw("TripTicketSignatories.TripTicketId='" . $ttId . "'")
            ->select('users.*', 
                'TripTicketSignatories.id AS SignatoryId',
                'TripTicketSignatories.Status'
            )
            ->orderBy('users.name')
            ->first();

        return view('/trip_ticket_g_rs/print_grs', [
            'tripTicket' => $tripTicket,
            'grs' => $grs,
            'signatory' => $signatory,
        ]);
    }
}
