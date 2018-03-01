<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function __construct()
    {
        // Protect all routes except the listing route
        $this->middleware('auth')->except('index');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index()
    {
        return BlogPost::with('user')->get();
    }

    /**
     * Abstract saving the BlogPost so it can be used for creating or updating posts
     * @param BlogPost $post
     * @param array $fields
     * @return BlogPost
     */
    public function save(BlogPost $post, array $fields)
    {
        /** @var User $user */
        $user = Auth::user();

        $title = array_get($fields, 'title');
        $blurb = array_get($fields, 'blurb');
        $content = array_get($fields, 'content');

        // TODO: Validate and return any errors here
        $validator = Validator::make($fields, [
            'title' => 'required',
            'blurb' => 'required',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->toArray()
            ])->setStatusCode(422);
        }

        $post->fill([
            'user_id' => $user->getId(),
            'title'   => $title,
            'blurb'   => $blurb,
            'content' => $content,
        ]);
        $post->save();

        // Load the user so we can inject this straight back into the frontend
        // without having to make another request
        $post->load('user');

        return $post;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $post = new BlogPost();
        return $this->save($post, $request->all());
    }

    /**
     * @param Request $request
     * @param int $id
     * @return mixed
     */
    public function update(Request $request, int $id)
    {
        $post = BlogPost::findOrFail($id);
        return $this->save($post, $request->all());
    }

    /**
     * @param int $id
     * @return string
     */
    public function destroy(int $id)
    {
        $company = BlogPost::findOrFail($id);
        $company->delete();
        return null;
    }
}
