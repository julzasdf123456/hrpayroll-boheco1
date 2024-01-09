<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRankingRepositoryRequest;
use App\Http\Requests\UpdateRankingRepositoryRequest;
use App\Repositories\RankingRepositoryRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class RankingRepositoryController extends AppBaseController
{
    /** @var  RankingRepositoryRepository */
    private $rankingRepositoryRepository;

    public function __construct(RankingRepositoryRepository $rankingRepositoryRepo)
    {
        $this->rankingRepositoryRepository = $rankingRepositoryRepo;
    }

    /**
     * Display a listing of the RankingRepository.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $rankingRepositories = $this->rankingRepositoryRepository->all();

        return view('ranking_repositories.index')
            ->with('rankingRepositories', $rankingRepositories);
    }

    /**
     * Show the form for creating a new RankingRepository.
     *
     * @return Response
     */
    public function create()
    {
        return view('ranking_repositories.create');
    }

    /**
     * Store a newly created RankingRepository in storage.
     *
     * @param CreateRankingRepositoryRequest $request
     *
     * @return Response
     */
    public function store(CreateRankingRepositoryRequest $request)
    {
        $input = $request->all();

        $rankingRepository = $this->rankingRepositoryRepository->create($input);

        Flash::success('Ranking Repository saved successfully.');

        return redirect(route('rankingRepositories.index'));
    }

    /**
     * Display the specified RankingRepository.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $rankingRepository = $this->rankingRepositoryRepository->find($id);

        if (empty($rankingRepository)) {
            Flash::error('Ranking Repository not found');

            return redirect(route('rankingRepositories.index'));
        }

        return view('ranking_repositories.show')->with('rankingRepository', $rankingRepository);
    }

    /**
     * Show the form for editing the specified RankingRepository.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $rankingRepository = $this->rankingRepositoryRepository->find($id);

        if (empty($rankingRepository)) {
            Flash::error('Ranking Repository not found');

            return redirect(route('rankingRepositories.index'));
        }

        return view('ranking_repositories.edit')->with('rankingRepository', $rankingRepository);
    }

    /**
     * Update the specified RankingRepository in storage.
     *
     * @param int $id
     * @param UpdateRankingRepositoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRankingRepositoryRequest $request)
    {
        $rankingRepository = $this->rankingRepositoryRepository->find($id);

        if (empty($rankingRepository)) {
            Flash::error('Ranking Repository not found');

            return redirect(route('rankingRepositories.index'));
        }

        $rankingRepository = $this->rankingRepositoryRepository->update($request->all(), $id);

        Flash::success('Ranking Repository updated successfully.');

        return redirect(route('rankingRepositories.index'));
    }

    /**
     * Remove the specified RankingRepository from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $rankingRepository = $this->rankingRepositoryRepository->find($id);

        if (empty($rankingRepository)) {
            Flash::error('Ranking Repository not found');

            return redirect(route('rankingRepositories.index'));
        }

        $this->rankingRepositoryRepository->delete($id);

        Flash::success('Ranking Repository deleted successfully.');

        return redirect(route('rankingRepositories.index'));
    }
}
