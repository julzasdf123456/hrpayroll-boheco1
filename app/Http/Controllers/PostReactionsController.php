<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostReactionsRequest;
use App\Http\Requests\UpdatePostReactionsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\PostReactionsRepository;
use Illuminate\Http\Request;
use Flash;

class PostReactionsController extends AppBaseController
{
    /** @var PostReactionsRepository $postReactionsRepository*/
    private $postReactionsRepository;

    public function __construct(PostReactionsRepository $postReactionsRepo)
    {
        $this->middleware('auth');
        $this->postReactionsRepository = $postReactionsRepo;
    }

    /**
     * Display a listing of the PostReactions.
     */
    public function index(Request $request)
    {
        $postReactions = $this->postReactionsRepository->paginate(10);

        return view('post_reactions.index')
            ->with('postReactions', $postReactions);
    }

    /**
     * Show the form for creating a new PostReactions.
     */
    public function create()
    {
        return view('post_reactions.create');
    }

    /**
     * Store a newly created PostReactions in storage.
     */
    public function store(CreatePostReactionsRequest $request)
    {
        $input = $request->all();

        $postReactions = $this->postReactionsRepository->create($input);

        Flash::success('Post Reactions saved successfully.');

        return redirect(route('postReactions.index'));
    }

    /**
     * Display the specified PostReactions.
     */
    public function show($id)
    {
        $postReactions = $this->postReactionsRepository->find($id);

        if (empty($postReactions)) {
            Flash::error('Post Reactions not found');

            return redirect(route('postReactions.index'));
        }

        return view('post_reactions.show')->with('postReactions', $postReactions);
    }

    /**
     * Show the form for editing the specified PostReactions.
     */
    public function edit($id)
    {
        $postReactions = $this->postReactionsRepository->find($id);

        if (empty($postReactions)) {
            Flash::error('Post Reactions not found');

            return redirect(route('postReactions.index'));
        }

        return view('post_reactions.edit')->with('postReactions', $postReactions);
    }

    /**
     * Update the specified PostReactions in storage.
     */
    public function update($id, UpdatePostReactionsRequest $request)
    {
        $postReactions = $this->postReactionsRepository->find($id);

        if (empty($postReactions)) {
            Flash::error('Post Reactions not found');

            return redirect(route('postReactions.index'));
        }

        $postReactions = $this->postReactionsRepository->update($request->all(), $id);

        Flash::success('Post Reactions updated successfully.');

        return redirect(route('postReactions.index'));
    }

    /**
     * Remove the specified PostReactions from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $postReactions = $this->postReactionsRepository->find($id);

        if (empty($postReactions)) {
            Flash::error('Post Reactions not found');

            return redirect(route('postReactions.index'));
        }

        $this->postReactionsRepository->delete($id);

        Flash::success('Post Reactions deleted successfully.');

        return redirect(route('postReactions.index'));
    }
}
