<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProfessionalIDsRequest;
use App\Http\Requests\UpdateProfessionalIDsRequest;
use App\Repositories\ProfessionalIDsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ProfessionalIDsController extends AppBaseController
{
    /** @var  ProfessionalIDsRepository */
    private $professionalIDsRepository;

    public function __construct(ProfessionalIDsRepository $professionalIDsRepo)
    {
        $this->middleware('auth');
        $this->professionalIDsRepository = $professionalIDsRepo;
    }

    /**
     * Display a listing of the ProfessionalIDs.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $professionalIDs = $this->professionalIDsRepository->all();

        return view('professional_i_ds.index')
            ->with('professionalIDs', $professionalIDs);
    }

    /**
     * Show the form for creating a new ProfessionalIDs.
     *
     * @return Response
     */
    public function create()
    {
        return view('professional_i_ds.create');
    }

    /**
     * Store a newly created ProfessionalIDs in storage.
     *
     * @param CreateProfessionalIDsRequest $request
     *
     * @return Response
     */
    public function store(CreateProfessionalIDsRequest $request)
    {
        $input = $request->all();

        $professionalIDs = $this->professionalIDsRepository->create($input);

        Flash::success('Professional I Ds saved successfully.');

        return redirect(route('employees.update-third-party-ids', [$professionalIDs->EmployeeId]));
    }

    /**
     * Display the specified ProfessionalIDs.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $professionalIDs = $this->professionalIDsRepository->find($id);

        if (empty($professionalIDs)) {
            Flash::error('Professional I Ds not found');

            return redirect(route('professionalIDs.index'));
        }

        return view('professional_i_ds.show')->with('professionalIDs', $professionalIDs);
    }

    /**
     * Show the form for editing the specified ProfessionalIDs.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $professionalIDs = $this->professionalIDsRepository->find($id);

        if (empty($professionalIDs)) {
            Flash::error('Professional I Ds not found');

            return redirect(route('professionalIDs.index'));
        }

        return view('professional_i_ds.edit')->with('professionalIDs', $professionalIDs);
    }

    /**
     * Update the specified ProfessionalIDs in storage.
     *
     * @param int $id
     * @param UpdateProfessionalIDsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProfessionalIDsRequest $request)
    {
        $professionalIDs = $this->professionalIDsRepository->find($id);

        if (empty($professionalIDs)) {
            Flash::error('Professional I Ds not found');

            return redirect(route('professionalIDs.index'));
        }

        $professionalIDs = $this->professionalIDsRepository->update($request->all(), $id);

        Flash::success('Professional I Ds updated successfully.');

        return redirect(route('professionalIDs.index'));
    }

    /**
     * Remove the specified ProfessionalIDs from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $professionalIDs = $this->professionalIDsRepository->find($id);

        if (empty($professionalIDs)) {
            Flash::error('Professional I Ds not found');

            return redirect(route('professionalIDs.index'));
        }

        $this->professionalIDsRepository->delete($id);

        Flash::success('Professional I Ds deleted successfully.');

        return redirect(route('employees.update-third-party-ids', [$professionalIDs->EmployeeId]));
    }
}
