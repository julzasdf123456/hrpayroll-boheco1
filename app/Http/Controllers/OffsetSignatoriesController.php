<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOffsetSignatoriesRequest;
use App\Http\Requests\UpdateOffsetSignatoriesRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\OffsetSignatoriesRepository;
use Illuminate\Http\Request;
use Flash;

class OffsetSignatoriesController extends AppBaseController
{
    /** @var OffsetSignatoriesRepository $offsetSignatoriesRepository*/
    private $offsetSignatoriesRepository;

    public function __construct(OffsetSignatoriesRepository $offsetSignatoriesRepo)
    {
        $this->offsetSignatoriesRepository = $offsetSignatoriesRepo;
    }

    /**
     * Display a listing of the OffsetSignatories.
     */
    public function index(Request $request)
    {
        $offsetSignatories = $this->offsetSignatoriesRepository->paginate(10);

        return view('offset_signatories.index')
            ->with('offsetSignatories', $offsetSignatories);
    }

    /**
     * Show the form for creating a new OffsetSignatories.
     */
    public function create()
    {
        return view('offset_signatories.create');
    }

    /**
     * Store a newly created OffsetSignatories in storage.
     */
    public function store(CreateOffsetSignatoriesRequest $request)
    {
        $input = $request->all();

        $offsetSignatories = $this->offsetSignatoriesRepository->create($input);

        Flash::success('Offset Signatories saved successfully.');

        return redirect(route('offsetSignatories.index'));
    }

    /**
     * Display the specified OffsetSignatories.
     */
    public function show($id)
    {
        $offsetSignatories = $this->offsetSignatoriesRepository->find($id);

        if (empty($offsetSignatories)) {
            Flash::error('Offset Signatories not found');

            return redirect(route('offsetSignatories.index'));
        }

        return view('offset_signatories.show')->with('offsetSignatories', $offsetSignatories);
    }

    /**
     * Show the form for editing the specified OffsetSignatories.
     */
    public function edit($id)
    {
        $offsetSignatories = $this->offsetSignatoriesRepository->find($id);

        if (empty($offsetSignatories)) {
            Flash::error('Offset Signatories not found');

            return redirect(route('offsetSignatories.index'));
        }

        return view('offset_signatories.edit')->with('offsetSignatories', $offsetSignatories);
    }

    /**
     * Update the specified OffsetSignatories in storage.
     */
    public function update($id, UpdateOffsetSignatoriesRequest $request)
    {
        $offsetSignatories = $this->offsetSignatoriesRepository->find($id);

        if (empty($offsetSignatories)) {
            Flash::error('Offset Signatories not found');

            return redirect(route('offsetSignatories.index'));
        }

        $offsetSignatories = $this->offsetSignatoriesRepository->update($request->all(), $id);

        Flash::success('Offset Signatories updated successfully.');

        return redirect(route('offsetSignatories.index'));
    }

    /**
     * Remove the specified OffsetSignatories from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $offsetSignatories = $this->offsetSignatoriesRepository->find($id);

        if (empty($offsetSignatories)) {
            Flash::error('Offset Signatories not found');

            return redirect(route('offsetSignatories.index'));
        }

        $this->offsetSignatoriesRepository->delete($id);

        Flash::success('Offset Signatories deleted successfully.');

        return redirect(route('offsetSignatories.index'));
    }
}
