<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostImage;
use App\Models\PostLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Exception;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showByProfile(Request $request)
    {
        $posts=Post::with('image','profile')->where('posts.profile_id', $request->profile_id)->orderBy('posts.created_at', 'DESC')->get();
        if ($posts != null) {
            foreach ($posts as $index => $item) {
                $item['comments'] = PostComment::with('profile')->where('post_comments.post_id', $item->id)->orderBy('post_comments.created_at', 'ASC')->get();
                $item['likes'] = PostLike::with('profile')->where('post_likes.post_id', $item->id)->orderBy('post_likes.created_at', 'DESC')->get();
            }
        }

        $response['message'] = 'success';
        $response['data'] = $posts;

        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function postRelease(Request $request)
    {
        $validationPost=Validator::make($request->all(), Post::$rules['create']);
        if ($validationPost->fails()) {
            $response['message'] = 'failed';
            $response['error'] = $validationPost->errors();

            return response()->json($response, 501);
        } else {
            $post = Post::create([
                'profile_id' => Auth::user()->profile->id,
                'caption' => $request->caption
            ]);
            if ($post) {
                try {
                    $requestUrlImage = $request->url_image;
                    foreach ($requestUrlImage as $index => $item) {
                        PostImage::create([
                            'post_id' => $post->id,
                            'url_image' => $item
                        ]);
                    }

                    $response['message'] = 'success';
                    $response['data'] = $post;

                    return response()->json($response, 200);
                } catch (Exception $e) {
                    $post->forceDelete();

                    $response['message'] = 'failed';
                    $response['error'] = $e->getMessage();
                    return response()->json($response, 501);
                }
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function showByPost(Request $request)
    {
        $post=Post::with('image','profile')->where('id', $request->post_id)->orderBy('posts.created_at', 'DESC')->first();
        if ($post != null) {
            $post['comments'] = PostComment::with('profile')->where('post_comments.post_id', $post->id)->orderBy('post_comments.created_at', 'ASC')->get();
            $post['likes'] = PostLike::with('profile')->where('post_likes.post_id', $post->id)->orderBy('post_likes.created_at', 'DESC')->get();
        }

        $response['message'] = 'success';
        $response['data'] = $post;

        return response()->json($response, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateCaption(Request $request)
    {
        $validation=Validator::make($request->all(), Post::$rules['update']);
        if ($validation->fails()) {
            $response['message'] = 'failed';
            $response['error'] = $validation->errors();

            return response()->json($response, 501);
        } else {
            $post=Post::find($request->post_id)->update([
                'caption' => $request->caption
            ]);

            $response['message'] = 'success';
            $response['data'] = $post;

            return response()->json($response, 200);
        }
    }

    public function postLike(Request $request)
    {
        $requestLike = $request->all();
        $requestLike['profile_id'] = Auth::user()->profile->id;
        $validation=Validator::make($requestLike, PostLike::$rules['create']);
        if ($validation->fails()) {
            $response['message'] = 'failed';
            $response['error'] = $validation->errors();

            return response()->json($response, 501);
        } else {
            $postLike = PostLike::create($requestLike);

            $response['message'] = 'success';
            $response['data'] = $postLike;

            return response()->json($response, 200);
        }
    }

    public function postUnlike(Request $request)
    {
        $requestUnlike = $request->all();
        $requestUnlike['profile_id'] = Auth::user()->profile->id;
        $validation=Validator::make($requestUnlike, PostLike::$rules['update']);
        if ($validation->fails()) {
            $response['message'] = 'failed';
            $response['error'] = $validation->errors();

            return response()->json($response, 501);
        } else {
            $postUnLike = PostLike::find($request->post_id)->delete();

            $response['message'] = 'success';
            $response['data'] = $postUnLike;

            return response()->json($response, 200);
        }
    }

    public function postComment(Request $request)
    {
        $requestComment = $request->all();
        $requestComment['profile_id'] = Auth::user()->profile->id;
        $validation=Validator::make($requestComment, PostComment::$rules['create']);
        if ($validation->fails()) {
            $response['message'] = 'failed';
            $response['error'] = $validation->errors();

            return response()->json($response, 501);
        } else {
            $postComment = PostComment::create($requestComment);

            $response['message'] = 'success';
            $response['data'] = $postComment;

            return response()->json($response, 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
