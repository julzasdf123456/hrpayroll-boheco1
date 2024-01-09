<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEmployeeImagesRequest;
use App\Http\Requests\UpdateEmployeeImagesRequest;
use App\Repositories\EmployeeImagesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class EmployeeImagesController extends AppBaseController
{
    /** @var  EmployeeImagesRepository */
    private $employeeImagesRepository;

    public function __construct(EmployeeImagesRepository $employeeImagesRepo)
    {
        $this->middleware('auth');
        $this->employeeImagesRepository = $employeeImagesRepo;
    }

    /**
     * Display a listing of the EmployeeImages.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $employeeImages = $this->employeeImagesRepository->all();

        return view('employee_images.index')
            ->with('employeeImages', $employeeImages);
    }

    /**
     * Show the form for creating a new EmployeeImages.
     *
     * @return Response
     */
    public function create()
    {
        return view('employee_images.create');
    }

    /**
     * Store a newly created EmployeeImages in storage.
     *
     * @param CreateEmployeeImagesRequest $request
     *
     * @return Response
     */
    public function store(CreateEmployeeImagesRequest $request)
    {
        $input = $request->all();

        $employeeImages = $this->employeeImagesRepository->create($input);

        Flash::success('Employee Images saved successfully.');

        return redirect(route('employeeImages.index'));
    }

    /**
     * Display the specified EmployeeImages.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $employeeImages = $this->employeeImagesRepository->find($id);

        if (empty($employeeImages)) {
            Flash::error('Employee Images not found');

            return redirect(route('employeeImages.index'));
        }

        return view('employee_images.show')->with('employeeImages', $employeeImages);
    }

    /**
     * Show the form for editing the specified EmployeeImages.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $employeeImages = $this->employeeImagesRepository->find($id);

        if (empty($employeeImages)) {
            Flash::error('Employee Images not found');

            return redirect(route('employeeImages.index'));
        }

        return view('employee_images.edit')->with('employeeImages', $employeeImages);
    }

    /**
     * Update the specified EmployeeImages in storage.
     *
     * @param int $id
     * @param UpdateEmployeeImagesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEmployeeImagesRequest $request)
    {
        $employeeImages = $this->employeeImagesRepository->find($id);

        if (empty($employeeImages)) {
            Flash::error('Employee Images not found');

            return redirect(route('employeeImages.index'));
        }

        $employeeImages = $this->employeeImagesRepository->update($request->all(), $id);

        Flash::success('Employee Images updated successfully.');

        return redirect(route('employeeImages.index'));
    }

    /**
     * Remove the specified EmployeeImages from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $employeeImages = $this->employeeImagesRepository->find($id);

        if (empty($employeeImages)) {
            Flash::error('Employee Images not found');

            return redirect(route('employeeImages.index'));
        }

        $this->employeeImagesRepository->delete($id);

        Flash::success('Employee Images deleted successfully.');

        return redirect(route('employeeImages.index'));
    }
}
