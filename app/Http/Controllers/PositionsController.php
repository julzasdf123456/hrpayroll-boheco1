<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePositionsRequest;
use App\Http\Requests\UpdatePositionsRequest;
use App\Repositories\PositionsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Positions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Flash;
use Response;

class PositionsController extends AppBaseController
{
    /** @var  PositionsRepository */
    private $positionsRepository;

    public function __construct(PositionsRepository $positionsRepo)
    {
        $this->middleware('auth');
        $this->positionsRepository = $positionsRepo;
    }

    /**
     * Display a listing of the Positions.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $positions = $this->positionsRepository->all();

        return view('positions.index', [
            'positions' => $positions,
            'supers' => Positions::whereIn('Level', ['General Manager', 'Manager', 'Chief', 'Head'])->get(),
        ]);
    }

    /**
     * Show the form for creating a new Positions.
     *
     * @return Response
     */
    public function create()
    {
        $position = DB::table('Positions')
            ->whereRaw("Level IN ('Managerial', 'Chief', 'Supervisor')")
            ->select('*')
            ->orderBy('Position')
            ->get();
            
        return view('positions.create', [
            'position' => $position,
        ]);
    }

    /**
     * Store a newly created Positions in storage.
     *
     * @param CreatePositionsRequest $request
     *
     * @return Response
     */
    public function store(CreatePositionsRequest $request)
    {
        $input = $request->all();

        $positions = $this->positionsRepository->create($input);

        Flash::success('Positions saved successfully.');

        return redirect(route('positions.index'));
    }

    /**
     * Display the specified Positions.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $positions = $this->positionsRepository->find($id);

        if (empty($positions)) {
            Flash::error('Positions not found');

            return redirect(route('positions.index'));
        }

        return view('positions.show')->with('positions', $positions);
    }

    /**
     * Show the form for editing the specified Positions.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $positions = $this->positionsRepository->find($id);

        if (empty($positions)) {
            Flash::error('Positions not found');

            return redirect(route('positions.index'));
        }

        $position = DB::table('Positions')
            ->whereRaw("Level IN ('Managerial', 'Chief', 'Supervisor')")
            ->select('*')
            ->orderBy('Position')
            ->get();

        return view('positions.edit', [
            'positions' => $positions,
            'position' => $position
        ]);
    }

    /**
     * Update the specified Positions in storage.
     *
     * @param int $id
     * @param UpdatePositionsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePositionsRequest $request)
    {
        $positions = $this->positionsRepository->find($id);

        if (empty($positions)) {
            Flash::error('Positions not found');

            return redirect(route('positions.index'));
        }

        $positions = $this->positionsRepository->update($request->all(), $id);

        Flash::success('Positions updated successfully.');

        return redirect(route('positions.index'));
    }

    /**
     * Remove the specified Positions from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $positions = $this->positionsRepository->find($id);

        if (empty($positions)) {
            Flash::error('Positions not found');

            return redirect(route('positions.index'));
        }

        $this->positionsRepository->delete($id);

        Flash::success('Positions deleted successfully.');

        return redirect(route('positions.index'));
    }

    public function updateSuper(Request $request) {
        $positionId = $request['PositionId'];
        $superId = $request['SuperId'];

        $position = Positions::find($positionId);

        if ($position != null) {
            $position->ParentPositionId = $superId;
            $position->save();
        }

        return response()->json($position, 200);
    }
}
