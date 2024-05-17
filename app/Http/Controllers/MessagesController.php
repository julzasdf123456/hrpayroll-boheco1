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
use App\Models\MessageHeads;
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
        $receiver = $request['Receiver'];
        $sender = $request['Sender'];

        $data = DB::table('Messages')
            ->whereRaw("(Sender='" . $sender . "' AND Receiver='" . $receiver . "') OR (Sender='" . $receiver . "' AND Receiver='" . $sender . "')")
            ->orderByDesc('created_at')
            ->get();

        return response()->json($data, 200);
    }

    public function storeMessages(Request $request) {
        $receiver = $request['Receiver'];
        // save message header
        $checkReceiver = MessageHeads::where('Receiver', $receiver)
            ->where('Sender', Auth::id())
            ->first();
        if ($checkReceiver == null) {
            $checkReceiver = new MessageHeads;
            $checkReceiver->id = IDGenerator::generateIDandRandString();
            $checkReceiver->Sender = Auth::id();
            $checkReceiver->Receiver = $receiver;
            $checkReceiver->LatestMessage = $request['Message'];
            $checkReceiver->save();
        } else {
            $checkReceiver->LatestMessage = $request['Message'];
            $checkReceiver->save();
        }

        $checkSender = MessageHeads::where('Receiver', Auth::id())
            ->where('Sender', $receiver)
            ->first();
        if ($checkSender == null) {
            $checkSender = new MessageHeads;
            $checkSender->id = IDGenerator::generateIDandRandString();
            $checkSender->Sender = $receiver;
            $checkSender->Receiver = Auth::id();
            $checkSender->LatestMessage = $request['Message'];
            $checkSender->save();
        } else {
            $checkSender->LatestMessage = $request['Message'];
            $checkSender->save();
        }

        // save message
        $id = IDGenerator::generateIDandRandString();
        $messages = new Messages;
        $messages->id = $id;
        $messages->Sender = Auth::id();
        $messages->Receiver = $receiver;
        $messages->Message = $request['Message'];
        $messages->save();

        $message = Messages::findOrFail($id);

        // broadcast message
        broadcast(new MessageSent($message))->toOthers();

        return response()->json($messages, 200);
    }

    public function getHeaderThreads(Request $request) {
        // $latestMessages = DB::table(DB::raw("(SELECT m.id, m.Sender, m.Receiver, m.Message, m.created_at, ROW_NUMBER() OVER (PARTITION BY m.Sender, m.Receiver ORDER BY m.created_at DESC) as rn FROM messages as m) as lm"))
        //     ->where('lm.rn', 1)
        //     ->join('users as u1', 'lm.Sender', '=', 'u1.id')
        //     ->join('users as u2', 'lm.Receiver', '=', 'u2.id')
        //     ->select('u1.id as sender_id', 'u1.name as sender_name', 'u2.id as receiver_id', 'u2.name as receiver_name', 'lm.Message', 'lm.created_at')
        //     ->orderBy('lm.created_at')
        //     ->get();

        $data = DB::table('MessageHeads')
            ->leftJoin('users', 'MessageHeads.Receiver', '=', 'users.id')
            ->where('Sender', Auth::id())
            ->select(
                'users.name',
                'MessageHeads.*'
            )
            ->orderByDesc('created_at')
            ->get();

        return response()->json($data, 200);
    }

    public function getUsers(Request $request) {
        return response()->json(DB::table('users')->orderBy('name')->get(), 200);
    }
}
