<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEducationalAttainmentRequest;
use App\Http\Requests\UpdateEducationalAttainmentRequest;
use App\Repositories\EducationalAttainmentRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Employees;
use Illuminate\Support\Facades\Storage;
use Flash;
use Response;

class EducationalAttainmentController extends AppBaseController
{
    /** @var  EducationalAttainmentRepository */
    private $educationalAttainmentRepository;

    public function __construct(EducationalAttainmentRepository $educationalAttainmentRepo)
    {
        $this->middleware('auth');
        $this->educationalAttainmentRepository = $educationalAttainmentRepo;
    }

    /**
     * Display a listing of the EducationalAttainment.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $educationalAttainments = $this->educationalAttainmentRepository->all();

        return view('educational_attainments.index')
            ->with('educationalAttainments', $educationalAttainments);
    }

    /**
     * Show the form for creating a new EducationalAttainment.
     *
     * @return Response
     */
    public function create()
    {
        return view('educational_attainments.create');
    }

    /**
     * Store a newly created EducationalAttainment in storage.
     *
     * @param CreateEducationalAttainmentRequest $request
     *
     * @return Response
     */
    public function store(CreateEducationalAttainmentRequest $request)
    {
        $input = $request->all();

        $educationalAttainment = $this->educationalAttainmentRepository->create($input);

        Flash::success('Educational Attainment saved successfully.');

        return redirect(route('educationalAttainments.index'));
    }

    /**
     * Display the specified EducationalAttainment.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $educationalAttainment = $this->educationalAttainmentRepository->find($id);

        if (empty($educationalAttainment)) {
            Flash::error('Educational Attainment not found');

            return redirect(route('educationalAttainments.index'));
        }

        return view('educational_attainments.show')->with('educationalAttainment', $educationalAttainment);
    }

    /**
     * Show the form for editing the specified EducationalAttainment.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $educationalAttainment = $this->educationalAttainmentRepository->find($id);

        if (empty($educationalAttainment)) {
            Flash::error('Educational Attainment not found');

            return redirect(route('educationalAttainments.index'));
        }

        return view('educational_attainments.edit')->with('educationalAttainment', $educationalAttainment);
    }

    /**
     * Update the specified EducationalAttainment in storage.
     *
     * @param int $id
     * @param UpdateEducationalAttainmentRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEducationalAttainmentRequest $request)
    {
        $educationalAttainment = $this->educationalAttainmentRepository->find($id);

        if (empty($educationalAttainment)) {
            Flash::error('Educational Attainment not found');

            return redirect(route('educationalAttainments.index'));
        }

        $educationalAttainment = $this->educationalAttainmentRepository->update($request->all(), $id);

        Flash::success('Educational Attainment updated successfully.');

        return redirect(route('educationalAttainments.index'));
    }

    /**
     * Remove the specified EducationalAttainment from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $educationalAttainment = $this->educationalAttainmentRepository->find($id);

        if (empty($educationalAttainment)) {
            Flash::error('Educational Attainment not found');

            return redirect(route('educationalAttainments.index'));
        }

        if ($educationalAttainment->Certification != null) {
            Storage::deleteDirectory('public/documents/' . $educationalAttainment->EmployeeId . '/' . $educationalAttainment->Type);
        }

        $this->educationalAttainmentRepository->delete($id);

        Flash::success('Educational Attainment deleted successfully.');

        

        // return redirect(route('educationalAttainments.index'));
        return response()->json(['res' => 'ok'], 200);
    }
}
