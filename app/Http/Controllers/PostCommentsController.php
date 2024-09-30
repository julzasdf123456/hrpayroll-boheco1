<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostCommentsRequest;
use App\Http\Requests\UpdatePostCommentsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\PostCommentsRepository;
use Illuminate\Http\Request;
use Flash;

class PostCommentsController extends AppBaseController
{
    /** @var PostCommentsRepository $postCommentsRepository*/
    private $postCommentsRepository;

    public function __construct(PostCommentsRepository $postCommentsRepo)
    {
        $this->middleware('auth');
        $this->postCommentsRepository = $postCommentsRepo;
    }

    /**
     * Display a listing of the PostComments.
     */
    public function index(Request $request)
    {
        $postComments = $this->postCommentsRepository->paginate(10);

        return view('post_comments.index')
            ->with('postComments', $postComments);
    }

    /**
     * Show the form for creating a new PostComments.
     */
    public function create()
    {
        return view('post_comments.create');
    }

    /**
     * Store a newly created PostComments in storage.
     */
    public function store(CreatePostCommentsRequest $request)
    {
        $input = $request->all();

        $postComments = $this->postCommentsRepository->create($input);

        Flash::success('Post Comments saved successfully.');

        return redirect(route('postComments.index'));
    }

    /**
     * Display the specified PostComments.
     */
    public function show($id)
    {
        $postComments = $this->postCommentsRepository->find($id);

        if (empty($postComments)) {
            Flash::error('Post Comments not found');

            return redirect(route('postComments.index'));
        }

        return view('post_comments.show')->with('postComments', $postComments);
    }

    /**
     * Show the form for editing the specified PostComments.
     */
    public function edit($id)
    {
        $postComments = $this->postCommentsRepository->find($id);

        if (empty($postComments)) {
            Flash::error('Post Comments not found');

            return redirect(route('postComments.index'));
        }

        return view('post_comments.edit')->with('postComments', $postComments);
    }

    /**
     * Update the specified PostComments in storage.
     */
    public function update($id, UpdatePostCommentsRequest $request)
    {
        $postComments = $this->postCommentsRepository->find($id);

        if (empty($postComments)) {
            Flash::error('Post Comments not found');

            return redirect(route('postComments.index'));
        }

        $postComments = $this->postCommentsRepository->update($request->all(), $id);

        Flash::success('Post Comments updated successfully.');

        return redirect(route('postComments.index'));
    }

    /**
     * Remove the specified PostComments from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $postComments = $this->postCommentsRepository->find($id);

        if (empty($postComments)) {
            Flash::error('Post Comments not found');

            return redirect(route('postComments.index'));
        }

        $this->postCommentsRepository->delete($id);

        Flash::success('Post Comments deleted successfully.');

        return redirect(route('postComments.index'));
    }
}
