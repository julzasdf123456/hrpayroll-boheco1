<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLeaveSignatoriesRepositoryRequest;
use App\Http\Requests\UpdateLeaveSignatoriesRepositoryRequest;
use App\Repositories\LeaveSignatoriesRepositoryRepository;
use App\Http\Controllers\AppBaseController;
use App\Models\LeaveSignatoriesRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Flash;
use Response;

class LeaveSignatoriesRepositoryController extends AppBaseController
{
    /** @var  LeaveSignatoriesRepositoryRepository */
    private $leaveSignatoriesRepositoryRepository;

    public function __construct(LeaveSignatoriesRepositoryRepository $leaveSignatoriesRepositoryRepo)
    {
        $this->leaveSignatoriesRepositoryRepository = $leaveSignatoriesRepositoryRepo;
    }

    /**
     * Display a listing of the LeaveSignatoriesRepository.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $leaveSignatoriesRepositories = DB::table('LeaveSignatoriesRepository')
            ->leftJoin('users', 'LeaveSignatoriesRepository.UserId', '=', 'users.id')
            ->select('LeaveSignatoriesRepository.id', 'users.id AS userid', 'users.name')
            ->get();

        return view('leave_signatories_repositories.index', [
            'leaveSignatoriesRepositories' => $leaveSignatoriesRepositories
        ]);
    }

    /**
     * Show the form for creating a new LeaveSignatoriesRepository.
     *
     * @return Response
     */
    public function create()
    {
        $users = DB::table('users')
            ->whereNotIn('users.id', function($q) {
                $q->select('UserId')->from('LeaveSignatoriesRepository');
            })
            ->leftJoin('Employees', 'users.employee_id', '=', 'Employees.id')
            ->pluck( 'users.name', 'users.id');

        return view('leave_signatories_repositories.create', [
            'users' => $users,
        ]);
    }

    /**
     * Store a newly created LeaveSignatoriesRepository in storage.
     *
     * @param CreateLeaveSignatoriesRepositoryRequest $request
     *
     * @return Response
     */
    public function store(CreateLeaveSignatoriesRepositoryRequest $request)
    {
        $input = $request->all();

        $leaveSignatoriesRepository = $this->leaveSignatoriesRepositoryRepository->create($input);

        Flash::success('Leave Signatories Repository saved successfully.');

        return redirect(route('leaveSignatoriesRepositories.index'));
    }

    /**
     * Display the specified LeaveSignatoriesRepository.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $leaveSignatoriesRepository = $this->leaveSignatoriesRepositoryRepository->find($id);

        if (empty($leaveSignatoriesRepository)) {
            Flash::error('Leave Signatories Repository not found');

            return redirect(route('leaveSignatoriesRepositories.index'));
        }

        return view('leave_signatories_repositories.show')->with('leaveSignatoriesRepository', $leaveSignatoriesRepository);
    }

    /**
     * Show the form for editing the specified LeaveSignatoriesRepository.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $leaveSignatoriesRepository = $this->leaveSignatoriesRepositoryRepository->find($id);

        if (empty($leaveSignatoriesRepository)) {
            Flash::error('Leave Signatories Repository not found');

            return redirect(route('leaveSignatoriesRepositories.index'));
        }

        return view('leave_signatories_repositories.edit')->with('leaveSignatoriesRepository', $leaveSignatoriesRepository);
    }

    /**
     * Update the specified LeaveSignatoriesRepository in storage.
     *
     * @param int $id
     * @param UpdateLeaveSignatoriesRepositoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLeaveSignatoriesRepositoryRequest $request)
    {
        $leaveSignatoriesRepository = $this->leaveSignatoriesRepositoryRepository->find($id);

        if (empty($leaveSignatoriesRepository)) {
            Flash::error('Leave Signatories Repository not found');

            return redirect(route('leaveSignatoriesRepositories.index'));
        }

        $leaveSignatoriesRepository = $this->leaveSignatoriesRepositoryRepository->update($request->all(), $id);

        Flash::success('Leave Signatories Repository updated successfully.');

        return redirect(route('leaveSignatoriesRepositories.index'));
    }

    /**
     * Remove the specified LeaveSignatoriesRepository from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $leaveSignatoriesRepository = $this->leaveSignatoriesRepositoryRepository->find($id);

        if (empty($leaveSignatoriesRepository)) {
            Flash::error('Leave Signatories Repository not found');

            return redirect(route('leaveSignatoriesRepositories.index'));
        }

        $this->leaveSignatoriesRepositoryRepository->delete($id);

        Flash::success('Leave Signatories Repository deleted successfully.');

        return redirect(route('leaveSignatoriesRepositories.index'));
    }
}
