<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Post $post): JsonResponse
    {
        $comments = $post->comments()->with('user')->latest()->get();
        return response()->json([
            'status' => 'success',
            'data' => [
                'comments' => $comments,
            ]
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Post $post): JsonResponse
    {
        $request->validate([
            'body' => 'required|string'
        ]);

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => $request->user()->id,
            'body' => $request->body,
        ]);


        return response()->json([
            'status' => 'success',
            'message' => 'Comment created',
            'data' => [
                'comment' => $comment->load('user')
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Comment $comment): JsonResponse
    {
        if (
            $comment->user_id !== $request->user()->id &&
            $comment->post->user_id !== $request->user()->id
        ) {
            return response()->json(['status' => 'error', 'message' => 'Forbidden'], 403);
        }

        $comment->delete();

        return response()->json(['status' => 'success', 'message' => 'Comment deleted'], 200);
    }
}
