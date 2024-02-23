<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAttachedAccountsRequest;
use App\Http\Requests\UpdateAttachedAccountsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\AttachedAccountsRepository;
use Illuminate\Http\Request;
use App\Models\AttachedAccounts;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Flash;

class AttachedAccountsController extends AppBaseController
{
    /** @var AttachedAccountsRepository $attachedAccountsRepository*/
    private $attachedAccountsRepository;

    public function __construct(AttachedAccountsRepository $attachedAccountsRepo)
    {
        $this->middleware('auth');
        $this->attachedAccountsRepository = $attachedAccountsRepo;
    }

    /**
     * Display a listing of the AttachedAccounts.
     */
    public function index(Request $request)
    {
        $attachedAccounts = $this->attachedAccountsRepository->paginate(10);

        return view('attached_accounts.index')
            ->with('attachedAccounts', $attachedAccounts);
    }

    /**
     * Show the form for creating a new AttachedAccounts.
     */
    public function create()
    {
        return view('attached_accounts.create');
    }

    /**
     * Store a newly created AttachedAccounts in storage.
     */
    public function store(CreateAttachedAccountsRequest $request)
    {
        $input = $request->all();

        $attachedAccounts = AttachedAccounts::where('EmployeeId', $input['EmployeeId'])
            ->where('AccountNumber', $input['AccountNumber'])
            ->first();

        if ($attachedAccounts != null) {
            return response()->json("Account already exists!", 403);
        } else {
            $attachedAccounts = $this->attachedAccountsRepository->create($input);

            return response()->json($attachedAccounts, 200);
        }
    }

    /**
     * Display the specified AttachedAccounts.
     */
    public function show($id)
    {
        $attachedAccounts = $this->attachedAccountsRepository->find($id);

        if (empty($attachedAccounts)) {
            Flash::error('Attached Accounts not found');

            return redirect(route('attachedAccounts.index'));
        }

        return view('attached_accounts.show')->with('attachedAccounts', $attachedAccounts);
    }

    /**
     * Show the form for editing the specified AttachedAccounts.
     */
    public function edit($id)
    {
        $attachedAccounts = $this->attachedAccountsRepository->find($id);

        if (empty($attachedAccounts)) {
            Flash::error('Attached Accounts not found');

            return redirect(route('attachedAccounts.index'));
        }

        return view('attached_accounts.edit')->with('attachedAccounts', $attachedAccounts);
    }

    /**
     * Update the specified AttachedAccounts in storage.
     */
    public function update($id, UpdateAttachedAccountsRequest $request)
    {
        $attachedAccounts = $this->attachedAccountsRepository->find($id);

        if (empty($attachedAccounts)) {
            Flash::error('Attached Accounts not found');

            return redirect(route('attachedAccounts.index'));
        }

        $attachedAccounts = $this->attachedAccountsRepository->update($request->all(), $id);

        Flash::success('Attached Accounts updated successfully.');

        return redirect(route('attachedAccounts.index'));
    }

    /**
     * Remove the specified AttachedAccounts from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $attachedAccounts = $this->attachedAccountsRepository->find($id);

        if (empty($attachedAccounts)) {
            Flash::error('Attached Accounts not found');

            return redirect(route('attachedAccounts.index'));
        }

        $this->attachedAccountsRepository->delete($id);

        // Flash::success('Attached Accounts deleted successfully.');

        // return redirect(route('attachedAccounts.index'));
        return response()->json('ok', 200);
    }

    public function getConnectedAccounts(Request $request) {
        $employeeId = $request['EmployeeId'];

        $data = AttachedAccounts::where("EmployeeId", $employeeId)
            ->orderBy('AccountNumber')
            ->get();

        return response()->json($data, 200);
    }
}
