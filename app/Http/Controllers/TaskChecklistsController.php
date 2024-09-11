<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskChecklistsRequest;
use App\Http\Requests\UpdateTaskChecklistsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\TaskChecklistsRepository;
use Illuminate\Http\Request;
use Flash;

class TaskChecklistsController extends AppBaseController
{
    /** @var TaskChecklistsRepository $taskChecklistsRepository*/
    private $taskChecklistsRepository;

    public function __construct(TaskChecklistsRepository $taskChecklistsRepo)
    {
        $this->middleware('auth');
        $this->taskChecklistsRepository = $taskChecklistsRepo;
    }

    /**
     * Display a listing of the TaskChecklists.
     */
    public function index(Request $request)
    {
        $taskChecklists = $this->taskChecklistsRepository->paginate(10);

        return view('task_checklists.index')
            ->with('taskChecklists', $taskChecklists);
    }

    /**
     * Show the form for creating a new TaskChecklists.
     */
    public function create()
    {
        return view('task_checklists.create');
    }

    /**
     * Store a newly created TaskChecklists in storage.
     */
    public function store(CreateTaskChecklistsRequest $request)
    {
        $input = $request->all();

        $taskChecklists = $this->taskChecklistsRepository->create($input);

        Flash::success('Task Checklists saved successfully.');

        return redirect(route('taskChecklists.index'));
    }

    /**
     * Display the specified TaskChecklists.
     */
    public function show($id)
    {
        $taskChecklists = $this->taskChecklistsRepository->find($id);

        if (empty($taskChecklists)) {
            Flash::error('Task Checklists not found');

            return redirect(route('taskChecklists.index'));
        }

        return view('task_checklists.show')->with('taskChecklists', $taskChecklists);
    }

    /**
     * Show the form for editing the specified TaskChecklists.
     */
    public function edit($id)
    {
        $taskChecklists = $this->taskChecklistsRepository->find($id);

        if (empty($taskChecklists)) {
            Flash::error('Task Checklists not found');

            return redirect(route('taskChecklists.index'));
        }

        return view('task_checklists.edit')->with('taskChecklists', $taskChecklists);
    }

    /**
     * Update the specified TaskChecklists in storage.
     */
    public function update($id, UpdateTaskChecklistsRequest $request)
    {
        $taskChecklists = $this->taskChecklistsRepository->find($id);

        if (empty($taskChecklists)) {
            Flash::error('Task Checklists not found');

            return redirect(route('taskChecklists.index'));
        }

        $taskChecklists = $this->taskChecklistsRepository->update($request->all(), $id);

        Flash::success('Task Checklists updated successfully.');

        return redirect(route('taskChecklists.index'));
    }

    /**
     * Remove the specified TaskChecklists from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $taskChecklists = $this->taskChecklistsRepository->find($id);

        if (empty($taskChecklists)) {
            Flash::error('Task Checklists not found');

            return redirect(route('taskChecklists.index'));
        }

        $this->taskChecklistsRepository->delete($id);

        Flash::success('Task Checklists deleted successfully.');

        return redirect(route('taskChecklists.index'));
    }

}
