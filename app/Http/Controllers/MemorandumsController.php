<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMemorandumsRequest;
use App\Http\Requests\UpdateMemorandumsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\MemorandumsRepository;
use Illuminate\Http\Request;
use Flash;

class MemorandumsController extends AppBaseController
{
    /** @var MemorandumsRepository $memorandumsRepository*/
    private $memorandumsRepository;

    public function __construct(MemorandumsRepository $memorandumsRepo)
    {
        $this->middleware('auth');
        $this->memorandumsRepository = $memorandumsRepo;
    }

    /**
     * Display a listing of the Memorandums.
     */
    public function index(Request $request)
    {
        $memorandums = $this->memorandumsRepository->paginate(10);

        return view('memorandums.index')
            ->with('memorandums', $memorandums);
    }

    /**
     * Show the form for creating a new Memorandums.
     */
    public function create()
    {
        return view('memorandums.create');
    }

    /**
     * Store a newly created Memorandums in storage.
     */
    public function store(CreateMemorandumsRequest $request)
    {
        $input = $request->all();

        $memorandums = $this->memorandumsRepository->create($input);

        Flash::success('Memorandums saved successfully.');

        return redirect(route('memorandums.index'));
    }

    /**
     * Display the specified Memorandums.
     */
    public function show($id)
    {
        $memorandums = $this->memorandumsRepository->find($id);

        if (empty($memorandums)) {
            Flash::error('Memorandums not found');

            return redirect(route('memorandums.index'));
        }

        return view('memorandums.show')->with('memorandums', $memorandums);
    }

    /**
     * Show the form for editing the specified Memorandums.
     */
    public function edit($id)
    {
        $memorandums = $this->memorandumsRepository->find($id);

        if (empty($memorandums)) {
            Flash::error('Memorandums not found');

            return redirect(route('memorandums.index'));
        }

        return view('memorandums.edit')->with('memorandums', $memorandums);
    }

    /**
     * Update the specified Memorandums in storage.
     */
    public function update($id, UpdateMemorandumsRequest $request)
    {
        $memorandums = $this->memorandumsRepository->find($id);

        if (empty($memorandums)) {
            Flash::error('Memorandums not found');

            return redirect(route('memorandums.index'));
        }

        $memorandums = $this->memorandumsRepository->update($request->all(), $id);

        Flash::success('Memorandums updated successfully.');

        return redirect(route('memorandums.index'));
    }

    /**
     * Remove the specified Memorandums from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $memorandums = $this->memorandumsRepository->find($id);

        if (empty($memorandums)) {
            Flash::error('Memorandums not found');

            return redirect(route('memorandums.index'));
        }

        $this->memorandumsRepository->delete($id);

        Flash::success('Memorandums deleted successfully.');

        return redirect(route('memorandums.index'));
    }

    public function createMemo(Request $request) {
        return view('/memorandums/create_memo', [

        ]);
    }
}
