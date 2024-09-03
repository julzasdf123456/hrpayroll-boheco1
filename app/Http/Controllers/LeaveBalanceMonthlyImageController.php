<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLeaveBalanceMonthlyImageRequest;
use App\Http\Requests\UpdateLeaveBalanceMonthlyImageRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\LeaveBalanceMonthlyImageRepository;
use Illuminate\Http\Request;
use Flash;

class LeaveBalanceMonthlyImageController extends AppBaseController
{
    /** @var LeaveBalanceMonthlyImageRepository $leaveBalanceMonthlyImageRepository*/
    private $leaveBalanceMonthlyImageRepository;

    public function __construct(LeaveBalanceMonthlyImageRepository $leaveBalanceMonthlyImageRepo)
    {
        $this->middleware('auth');
        $this->leaveBalanceMonthlyImageRepository = $leaveBalanceMonthlyImageRepo;
    }

    /**
     * Display a listing of the LeaveBalanceMonthlyImage.
     */
    public function index(Request $request)
    {
        $leaveBalanceMonthlyImages = $this->leaveBalanceMonthlyImageRepository->paginate(10);

        return view('leave_balance_monthly_images.index')
            ->with('leaveBalanceMonthlyImages', $leaveBalanceMonthlyImages);
    }

    /**
     * Show the form for creating a new LeaveBalanceMonthlyImage.
     */
    public function create()
    {
        return view('leave_balance_monthly_images.create');
    }

    /**
     * Store a newly created LeaveBalanceMonthlyImage in storage.
     */
    public function store(CreateLeaveBalanceMonthlyImageRequest $request)
    {
        $input = $request->all();

        $leaveBalanceMonthlyImage = $this->leaveBalanceMonthlyImageRepository->create($input);

        Flash::success('Leave Balance Monthly Image saved successfully.');

        return redirect(route('leaveBalanceMonthlyImages.index'));
    }

    /**
     * Display the specified LeaveBalanceMonthlyImage.
     */
    public function show($id)
    {
        $leaveBalanceMonthlyImage = $this->leaveBalanceMonthlyImageRepository->find($id);

        if (empty($leaveBalanceMonthlyImage)) {
            Flash::error('Leave Balance Monthly Image not found');

            return redirect(route('leaveBalanceMonthlyImages.index'));
        }

        return view('leave_balance_monthly_images.show')->with('leaveBalanceMonthlyImage', $leaveBalanceMonthlyImage);
    }

    /**
     * Show the form for editing the specified LeaveBalanceMonthlyImage.
     */
    public function edit($id)
    {
        $leaveBalanceMonthlyImage = $this->leaveBalanceMonthlyImageRepository->find($id);

        if (empty($leaveBalanceMonthlyImage)) {
            Flash::error('Leave Balance Monthly Image not found');

            return redirect(route('leaveBalanceMonthlyImages.index'));
        }

        return view('leave_balance_monthly_images.edit')->with('leaveBalanceMonthlyImage', $leaveBalanceMonthlyImage);
    }

    /**
     * Update the specified LeaveBalanceMonthlyImage in storage.
     */
    public function update($id, UpdateLeaveBalanceMonthlyImageRequest $request)
    {
        $leaveBalanceMonthlyImage = $this->leaveBalanceMonthlyImageRepository->find($id);

        if (empty($leaveBalanceMonthlyImage)) {
            Flash::error('Leave Balance Monthly Image not found');

            return redirect(route('leaveBalanceMonthlyImages.index'));
        }

        $leaveBalanceMonthlyImage = $this->leaveBalanceMonthlyImageRepository->update($request->all(), $id);

        Flash::success('Leave Balance Monthly Image updated successfully.');

        return redirect(route('leaveBalanceMonthlyImages.index'));
    }

    /**
     * Remove the specified LeaveBalanceMonthlyImage from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $leaveBalanceMonthlyImage = $this->leaveBalanceMonthlyImageRepository->find($id);

        if (empty($leaveBalanceMonthlyImage)) {
            Flash::error('Leave Balance Monthly Image not found');

            return redirect(route('leaveBalanceMonthlyImages.index'));
        }

        $this->leaveBalanceMonthlyImageRepository->delete($id);

        Flash::success('Leave Balance Monthly Image deleted successfully.');

        return redirect(route('leaveBalanceMonthlyImages.index'));
    }
}
