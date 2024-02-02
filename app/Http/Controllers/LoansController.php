<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLoansRequest;
use App\Http\Requests\UpdateLoansRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\LoansRepository;
use Illuminate\Http\Request;
use App\Models\Loans;
use App\Models\LoanDetails;
use App\Models\IDGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Flash;

class LoansController extends AppBaseController
{
    /** @var LoansRepository $loansRepository*/
    private $loansRepository;

    public function __construct(LoansRepository $loansRepo)
    {
        $this->middleware('auth');
        $this->loansRepository = $loansRepo;
    }

    /**
     * Display a listing of the Loans.
     */
    public function index(Request $request)
    {
        $loans = $this->loansRepository->paginate(10);

        return view('loans.index')
            ->with('loans', $loans);
    }

    /**
     * Show the form for creating a new Loans.
     */
    public function create()
    {
        return view('loans.create');
    }

    /**
     * Store a newly created Loans in storage.
     */
    public function store(CreateLoansRequest $request)
    {
        $input = $request->all();

        $loans = $this->loansRepository->create($input);

        Flash::success('Loans saved successfully.');

        return redirect(route('loans.index'));
    }

    /**
     * Display the specified Loans.
     */
    public function show($id)
    {
        $loans = $this->loansRepository->find($id);

        if (empty($loans)) {
            Flash::error('Loans not found');

            return redirect(route('loans.index'));
        }

        return view('loans.show')->with('loans', $loans);
    }

    /**
     * Show the form for editing the specified Loans.
     */
    public function edit($id)
    {
        $loans = $this->loansRepository->find($id);

        if (empty($loans)) {
            Flash::error('Loans not found');

            return redirect(route('loans.index'));
        }

        return view('loans.edit')->with('loans', $loans);
    }

    /**
     * Update the specified Loans in storage.
     */
    public function update($id, UpdateLoansRequest $request)
    {
        $loans = $this->loansRepository->find($id);

        if (empty($loans)) {
            Flash::error('Loans not found');

            return redirect(route('loans.index'));
        }

        $loans = $this->loansRepository->update($request->all(), $id);

        Flash::success('Loans updated successfully.');

        return redirect(route('loans.index'));
    }

    /**
     * Remove the specified Loans from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $loans = $this->loansRepository->find($id);

        if (empty($loans)) {
            Flash::error('Loans not found');

            return redirect(route('loans.index'));
        }

        $this->loansRepository->delete($id);

        Flash::success('Loans deleted successfully.');

        return redirect(route('loans.index'));
    }

    public function pagIbig(Request $request) {
        $data = DB::table('Loans')
            ->leftJoin('Employees', 'Loans.EmployeeId', '=', 'Employees.id')
            ->whereRaw("LoanFor='Pag-Ibig'")
            ->select(
                'Employees.FirstName',
                'Employees.MiddleName',
                'Employees.LastName',
                'Employees.Suffix',
                'Loans.*')
            ->orderByDesc('Loans.created_at')
            ->get();

        return view('/loans/pag_ibig', [
            'data' => $data,
        ]);
    }

    public function sss(Request $request) {
        $data = DB::table('Loans')
            ->leftJoin('Employees', 'Loans.EmployeeId', '=', 'Employees.id')
            ->whereRaw("LoanFor='SSS'")
            ->select(
                'Employees.FirstName',
                'Employees.MiddleName',
                'Employees.LastName',
                'Employees.Suffix',
                'Loans.*')
            ->orderByDesc('Loans.created_at')
            ->get();

        return view('/loans/sss', [
            'data' => $data,
        ]);
    }

    public function motorcycle(Request $request) {
        $data = DB::table('Loans')
            ->leftJoin('Employees', 'Loans.EmployeeId', '=', 'Employees.id')
            ->whereRaw("LoanFor='Motorcycle'")
            ->select(
                'Employees.FirstName',
                'Employees.MiddleName',
                'Employees.LastName',
                'Employees.Suffix',
                'Loans.*')
            ->orderByDesc('Loans.created_at')
            ->get();

        return view('/loans/motorcycle', [
            'data' => $data,
        ]);
    }

    public function savePagIbigLoans(Request $request) {
        $employeeId = $request['EmployeeId'];
        $monthlyAmmortization = $request['MonthlyAmmortization'];
        $terms = $request['Terms'];
        $startingDate = $request['StartingDate'];

        $loanId = IDGenerator::generateID();
        $loan = new Loans;
        $loan->id = $loanId;
        $loan->LoanFor = 'Pag-Ibig';
        $loan->LoanName = 'Pag-Ibig';
        $loan->Terms = $terms;
        $loan->TermUnit = 'Monthly';
        $loan->EmployeeId = $employeeId;
        $loan->PaymentTerm = '15/30';
        $loan->MonthlyAmmortization = $monthlyAmmortization;
        $loan->save();

        for($i=0; $i<$terms; $i++) {
            if ($loan->PaymentTerm=='15') {
                $loanDetails = new LoanDetails;
                $loanDetails->id = IDGenerator::generateIDandRandString() . $i;
                $loanDetails->LoanId = $loanId;
                $loanDetails->MonthlyAmmortization = $monthlyAmmortization;
                $loanDetails->Month = date('Y-m-15', strtotime($startingDate . ' +' . $i . ' months'));
                $loanDetails->save();
            } elseif ($loan->PaymentTerm=='30') {
                $baseDate = date('Y-m-d', strtotime($startingDate . ' +' . $i . ' months'));
                $month = date('Y-m-d', strtotime('last day of ' . $baseDate));

                $loanDetails = new LoanDetails;
                $loanDetails->id = IDGenerator::generateIDandRandString() . $i;
                $loanDetails->LoanId = $loanId;
                $loanDetails->MonthlyAmmortization = $monthlyAmmortization;
                $loanDetails->Month = date('Y-m-d', strtotime($month));
                $loanDetails->save();
            } else {
                $loanDetails = new LoanDetails;
                $loanDetails->id = IDGenerator::generateIDandRandString() . $i;
                $loanDetails->LoanId = $loanId;
                $loanDetails->MonthlyAmmortization = round(floatval($monthlyAmmortization)/2, 2);
                $loanDetails->Month = date('Y-m-15', strtotime($startingDate . ' +' . $i . ' months'));
                $loanDetails->save();

                $baseDate = date('Y-m-d', strtotime($startingDate . ' +' . $i . ' months'));
                $month = date('Y-m-d', strtotime('last day of ' . $baseDate));

                $loanDetails = new LoanDetails;
                $loanDetails->id = IDGenerator::generateIDandRandString() . $i;
                $loanDetails->LoanId = $loanId;
                $loanDetails->MonthlyAmmortization = round(floatval($monthlyAmmortization)/2, 2);
                $loanDetails->Month = date('Y-m-d', strtotime($month));
                $loanDetails->save();
            }
        }

        return response()->json($loan, 200);
    }

    public function saveSSSLoans(Request $request) {
        $employeeId = $request['EmployeeId'];
        $monthlyAmmortization = $request['MonthlyAmmortization'];
        $terms = $request['Terms'];
        $startingDate = $request['StartingDate'];

        $loanId = IDGenerator::generateID();
        $loan = new Loans;
        $loan->id = $loanId;
        $loan->LoanFor = 'SSS';
        $loan->LoanName = 'SSS';
        $loan->Terms = $terms;
        $loan->TermUnit = 'Monthly';
        $loan->EmployeeId = $employeeId;
        $loan->PaymentTerm = '15/30';
        $loan->MonthlyAmmortization = $monthlyAmmortization;
        $loan->save();

        for($i=0; $i<$terms; $i++) {
            if ($loan->PaymentTerm=='15') {
                $loanDetails = new LoanDetails;
                $loanDetails->id = IDGenerator::generateIDandRandString() . $i;
                $loanDetails->LoanId = $loanId;
                $loanDetails->MonthlyAmmortization = $monthlyAmmortization;
                $loanDetails->Month = date('Y-m-15', strtotime($startingDate . ' +' . $i . ' months'));
                $loanDetails->save();
            } elseif ($loan->PaymentTerm=='30') {
                $baseDate = date('Y-m-d', strtotime($startingDate . ' +' . $i . ' months'));
                $month = date('Y-m-d', strtotime('last day of ' . $baseDate));

                $loanDetails = new LoanDetails;
                $loanDetails->id = IDGenerator::generateIDandRandString() . $i;
                $loanDetails->LoanId = $loanId;
                $loanDetails->MonthlyAmmortization = $monthlyAmmortization;
                $loanDetails->Month = date('Y-m-d', strtotime($month));
                $loanDetails->save();
            } else {
                $loanDetails = new LoanDetails;
                $loanDetails->id = IDGenerator::generateIDandRandString() . $i;
                $loanDetails->LoanId = $loanId;
                $loanDetails->MonthlyAmmortization = round(floatval($monthlyAmmortization)/2, 2);
                $loanDetails->Month = date('Y-m-15', strtotime($startingDate . ' +' . $i . ' months'));
                $loanDetails->save();

                $baseDate = date('Y-m-d', strtotime($startingDate . ' +' . $i . ' months'));
                $month = date('Y-m-d', strtotime('last day of ' . $baseDate));

                $loanDetails = new LoanDetails;
                $loanDetails->id = IDGenerator::generateIDandRandString() . $i;
                $loanDetails->LoanId = $loanId;
                $loanDetails->MonthlyAmmortization = round(floatval($monthlyAmmortization)/2, 2);
                $loanDetails->Month = date('Y-m-d', strtotime($month));
                $loanDetails->save();
            }
        }

        return response()->json($loan, 200);
    }

    public function saveMotorcycleLoans(Request $request) {
        $employeeId = $request['EmployeeId'];
        $monthlyAmmortization = $request['MonthlyAmmortization'];
        $terms = $request['Terms'];
        $startingDate = $request['StartingDate'];

        $loanId = IDGenerator::generateID();
        $loan = new Loans;
        $loan->id = $loanId;
        $loan->LoanFor = 'Motorcycle';
        $loan->LoanName = 'Motorcycle';
        $loan->Terms = $terms;
        $loan->TermUnit = 'Monthly';
        $loan->EmployeeId = $employeeId;
        $loan->PaymentTerm = '15/30';
        $loan->MonthlyAmmortization = $monthlyAmmortization;
        $loan->save();

        for($i=0; $i<$terms; $i++) {
            if ($loan->PaymentTerm=='15') {
                $loanDetails = new LoanDetails;
                $loanDetails->id = IDGenerator::generateIDandRandString() . $i;
                $loanDetails->LoanId = $loanId;
                $loanDetails->MonthlyAmmortization = $monthlyAmmortization;
                $loanDetails->Month = date('Y-m-15', strtotime($startingDate . ' +' . $i . ' months'));
                $loanDetails->save();
            } elseif ($loan->PaymentTerm=='30') {
                $baseDate = date('Y-m-d', strtotime($startingDate . ' +' . $i . ' months'));
                $month = date('Y-m-d', strtotime('last day of ' . $baseDate));

                $loanDetails = new LoanDetails;
                $loanDetails->id = IDGenerator::generateIDandRandString() . $i;
                $loanDetails->LoanId = $loanId;
                $loanDetails->MonthlyAmmortization = $monthlyAmmortization;
                $loanDetails->Month = date('Y-m-d', strtotime($month));
                $loanDetails->save();
            } else {
                $loanDetails = new LoanDetails;
                $loanDetails->id = IDGenerator::generateIDandRandString() . $i;
                $loanDetails->LoanId = $loanId;
                $loanDetails->MonthlyAmmortization = round(floatval($monthlyAmmortization)/2, 2);
                $loanDetails->Month = date('Y-m-15', strtotime($startingDate . ' +' . $i . ' months'));
                $loanDetails->save();

                $baseDate = date('Y-m-d', strtotime($startingDate . ' +' . $i . ' months'));
                $month = date('Y-m-d', strtotime('last day of ' . $baseDate));

                $loanDetails = new LoanDetails;
                $loanDetails->id = IDGenerator::generateIDandRandString() . $i;
                $loanDetails->LoanId = $loanId;
                $loanDetails->MonthlyAmmortization = round(floatval($monthlyAmmortization)/2, 2);
                $loanDetails->Month = date('Y-m-d', strtotime($month));
                $loanDetails->save();
            }
        }

        return response()->json($loan, 200);
    }

    public function getLoanDetailsAjax(Request $request) {
        $loanId = $request['LoanId'];

        $loanDetails = LoanDetails::where('LoanId', $loanId)->orderBy('Month')->get();

        return response()->json($loanDetails, 200);
    }
}
