<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMemorandumEmployeesRequest;
use App\Http\Requests\UpdateMemorandumEmployeesRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\MemorandumEmployeesRepository;
use Illuminate\Http\Request;
use Flash;

class MemorandumEmployeesController extends AppBaseController
{
    /** @var MemorandumEmployeesRepository $memorandumEmployeesRepository*/
    private $memorandumEmployeesRepository;

    public function __construct(MemorandumEmployeesRepository $memorandumEmployeesRepo)
    {
        $this->middleware('auth');
        $this->memorandumEmployeesRepository = $memorandumEmployeesRepo;
    }

    /**
     * Display a listing of the MemorandumEmployees.
     */
    public function index(Request $request)
    {
        $memorandumEmployees = $this->memorandumEmployeesRepository->paginate(10);

        return view('memorandum_employees.index')
            ->with('memorandumEmployees', $memorandumEmployees);
    }

    /**
     * Show the form for creating a new MemorandumEmployees.
     */
    public function create()
    {
        return view('memorandum_employees.create');
    }

    /**
     * Store a newly created MemorandumEmployees in storage.
     */
    public function store(CreateMemorandumEmployeesRequest $request)
    {
        $input = $request->all();

        $memorandumEmployees = $this->memorandumEmployeesRepository->create($input);

        Flash::success('Memorandum Employees saved successfully.');

        return redirect(route('memorandumEmployees.index'));
    }

    /**
     * Display the specified MemorandumEmployees.
     */
    public function show($id)
    {
        $memorandumEmployees = $this->memorandumEmployeesRepository->find($id);

        if (empty($memorandumEmployees)) {
            Flash::error('Memorandum Employees not found');

            return redirect(route('memorandumEmployees.index'));
        }

        return view('memorandum_employees.show')->with('memorandumEmployees', $memorandumEmployees);
    }

    /**
     * Show the form for editing the specified MemorandumEmployees.
     */
    public function edit($id)
    {
        $memorandumEmployees = $this->memorandumEmployeesRepository->find($id);

        if (empty($memorandumEmployees)) {
            Flash::error('Memorandum Employees not found');

            return redirect(route('memorandumEmployees.index'));
        }

        return view('memorandum_employees.edit')->with('memorandumEmployees', $memorandumEmployees);
    }

    /**
     * Update the specified MemorandumEmployees in storage.
     */
    public function update($id, UpdateMemorandumEmployeesRequest $request)
    {
        $memorandumEmployees = $this->memorandumEmployeesRepository->find($id);

        if (empty($memorandumEmployees)) {
            Flash::error('Memorandum Employees not found');

            return redirect(route('memorandumEmployees.index'));
        }

        $memorandumEmployees = $this->memorandumEmployeesRepository->update($request->all(), $id);

        Flash::success('Memorandum Employees updated successfully.');

        return redirect(route('memorandumEmployees.index'));
    }

    /**
     * Remove the specified MemorandumEmployees from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $memorandumEmployees = $this->memorandumEmployeesRepository->find($id);

        if (empty($memorandumEmployees)) {
            Flash::error('Memorandum Employees not found');

            return redirect(route('memorandumEmployees.index'));
        }

        $this->memorandumEmployeesRepository->delete($id);

        Flash::success('Memorandum Employees deleted successfully.');

        return redirect(route('memorandumEmployees.index'));
    }
}
