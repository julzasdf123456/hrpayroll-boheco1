<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateNotificationsRequest;
use App\Http\Requests\UpdateNotificationsRequest;
use App\Repositories\NotificationsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Notifications;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Employees;
use Flash;
use Response;

class NotificationsController extends AppBaseController
{
    /** @var  NotificationsRepository */
    private $notificationsRepository;

    public function __construct(NotificationsRepository $notificationsRepo)
    {
        $this->middleware('auth');
        $this->notificationsRepository = $notificationsRepo;
    }

    /**
     * Display a listing of the Notifications.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $notifications = $this->notificationsRepository->all();

        return view('notifications.index')
            ->with('notifications', $notifications);
    }

    /**
     * Show the form for creating a new Notifications.
     *
     * @return Response
     */
    public function create()
    {
        return view('notifications.create');
    }

    /**
     * Store a newly created Notifications in storage.
     *
     * @param CreateNotificationsRequest $request
     *
     * @return Response
     */
    public function store(CreateNotificationsRequest $request)
    {
        $input = $request->all();

        $users = DB::table('users')
            ->leftJoin('Employees', 'users.employee_id', '=', 'Employees.id')
            ->select('users.id')
            ->get();

        foreach($users as $item) {
            $notification = new Notifications;
            $notification->UserId = $item->id;
            $notification->Type = $input['Type'];
            $notification->Content = $input['Content'];
            $notification->Status = 'UNREAD';
            $notification->Notes = $notification->id;
            $notification->save();
        }

        // $notifications = $this->notificationsRepository->create($input);

        Flash::success('Notifications saved successfully.');

        return redirect(route('home'));
    }

    /**
     * Display the specified Notifications.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $notifications = $this->notificationsRepository->find($id);

        if (empty($notifications)) {
            Flash::error('Notifications not found');

            return redirect(route('notifications.index'));
        }

        return view('notifications.show')->with('notifications', $notifications);
    }

    /**
     * Show the form for editing the specified Notifications.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $notifications = $this->notificationsRepository->find($id);

        if (empty($notifications)) {
            Flash::error('Notifications not found');

            return redirect(route('notifications.index'));
        }

        return view('notifications.edit')->with('notifications', $notifications);
    }

    /**
     * Update the specified Notifications in storage.
     *
     * @param int $id
     * @param UpdateNotificationsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateNotificationsRequest $request)
    {
        $notifications = $this->notificationsRepository->find($id);

        if (empty($notifications)) {
            Flash::error('Notifications not found');

            return redirect(route('notifications.index'));
        }

        $notifications = $this->notificationsRepository->update($request->all(), $id);

        Flash::success('Notifications updated successfully.');

        return redirect(route('notifications.index'));
    }

    /**
     * Remove the specified Notifications from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $notifications = $this->notificationsRepository->find($id);

        if (empty($notifications)) {
            Flash::error('Notifications not found');

            return redirect(route('notifications.index'));
        }

        $this->notificationsRepository->delete($id);

        Flash::success('Notifications deleted successfully.');

        return redirect(route('notifications.index'));
    }

    public function getAllNotifications(Request $request) {
        if ($request->ajax()) {
            $notifications = Notifications::where('UserId', Auth::id())->orderByDesc('created_at')->limit(10)->get();

            $data = "";

            foreach($notifications as $item) {
                $data .= '<div class="dropdown-divider"></div>' .
                        '<a href="' . route('notifications.mark-as-read', [$item->id]) . '" class="dropdown-item ' . ($item->Status=="UNREAD" ? 'text-info' : '') . ' ellipsize">' .
                            '<i class="fas fa-envelope mr-2"></i> ' . $item->Content .
                        '</a>';                
            }

            return response()->json($data, 200);
        }
    }

    public function getNotifCounter(Request $request) {
        if ($request->ajax()) {
            $notifications = Notifications::where('UserId', Auth::id())->where('Status', 'UNREAD')->get();

            return response()->json(['res' => count($notifications)], 200);
        }
    }

    public function markAsRead($id) {
        $notifications = Notifications::find($id);
        $notifications->Status = 'READ';
        $notifications->save();

        return redirect(Notifications::assessNotificationRoute($notifications->Type, $notifications->Notes, $notifications->id));
    }
}
