<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\PostRepository;
use Illuminate\Http\Request;
use App\Models\IDGenerator;
use App\Models\Employees;
use App\Models\MemorandumEmployees;
use App\Models\SMSNotifications;
use App\Models\Post;
use App\Models\PostReactions;
use App\Models\PostComments;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Flash;
use File;

class PostController extends AppBaseController
{
    /** @var PostRepository $postRepository*/
    private $postRepository;

    public function __construct(PostRepository $postRepo)
    {
        $this->middleware('auth');
        $this->postRepository = $postRepo;
    }

    /**
     * Display a listing of the Post.
     */
    public function index(Request $request)
    {
        $posts = $this->postRepository->paginate(10);

        return view('posts.index')
            ->with('posts', $posts);
    }

    /**
     * Show the form for creating a new Post.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created Post in storage.
     */
    public function store(CreatePostRequest $request)
    {
        $input = $request->all();
        $input['PostUserId'] = Auth::id();

        $post = $this->postRepository->create($input);

        // store photos

        $uploadedImages = [];
        if ($request->hasFile('images')) {
            $path = Post::path() . $input['id'] . "/";
            File::makeDirectory($path, $mode = 0777, true, true);

            foreach ($request->file('images') as $key => $image) {
                $imageName = $key . '-' . $input['id'] . '.' . $image->getClientOriginalExtension();
                $image->move($path, $imageName);
            }
        }

        return redirect(route('posts.index'));
    }

    /**
     * Display the specified Post.
     */
    public function show($id)
    {
        $post = $this->postRepository->find($id);

        if (empty($post)) {
            Flash::error('Post not found');

            return redirect(route('posts.index'));
        }

        return view('posts.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified Post.
     */
    public function edit($id)
    {
        $post = $this->postRepository->find($id);

        if (empty($post)) {
            Flash::error('Post not found');

            return redirect(route('posts.index'));
        }

        return view('posts.edit')->with('post', $post);
    }

    /**
     * Update the specified Post in storage.
     */
    public function update($id, UpdatePostRequest $request)
    {
        $post = $this->postRepository->find($id);

        if (empty($post)) {
            Flash::error('Post not found');

            return redirect(route('posts.index'));
        }

        $post = $this->postRepository->update($request->all(), $id);

        Flash::success('Post updated successfully.');

        return redirect(route('posts.index'));
    }

    /**
     * Remove the specified Post from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $post = $this->postRepository->find($id);

        if (empty($post)) {
            Flash::error('Post not found');

            return redirect(route('posts.index'));
        }

        $this->postRepository->delete($id);

        Flash::success('Post deleted successfully.');

        return redirect(route('posts.index'));
    }

    public function getPosts(Request $request) {
        $takeAmount = intval($request['Take']);

        $posts = DB::table('Posts')
            ->leftJoin('users', 'Posts.PostUserId', '=', 'users.id')
            ->leftJoin('Employees', 'users.employee_id', '=', 'Employees.id')
            ->whereRaw("Posts.Privacy='Show to Everyone'")
            ->select(
                'Posts.*', 
                'Employees.FirstName', 
                'Employees.LastName', 
                'Employees.ProfilePicture',
                'Employees.id AS EmployeeId'
            )
            ->orderByDesc('Posts.created_at')
            ->take($takeAmount)
            ->get();

        foreach ($posts as $item) {
            $item->Reactions = PostReactions::where('PostId', $item->id)
                ->select('UserId')
                ->get();

            $item->LatestComments = DB::table('PostComments')
                ->leftJoin('users', 'PostComments.CommenterUserId', '=', 'users.id')
                ->leftJoin('Employees', 'users.employee_id', '=', 'Employees.id')
                ->whereRaw("PostComments.PostId='" . $item->id . "'")
                ->select(
                    'PostComments.*', 
                    'Employees.FirstName', 
                    'Employees.LastName', 
                    'Employees.ProfilePicture',
                    'Employees.id AS EmployeeId'
                )
                ->orderByDesc('PostComments.created_at')
                ->take(3)
                ->get();

            // get images
            $dir = Post::path() . $item->id;

            if (is_dir($dir)) {
                $files = scandir(Post::path() . $item->id);
                $files = array_diff($files, array('.', '..'));
                $imgs = [];
                foreach($files as $file) {
                    array_push($imgs, $file);
                }
                $item->Images = $imgs;
            }
        }

        return response()->json($posts, 200);
    }

    public function react(Request $request) {
        $type = $request['Type'];
        $postId = $request['PostId'];
        $userId = Auth::id();

        $reaction = PostReactions::where('UserId', $userId)->where('PostId', $postId)->first();
        $post = Post::find($postId);

        $reactionCount = 0;

        if ($reaction != null) {
            $reaction->delete();

            // update post react count
            $cnt = $post->ReactionCount != null ? intval($post->ReactionCount) : 0;
            if ($cnt > 0) {
                $reactionCount = $cnt - 1;
                $post->ReactionCount = $reactionCount;
            } else {
                $reactionCount = 0;
                $post->ReactionCount = 0;
            }
            $post->save();
        } else {
            $reaction = PostReactions::create([
                'id' => IDGenerator::generateIDandRandString(),
                'PostId' => $postId,
                'UserId' => $userId,
                'ReactionType' => $type
            ]);

            // updadte post react count
            $cnt = $post->ReactionCount != null ? intval($post->ReactionCount) : 0;
            $reactionCount = $cnt + 1;
            $post->ReactionCount = $reactionCount;
            $post->save();
        }

        return response()->json($reactionCount, 200);
    }

    public function comment(Request $request) {
        $input = $request->all();

        $userData = DB::table('users')
            ->leftJoin('Employees', 'users.employee_id', '=', 'Employees.id')
            ->whereRaw("users.id='" . $input['CommenterUserId'] . "'")
            ->select('Employees.*')
            ->first();

        $comment = PostComments::create($input);
        
        if ($userData != null) {
            $comment->FirstName = $userData->FirstName;
            $comment->LastName = $userData->LastName;
            $comment->ProfilePicture = $userData->ProfilePicture;
            $comment->EmployeeId = $userData->id;
        }

        return response()->json($comment, 200);
    }

}
