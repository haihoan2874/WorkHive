<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
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
        $comments = $post->comments()->with('user')->latest()->paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => [
                'comments' => CommentResource::collection($comments),
                'meta' => [
                    'current_page' => $comments->currentPage(),
                    'last_page' => $comments->lastPage(),
                    'per_page' => $comments->perPage(),
                    'total' => $comments->total(),
                ]
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
    public function store(StoreCommentRequest $request, Post $post): JsonResponse
    {
        // $request->validate([
        //     'body' => 'required|string'
        // ]);

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => $request->user()->id,
            'body' => $request->validated()['body'],
        ]);


        return response()->json([
            'status' => 'success',
            'message' => 'Comment created',
            'data' => [
                'comment' => new CommentResource($comment->load('user')),
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
        // if (
        //     $comment->user_id !== $request->user()->id &&
        //     $comment->post->user_id !== $request->user()->id
        // ) {
        //     return response()->json(['status' => 'error', 'message' => 'Forbidden'], 403);
        // }
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->json(['status' => 'success', 'message' => 'Comment deleted'], 200);
    }
}
