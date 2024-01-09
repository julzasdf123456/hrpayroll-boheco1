<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRankingsRequest;
use App\Http\Requests\UpdateRankingsRequest;
use App\Repositories\RankingsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class RankingsController extends AppBaseController
{
    /** @var  RankingsRepository */
    private $rankingsRepository;

    public function __construct(RankingsRepository $rankingsRepo)
    {
        $this->rankingsRepository = $rankingsRepo;
    }

    /**
     * Display a listing of the Rankings.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $rankings = $this->rankingsRepository->all();

        return view('rankings.index')
            ->with('rankings', $rankings);
    }

    /**
     * Show the form for creating a new Rankings.
     *
     * @return Response
     */
    public function create()
    {
        return view('rankings.create');
    }

    /**
     * Store a newly created Rankings in storage.
     *
     * @param CreateRankingsRequest $request
     *
     * @return Response
     */
    public function store(CreateRankingsRequest $request)
    {
        $input = $request->all();

        $rankings = $this->rankingsRepository->create($input);

        Flash::success('Rankings saved successfully.');

        return redirect(route('rankings.index'));
    }

    /**
     * Display the specified Rankings.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $rankings = $this->rankingsRepository->find($id);

        if (empty($rankings)) {
            Flash::error('Rankings not found');

            return redirect(route('rankings.index'));
        }

        return view('rankings.show')->with('rankings', $rankings);
    }

    /**
     * Show the form for editing the specified Rankings.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $rankings = $this->rankingsRepository->find($id);

        if (empty($rankings)) {
            Flash::error('Rankings not found');

            return redirect(route('rankings.index'));
        }

        return view('rankings.edit')->with('rankings', $rankings);
    }

    /**
     * Update the specified Rankings in storage.
     *
     * @param int $id
     * @param UpdateRankingsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRankingsRequest $request)
    {
        $rankings = $this->rankingsRepository->find($id);

        if (empty($rankings)) {
            Flash::error('Rankings not found');

            return redirect(route('rankings.index'));
        }

        $rankings = $this->rankingsRepository->update($request->all(), $id);

        Flash::success('Rankings updated successfully.');

        return redirect(route('rankings.index'));
    }

    /**
     * Remove the specified Rankings from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $rankings = $this->rankingsRepository->find($id);

        if (empty($rankings)) {
            Flash::error('Rankings not found');

            return redirect(route('rankings.index'));
        }

        $this->rankingsRepository->delete($id);

        Flash::success('Rankings deleted successfully.');

        // return redirect(route('rankings.index'));
        
        return response()->json(['response' => 'ok'], 200);
    }
}
