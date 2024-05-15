<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMessagesRequest;
use App\Http\Requests\UpdateMessagesRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\MessagesRepository;
use Illuminate\Http\Request;
use App\Models\IDGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Events\MessageSent;
use App\Models\Messages;
use Flash;

class MessagesController extends AppBaseController
{
    /** @var MessagesRepository $messagesRepository*/
    private $messagesRepository;

    public function __construct(MessagesRepository $messagesRepo)
    {
        $this->middleware('auth');
        $this->messagesRepository = $messagesRepo;
    }

    /**
     * Display a listing of the Messages.
     */
    public function index(Request $request)
    {
        return Messages::orderByDesc('created_at')->get();
    }

    /**
     * Show the form for creating a new Messages.
     */
    public function create()
    {
        return view('messages.create');
    }

    /**
     * Store a newly created Messages in storage.
     */
    public function store(CreateMessagesRequest $request)
    {
        $input = $request->all();
        $input['id'] = IDGenerator::generateIDandRandString();
        $input['Sender'] = Auth::id();
        $input['Receiver'] = '1';

        $messages = $this->messagesRepository->create($input);

        broadcast(new MessageSent($messages))->toOthers();

        return response()->json($messages, 200);
    }

    /**
     * Display the specified Messages.
     */
    public function show($id)
    {
        $messages = $this->messagesRepository->find($id);

        if (empty($messages)) {
            Flash::error('Messages not found');

            return redirect(route('messages.index'));
        }

        return view('messages.show')->with('messages', $messages);
    }

    /**
     * Show the form for editing the specified Messages.
     */
    public function edit($id)
    {
        $messages = $this->messagesRepository->find($id);

        if (empty($messages)) {
            Flash::error('Messages not found');

            return redirect(route('messages.index'));
        }

        return view('messages.edit')->with('messages', $messages);
    }

    /**
     * Update the specified Messages in storage.
     */
    public function update($id, UpdateMessagesRequest $request)
    {
        $messages = $this->messagesRepository->find($id);

        if (empty($messages)) {
            Flash::error('Messages not found');

            return redirect(route('messages.index'));
        }

        $messages = $this->messagesRepository->update($request->all(), $id);

        Flash::success('Messages updated successfully.');

        return redirect(route('messages.index'));
    }

    /**
     * Remove the specified Messages from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $messages = $this->messagesRepository->find($id);

        if (empty($messages)) {
            Flash::error('Messages not found');

            return redirect(route('messages.index'));
        }

        $this->messagesRepository->delete($id);

        Flash::success('Messages deleted successfully.');

        return redirect(route('messages.index'));
    }

    public function getMessageThread(Request $request) {
        $data = DB::table('Messages')
            ->orderByDesc('created_at')
            ->get();

        return response()->json($data, 200);
    }

    public function storeMessages(Request $request) {
        $id = IDGenerator::generateIDandRandString();
        $messages = new Messages;
        $messages->id = $id;
        $messages->Sender = Auth::id();
        $messages->Receiver = '1';
        $messages->Message = $request['Message'];
        $messages->save();

        $message = Messages::findOrFail($id);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($messages, 200);
    }
}
