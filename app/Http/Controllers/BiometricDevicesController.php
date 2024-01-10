<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBiometricDevicesRequest;
use App\Http\Requests\UpdateBiometricDevicesRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\BiometricDevicesRepository;
use Illuminate\Http\Request;
use Flash;
use App\Models\BiometricDevices;
use App\Models\IDGenerator;

class BiometricDevicesController extends AppBaseController
{
    /** @var BiometricDevicesRepository $biometricDevicesRepository*/
    private $biometricDevicesRepository;

    public function __construct(BiometricDevicesRepository $biometricDevicesRepo)
    {
        $this->biometricDevicesRepository = $biometricDevicesRepo;
    }

    /**
     * Display a listing of the BiometricDevices.
     */
    public function index(Request $request)
    {
        $biometricDevices = $this->biometricDevicesRepository->paginate(10);

        return view('biometric_devices.index')
            ->with('biometricDevices', $biometricDevices);
    }

    /**
     * Show the form for creating a new BiometricDevices.
     */
    public function create()
    {
        return view('biometric_devices.create');
    }

    /**
     * Store a newly created BiometricDevices in storage.
     */
    public function store(CreateBiometricDevicesRequest $request)
    {
        $input = $request->all();

        $biometricDevices = $this->biometricDevicesRepository->create($input);

        Flash::success('Biometric Devices saved successfully.');

        return redirect(route('biometricDevices.index'));
    }

    /**
     * Display the specified BiometricDevices.
     */
    public function show($id)
    {
        $biometricDevices = $this->biometricDevicesRepository->find($id);

        if (empty($biometricDevices)) {
            Flash::error('Biometric Devices not found');

            return redirect(route('biometricDevices.index'));
        }

        return view('biometric_devices.show')->with('biometricDevices', $biometricDevices);
    }

    /**
     * Show the form for editing the specified BiometricDevices.
     */
    public function edit($id)
    {
        $biometricDevices = $this->biometricDevicesRepository->find($id);

        if (empty($biometricDevices)) {
            Flash::error('Biometric Devices not found');

            return redirect(route('biometricDevices.index'));
        }

        return view('biometric_devices.edit')->with('biometricDevices', $biometricDevices);
    }

    /**
     * Update the specified BiometricDevices in storage.
     */
    public function update($id, UpdateBiometricDevicesRequest $request)
    {
        $biometricDevices = $this->biometricDevicesRepository->find($id);

        if (empty($biometricDevices)) {
            Flash::error('Biometric Devices not found');

            return redirect(route('biometricDevices.index'));
        }

        $biometricDevices = $this->biometricDevicesRepository->update($request->all(), $id);

        Flash::success('Biometric Devices updated successfully.');

        return redirect(route('biometricDevices.index'));
    }

    /**
     * Remove the specified BiometricDevices from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $biometricDevices = $this->biometricDevicesRepository->find($id);

        if (empty($biometricDevices)) {
            Flash::error('Biometric Devices not found');

            return redirect(route('biometricDevices.index'));
        }

        $this->biometricDevicesRepository->delete($id);

        Flash::success('Biometric Devices deleted successfully.');

        return redirect(route('biometricDevices.index'));
    }
}
