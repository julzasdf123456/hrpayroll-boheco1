<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEmployeeBonusesRequest;
use App\Http\Requests\UpdateEmployeeBonusesRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\EmployeeBonusesRepository;
use Illuminate\Http\Request;
use Flash;

class EmployeeBonusesController extends AppBaseController
{
    /** @var EmployeeBonusesRepository $employeeBonusesRepository*/
    private $employeeBonusesRepository;

    public function __construct(EmployeeBonusesRepository $employeeBonusesRepo)
    {
        $this->employeeBonusesRepository = $employeeBonusesRepo;
    }

    /**
     * Display a listing of the EmployeeBonuses.
     */
    public function index(Request $request)
    {
        $employeeBonuses = $this->employeeBonusesRepository->paginate(10);

        return view('employee_bonuses.index')
            ->with('employeeBonuses', $employeeBonuses);
    }

    /**
     * Show the form for creating a new EmployeeBonuses.
     */
    public function create()
    {
        return view('employee_bonuses.create');
    }

    /**
     * Store a newly created EmployeeBonuses in storage.
     */
    public function store(CreateEmployeeBonusesRequest $request)
    {
        $input = $request->all();

        $employeeBonuses = $this->employeeBonusesRepository->create($input);

        Flash::success('Employee Bonuses saved successfully.');

        return redirect(route('employeeBonuses.index'));
    }

    /**
     * Display the specified EmployeeBonuses.
     */
    public function show($id)
    {
        $employeeBonuses = $this->employeeBonusesRepository->find($id);

        if (empty($employeeBonuses)) {
            Flash::error('Employee Bonuses not found');

            return redirect(route('employeeBonuses.index'));
        }

        return view('employee_bonuses.show')->with('employeeBonuses', $employeeBonuses);
    }

    /**
     * Show the form for editing the specified EmployeeBonuses.
     */
    public function edit($id)
    {
        $employeeBonuses = $this->employeeBonusesRepository->find($id);

        if (empty($employeeBonuses)) {
            Flash::error('Employee Bonuses not found');

            return redirect(route('employeeBonuses.index'));
        }

        return view('employee_bonuses.edit')->with('employeeBonuses', $employeeBonuses);
    }

    /**
     * Update the specified EmployeeBonuses in storage.
     */
    public function update($id, UpdateEmployeeBonusesRequest $request)
    {
        $employeeBonuses = $this->employeeBonusesRepository->find($id);

        if (empty($employeeBonuses)) {
            Flash::error('Employee Bonuses not found');

            return redirect(route('employeeBonuses.index'));
        }

        $employeeBonuses = $this->employeeBonusesRepository->update($request->all(), $id);

        Flash::success('Employee Bonuses updated successfully.');

        return redirect(route('employeeBonuses.index'));
    }

    /**
     * Remove the specified EmployeeBonuses from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $employeeBonuses = $this->employeeBonusesRepository->find($id);

        if (empty($employeeBonuses)) {
            Flash::error('Employee Bonuses not found');

            return redirect(route('employeeBonuses.index'));
        }

        $this->employeeBonusesRepository->delete($id);

        Flash::success('Employee Bonuses deleted successfully.');

        return redirect(route('employeeBonuses.index'));
    }
}
