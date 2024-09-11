<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskHeadsRequest;
use App\Http\Requests\UpdateTaskHeadsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\TaskHeadsRepository;
use Illuminate\Http\Request;
use Flash;

class TaskHeadsController extends AppBaseController
{
    /** @var TaskHeadsRepository $taskHeadsRepository*/
    private $taskHeadsRepository;

    public function __construct(TaskHeadsRepository $taskHeadsRepo)
    {
        $this->middleware('auth');
        $this->taskHeadsRepository = $taskHeadsRepo;
    }

    /**
     * Display a listing of the TaskHeads.
     */
    public function index(Request $request)
    {
        $taskHeads = $this->taskHeadsRepository->paginate(10);

        return view('task_heads.index')
            ->with('taskHeads', $taskHeads);
    }

    /**
     * Show the form for creating a new TaskHeads.
     */
    public function create()
    {
        return view('task_heads.create');
    }

    /**
     * Store a newly created TaskHeads in storage.
     */
    public function store(CreateTaskHeadsRequest $request)
    {
        $input = $request->all();

        $taskHeads = $this->taskHeadsRepository->create($input);

        Flash::success('Task Heads saved successfully.');

        return redirect(route('taskHeads.index'));
    }

    /**
     * Display the specified TaskHeads.
     */
    public function show($id)
    {
        $taskHeads = $this->taskHeadsRepository->find($id);

        if (empty($taskHeads)) {
            Flash::error('Task Heads not found');

            return redirect(route('taskHeads.index'));
        }

        return view('task_heads.show')->with('taskHeads', $taskHeads);
    }

    /**
     * Show the form for editing the specified TaskHeads.
     */
    public function edit($id)
    {
        $taskHeads = $this->taskHeadsRepository->find($id);

        if (empty($taskHeads)) {
            Flash::error('Task Heads not found');

            return redirect(route('taskHeads.index'));
        }

        return view('task_heads.edit')->with('taskHeads', $taskHeads);
    }

    /**
     * Update the specified TaskHeads in storage.
     */
    public function update($id, UpdateTaskHeadsRequest $request)
    {
        $taskHeads = $this->taskHeadsRepository->find($id);

        if (empty($taskHeads)) {
            Flash::error('Task Heads not found');

            return redirect(route('taskHeads.index'));
        }

        $taskHeads = $this->taskHeadsRepository->update($request->all(), $id);

        Flash::success('Task Heads updated successfully.');

        return redirect(route('taskHeads.index'));
    }

    /**
     * Remove the specified TaskHeads from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $taskHeads = $this->taskHeadsRepository->find($id);

        if (empty($taskHeads)) {
            Flash::error('Task Heads not found');

            return redirect(route('taskHeads.index'));
        }

        $this->taskHeadsRepository->delete($id);

        Flash::success('Task Heads deleted successfully.');

        return redirect(route('taskHeads.index'));
    }

    public function kanban(Request $request) {
        return view('/task_heads/kanban', [
            
        ]);
    }
}
