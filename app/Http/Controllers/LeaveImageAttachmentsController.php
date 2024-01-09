<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLeaveImageAttachmentsRequest;
use App\Http\Requests\UpdateLeaveImageAttachmentsRequest;
use App\Repositories\LeaveImageAttachmentsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class LeaveImageAttachmentsController extends AppBaseController
{
    /** @var  LeaveImageAttachmentsRepository */
    private $leaveImageAttachmentsRepository;

    public function __construct(LeaveImageAttachmentsRepository $leaveImageAttachmentsRepo)
    {
        $this->middleware('auth');
        $this->leaveImageAttachmentsRepository = $leaveImageAttachmentsRepo;
    }

    /**
     * Display a listing of the LeaveImageAttachments.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $leaveImageAttachments = $this->leaveImageAttachmentsRepository->all();

        return view('leave_image_attachments.index')
            ->with('leaveImageAttachments', $leaveImageAttachments);
    }

    /**
     * Show the form for creating a new LeaveImageAttachments.
     *
     * @return Response
     */
    public function create()
    {
        return view('leave_image_attachments.create');
    }

    /**
     * Store a newly created LeaveImageAttachments in storage.
     *
     * @param CreateLeaveImageAttachmentsRequest $request
     *
     * @return Response
     */
    public function store(CreateLeaveImageAttachmentsRequest $request)
    {
        $input = $request->all();

        $leaveImageAttachments = $this->leaveImageAttachmentsRepository->create($input);

        Flash::success('Leave Image Attachments saved successfully.');

        return redirect(route('leaveImageAttachments.index'));
    }

    /**
     * Display the specified LeaveImageAttachments.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $leaveImageAttachments = $this->leaveImageAttachmentsRepository->find($id);

        if (empty($leaveImageAttachments)) {
            Flash::error('Leave Image Attachments not found');

            return redirect(route('leaveImageAttachments.index'));
        }

        return view('leave_image_attachments.show')->with('leaveImageAttachments', $leaveImageAttachments);
    }

    /**
     * Show the form for editing the specified LeaveImageAttachments.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $leaveImageAttachments = $this->leaveImageAttachmentsRepository->find($id);

        if (empty($leaveImageAttachments)) {
            Flash::error('Leave Image Attachments not found');

            return redirect(route('leaveImageAttachments.index'));
        }

        return view('leave_image_attachments.edit')->with('leaveImageAttachments', $leaveImageAttachments);
    }

    /**
     * Update the specified LeaveImageAttachments in storage.
     *
     * @param int $id
     * @param UpdateLeaveImageAttachmentsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLeaveImageAttachmentsRequest $request)
    {
        $leaveImageAttachments = $this->leaveImageAttachmentsRepository->find($id);

        if (empty($leaveImageAttachments)) {
            Flash::error('Leave Image Attachments not found');

            return redirect(route('leaveImageAttachments.index'));
        }

        $leaveImageAttachments = $this->leaveImageAttachmentsRepository->update($request->all(), $id);

        Flash::success('Leave Image Attachments updated successfully.');

        return redirect(route('leaveImageAttachments.index'));
    }

    /**
     * Remove the specified LeaveImageAttachments from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $leaveImageAttachments = $this->leaveImageAttachmentsRepository->find($id);

        if (empty($leaveImageAttachments)) {
            Flash::error('Leave Image Attachments not found');

            return redirect(route('leaveImageAttachments.index'));
        }

        $this->leaveImageAttachmentsRepository->delete($id);

        Flash::success('Leave Image Attachments deleted successfully.');

        return redirect(route('leaveImageAttachments.index'));
    }
}
