<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDependentsRequest;
use App\Http\Requests\UpdateDependentsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\DependentsRepository;
use Illuminate\Http\Request;
use App\Models\Dependents;
use Flash;

class DependentsController extends AppBaseController
{
    /** @var DependentsRepository $dependentsRepository*/
    private $dependentsRepository;

    public function __construct(DependentsRepository $dependentsRepo)
    {
        $this->middleware('auth');
        $this->dependentsRepository = $dependentsRepo;
    }

    /**
     * Display a listing of the Dependents.
     */
    public function index(Request $request)
    {
        $dependents = $this->dependentsRepository->paginate(10);

        return view('dependents.index')
            ->with('dependents', $dependents);
    }

    /**
     * Show the form for creating a new Dependents.
     */
    public function create()
    {
        return view('dependents.create');
    }

    /**
     * Store a newly created Dependents in storage.
     */
    public function store(CreateDependentsRequest $request)
    {
        $input = $request->all();

        $dependents = $this->dependentsRepository->create($input);

        // Flash::success('Dependents saved successfully.');

        // return redirect(route('dependents.index'));
        return response()->json($dependents, 200);
    }

    /**
     * Display the specified Dependents.
     */
    public function show($id)
    {
        $dependents = $this->dependentsRepository->find($id);

        if (empty($dependents)) {
            Flash::error('Dependents not found');

            return redirect(route('dependents.index'));
        }

        return view('dependents.show')->with('dependents', $dependents);
    }

    /**
     * Show the form for editing the specified Dependents.
     */
    public function edit($id)
    {
        $dependents = $this->dependentsRepository->find($id);

        if (empty($dependents)) {
            Flash::error('Dependents not found');

            return redirect(route('dependents.index'));
        }

        return view('dependents.edit')->with('dependents', $dependents);
    }

    /**
     * Update the specified Dependents in storage.
     */
    public function update($id, UpdateDependentsRequest $request)
    {
        $dependents = $this->dependentsRepository->find($id);

        if (empty($dependents)) {
            Flash::error('Dependents not found');

            return redirect(route('dependents.index'));
        }

        $dependents = $this->dependentsRepository->update($request->all(), $id);

        Flash::success('Dependents updated successfully.');

        return redirect(route('dependents.index'));
    }

    /**
     * Remove the specified Dependents from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $dependents = $this->dependentsRepository->find($id);

        if (empty($dependents)) {
            Flash::error('Dependents not found');

            return redirect(route('dependents.index'));
        }

        $this->dependentsRepository->delete($id);

        Flash::success('Dependents deleted successfully.');

        return redirect(route('dependents.index'));
    }

    public function addDependents(Request $request) {
        return view('/dependents/add_dependents');
    }

    public function getDependents(Request $request) {
        $employeeId = $request['EmployeeId'];
        $data = Dependents::where('EmployeeId', $employeeId)
            ->orderBy('DependentName')
            ->get();

        return response()->json($data, 200);
    }

    public function removeDependent(Request $request) {
        $id = $request['id'];

        Dependents::where('id', $id)
            ->delete();

        return response()->json('ok', 200);
    }
}
