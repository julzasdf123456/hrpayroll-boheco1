<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOtherAddonsDeductionsRequest;
use App\Http\Requests\UpdateOtherAddonsDeductionsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\OtherAddonsDeductionsRepository;
use Illuminate\Http\Request;
use Flash;

class OtherAddonsDeductionsController extends AppBaseController
{
    /** @var OtherAddonsDeductionsRepository $otherAddonsDeductionsRepository*/
    private $otherAddonsDeductionsRepository;

    public function __construct(OtherAddonsDeductionsRepository $otherAddonsDeductionsRepo)
    {
        $this->middleware('auth');
        $this->otherAddonsDeductionsRepository = $otherAddonsDeductionsRepo;
    }

    /**
     * Display a listing of the OtherAddonsDeductions.
     */
    public function index(Request $request)
    {
        $otherAddonsDeductions = $this->otherAddonsDeductionsRepository->paginate(10);

        return view('other_addons_deductions.index')
            ->with('otherAddonsDeductions', $otherAddonsDeductions);
    }

    /**
     * Show the form for creating a new OtherAddonsDeductions.
     */
    public function create()
    {
        return view('other_addons_deductions.create');
    }

    /**
     * Store a newly created OtherAddonsDeductions in storage.
     */
    public function store(CreateOtherAddonsDeductionsRequest $request)
    {
        $input = $request->all();

        $otherAddonsDeductions = $this->otherAddonsDeductionsRepository->create($input);

        Flash::success('Other Addons Deductions saved successfully.');

        return redirect(route('otherAddonsDeductions.index'));
    }

    /**
     * Display the specified OtherAddonsDeductions.
     */
    public function show($id)
    {
        $otherAddonsDeductions = $this->otherAddonsDeductionsRepository->find($id);

        if (empty($otherAddonsDeductions)) {
            Flash::error('Other Addons Deductions not found');

            return redirect(route('otherAddonsDeductions.index'));
        }

        return view('other_addons_deductions.show')->with('otherAddonsDeductions', $otherAddonsDeductions);
    }

    /**
     * Show the form for editing the specified OtherAddonsDeductions.
     */
    public function edit($id)
    {
        $otherAddonsDeductions = $this->otherAddonsDeductionsRepository->find($id);

        if (empty($otherAddonsDeductions)) {
            Flash::error('Other Addons Deductions not found');

            return redirect(route('otherAddonsDeductions.index'));
        }

        return view('other_addons_deductions.edit')->with('otherAddonsDeductions', $otherAddonsDeductions);
    }

    /**
     * Update the specified OtherAddonsDeductions in storage.
     */
    public function update($id, UpdateOtherAddonsDeductionsRequest $request)
    {
        $otherAddonsDeductions = $this->otherAddonsDeductionsRepository->find($id);

        if (empty($otherAddonsDeductions)) {
            Flash::error('Other Addons Deductions not found');

            return redirect(route('otherAddonsDeductions.index'));
        }

        $otherAddonsDeductions = $this->otherAddonsDeductionsRepository->update($request->all(), $id);

        Flash::success('Other Addons Deductions updated successfully.');

        return redirect(route('otherAddonsDeductions.index'));
    }

    /**
     * Remove the specified OtherAddonsDeductions from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $otherAddonsDeductions = $this->otherAddonsDeductionsRepository->find($id);

        if (empty($otherAddonsDeductions)) {
            Flash::error('Other Addons Deductions not found');

            return redirect(route('otherAddonsDeductions.index'));
        }

        $this->otherAddonsDeductionsRepository->delete($id);

        Flash::success('Other Addons Deductions deleted successfully.');

        return redirect(route('otherAddonsDeductions.index'));
    }
}
