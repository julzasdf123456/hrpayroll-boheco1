<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOvertimesRequest;
use App\Http\Requests\UpdateOvertimesRequest;
use App\Repositories\OvertimesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Employees;
use Flash;
use Response;

class OvertimesController extends AppBaseController
{
    /** @var  OvertimesRepository */
    private $overtimesRepository;

    public function __construct(OvertimesRepository $overtimesRepo)
    {
        $this->overtimesRepository = $overtimesRepo;
    }

    /**
     * Display a listing of the Overtimes.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $overtimes = $this->overtimesRepository->all();

        return view('overtimes.index')
            ->with('overtimes', $overtimes);
    }

    /**
     * Show the form for creating a new Overtimes.
     *
     * @return Response
     */
    public function create()
    {
        return view('overtimes.create', [
            'employees' => Employees::all(),
        ]);
    }

    /**
     * Store a newly created Overtimes in storage.
     *
     * @param CreateOvertimesRequest $request
     *
     * @return Response
     */
    public function store(CreateOvertimesRequest $request)
    {
        $input = $request->all();

        $overtimes = $this->overtimesRepository->create($input);

        Flash::success('Overtimes saved successfully.');

        return redirect(route('overtimes.index'));
    }

    /**
     * Display the specified Overtimes.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $overtimes = $this->overtimesRepository->find($id);

        if (empty($overtimes)) {
            Flash::error('Overtimes not found');

            return redirect(route('overtimes.index'));
        }

        return view('overtimes.show')->with('overtimes', $overtimes);
    }

    /**
     * Show the form for editing the specified Overtimes.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $overtimes = $this->overtimesRepository->find($id);

        if (empty($overtimes)) {
            Flash::error('Overtimes not found');

            return redirect(route('overtimes.index'));
        }

        return view('overtimes.edit', [
            'employees' => Employees::all(),
        ])->with('overtimes', $overtimes);
    }

    /**
     * Update the specified Overtimes in storage.
     *
     * @param int $id
     * @param UpdateOvertimesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateOvertimesRequest $request)
    {
        $overtimes = $this->overtimesRepository->find($id);

        if (empty($overtimes)) {
            Flash::error('Overtimes not found');

            return redirect(route('overtimes.index'));
        }

        $overtimes = $this->overtimesRepository->update($request->all(), $id);

        Flash::success('Overtimes updated successfully.');

        return redirect(route('overtimes.index'));
    }

    /**
     * Remove the specified Overtimes from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $overtimes = $this->overtimesRepository->find($id);

        if (empty($overtimes)) {
            Flash::error('Overtimes not found');

            return redirect(route('overtimes.index'));
        }

        $this->overtimesRepository->delete($id);

        Flash::success('Overtimes deleted successfully.');

        return redirect(route('overtimes.index'));
    }
}
