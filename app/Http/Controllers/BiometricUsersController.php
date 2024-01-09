<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBiometricUsersRequest;
use App\Http\Requests\UpdateBiometricUsersRequest;
use App\Repositories\BiometricUsersRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class BiometricUsersController extends AppBaseController
{
    /** @var  BiometricUsersRepository */
    private $biometricUsersRepository;

    public function __construct(BiometricUsersRepository $biometricUsersRepo)
    {
        $this->middleware('auth');
        $this->biometricUsersRepository = $biometricUsersRepo;
    }

    /**
     * Display a listing of the BiometricUsers.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $biometricUsers = $this->biometricUsersRepository->all();

        return view('biometric_users.index')
            ->with('biometricUsers', $biometricUsers);
    }

    /**
     * Show the form for creating a new BiometricUsers.
     *
     * @return Response
     */
    public function create()
    {
        return view('biometric_users.create');
    }

    /**
     * Store a newly created BiometricUsers in storage.
     *
     * @param CreateBiometricUsersRequest $request
     *
     * @return Response
     */
    public function store(CreateBiometricUsersRequest $request)
    {
        $input = $request->all();

        $biometricUsers = $this->biometricUsersRepository->create($input);

        Flash::success('Biometric Users saved successfully.');

        return redirect(route('biometricUsers.index'));
    }

    /**
     * Display the specified BiometricUsers.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $biometricUsers = $this->biometricUsersRepository->find($id);

        if (empty($biometricUsers)) {
            Flash::error('Biometric Users not found');

            return redirect(route('biometricUsers.index'));
        }

        return view('biometric_users.show')->with('biometricUsers', $biometricUsers);
    }

    /**
     * Show the form for editing the specified BiometricUsers.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $biometricUsers = $this->biometricUsersRepository->find($id);

        if (empty($biometricUsers)) {
            Flash::error('Biometric Users not found');

            return redirect(route('biometricUsers.index'));
        }

        return view('biometric_users.edit')->with('biometricUsers', $biometricUsers);
    }

    /**
     * Update the specified BiometricUsers in storage.
     *
     * @param int $id
     * @param UpdateBiometricUsersRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBiometricUsersRequest $request)
    {
        $biometricUsers = $this->biometricUsersRepository->find($id);

        if (empty($biometricUsers)) {
            Flash::error('Biometric Users not found');

            return redirect(route('biometricUsers.index'));
        }

        $biometricUsers = $this->biometricUsersRepository->update($request->all(), $id);

        Flash::success('Biometric Users updated successfully.');

        return redirect(route('biometricUsers.index'));
    }

    /**
     * Remove the specified BiometricUsers from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $biometricUsers = $this->biometricUsersRepository->find($id);

        if (empty($biometricUsers)) {
            Flash::error('Biometric Users not found');

            return redirect(route('biometricUsers.index'));
        }

        $this->biometricUsersRepository->delete($id);

        Flash::success('Biometric Users deleted successfully.');

        return redirect(route('biometricUsers.index'));
    }
}
