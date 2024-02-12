<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserFootprintsRequest;
use App\Http\Requests\UpdateUserFootprintsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\UserFootprintsRepository;
use Illuminate\Http\Request;
use Flash;

class UserFootprintsController extends AppBaseController
{
    /** @var UserFootprintsRepository $userFootprintsRepository*/
    private $userFootprintsRepository;

    public function __construct(UserFootprintsRepository $userFootprintsRepo)
    {
        $this->userFootprintsRepository = $userFootprintsRepo;
    }

    /**
     * Display a listing of the UserFootprints.
     */
    public function index(Request $request)
    {
        $this->middleware('auth');
        $userFootprints = $this->userFootprintsRepository->paginate(10);

        return view('user_footprints.index')
            ->with('userFootprints', $userFootprints);
    }

    /**
     * Show the form for creating a new UserFootprints.
     */
    public function create()
    {
        return view('user_footprints.create');
    }

    /**
     * Store a newly created UserFootprints in storage.
     */
    public function store(CreateUserFootprintsRequest $request)
    {
        $input = $request->all();

        $userFootprints = $this->userFootprintsRepository->create($input);

        Flash::success('User Footprints saved successfully.');

        return redirect(route('userFootprints.index'));
    }

    /**
     * Display the specified UserFootprints.
     */
    public function show($id)
    {
        $userFootprints = $this->userFootprintsRepository->find($id);

        if (empty($userFootprints)) {
            Flash::error('User Footprints not found');

            return redirect(route('userFootprints.index'));
        }

        return view('user_footprints.show')->with('userFootprints', $userFootprints);
    }

    /**
     * Show the form for editing the specified UserFootprints.
     */
    public function edit($id)
    {
        $userFootprints = $this->userFootprintsRepository->find($id);

        if (empty($userFootprints)) {
            Flash::error('User Footprints not found');

            return redirect(route('userFootprints.index'));
        }

        return view('user_footprints.edit')->with('userFootprints', $userFootprints);
    }

    /**
     * Update the specified UserFootprints in storage.
     */
    public function update($id, UpdateUserFootprintsRequest $request)
    {
        $userFootprints = $this->userFootprintsRepository->find($id);

        if (empty($userFootprints)) {
            Flash::error('User Footprints not found');

            return redirect(route('userFootprints.index'));
        }

        $userFootprints = $this->userFootprintsRepository->update($request->all(), $id);

        Flash::success('User Footprints updated successfully.');

        return redirect(route('userFootprints.index'));
    }

    /**
     * Remove the specified UserFootprints from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $userFootprints = $this->userFootprintsRepository->find($id);

        if (empty($userFootprints)) {
            Flash::error('User Footprints not found');

            return redirect(route('userFootprints.index'));
        }

        $this->userFootprintsRepository->delete($id);

        Flash::success('User Footprints deleted successfully.');

        return redirect(route('userFootprints.index'));
    }
}
