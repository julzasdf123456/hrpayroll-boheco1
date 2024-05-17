<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMessageHeadsRequest;
use App\Http\Requests\UpdateMessageHeadsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\MessageHeadsRepository;
use Illuminate\Http\Request;
use Flash;

class MessageHeadsController extends AppBaseController
{
    /** @var MessageHeadsRepository $messageHeadsRepository*/
    private $messageHeadsRepository;

    public function __construct(MessageHeadsRepository $messageHeadsRepo)
    {
        $this->middleware('auth');
        $this->messageHeadsRepository = $messageHeadsRepo;
    }

    /**
     * Display a listing of the MessageHeads.
     */
    public function index(Request $request)
    {
        $messageHeads = $this->messageHeadsRepository->paginate(10);

        return view('message_heads.index')
            ->with('messageHeads', $messageHeads);
    }

    /**
     * Show the form for creating a new MessageHeads.
     */
    public function create()
    {
        return view('message_heads.create');
    }

    /**
     * Store a newly created MessageHeads in storage.
     */
    public function store(CreateMessageHeadsRequest $request)
    {
        $input = $request->all();

        $messageHeads = $this->messageHeadsRepository->create($input);

        Flash::success('Message Heads saved successfully.');

        return redirect(route('messageHeads.index'));
    }

    /**
     * Display the specified MessageHeads.
     */
    public function show($id)
    {
        $messageHeads = $this->messageHeadsRepository->find($id);

        if (empty($messageHeads)) {
            Flash::error('Message Heads not found');

            return redirect(route('messageHeads.index'));
        }

        return view('message_heads.show')->with('messageHeads', $messageHeads);
    }

    /**
     * Show the form for editing the specified MessageHeads.
     */
    public function edit($id)
    {
        $messageHeads = $this->messageHeadsRepository->find($id);

        if (empty($messageHeads)) {
            Flash::error('Message Heads not found');

            return redirect(route('messageHeads.index'));
        }

        return view('message_heads.edit')->with('messageHeads', $messageHeads);
    }

    /**
     * Update the specified MessageHeads in storage.
     */
    public function update($id, UpdateMessageHeadsRequest $request)
    {
        $messageHeads = $this->messageHeadsRepository->find($id);

        if (empty($messageHeads)) {
            Flash::error('Message Heads not found');

            return redirect(route('messageHeads.index'));
        }

        $messageHeads = $this->messageHeadsRepository->update($request->all(), $id);

        Flash::success('Message Heads updated successfully.');

        return redirect(route('messageHeads.index'));
    }

    /**
     * Remove the specified MessageHeads from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $messageHeads = $this->messageHeadsRepository->find($id);

        if (empty($messageHeads)) {
            Flash::error('Message Heads not found');

            return redirect(route('messageHeads.index'));
        }

        $this->messageHeadsRepository->delete($id);

        Flash::success('Message Heads deleted successfully.');

        return redirect(route('messageHeads.index'));
    }
}
