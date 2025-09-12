<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): JsonResponse
    {
        $posts = Post::with('user')->when($request->boolean('published_only'), fn($q) => $q->whereNotNull('published_at'))
            ->latest()->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => [
                'posts' => PostResource::collection($posts),
                'meta' => [
                    'current_page' => $posts->currentPage(),
                    'last_page' => $posts->lastPage(),
                    'per_page' => $posts->perPage(),
                    'total' => $posts->total(),
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
    public function store(StorePostRequest $request): JsonResponse
    {
        $post = $request->user()->posts()->create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Post created',
            'data' => [
                'post' => new PostResource($post->load('user')),
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'post' => new PostResource($post->load('user'))
            ]
        ], 200);
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
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        // if ($post->user_id !== $request->user()->id) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Forbidden',

        //     ], 403);
        // }

        // $request->validate([
        //     'title' => 'sometimes|required|string|max:255',
        //     'slug' => 'sometimes|required|string|max:255|unique:posts,slug,' . $post->id,
        //     'body' => 'sometimes|required|string',
        //     'published_at' => 'nullable|date',
        // ]);

        $this->authorize('update', $post);

        // $post->update($request->only('title', 'slug', 'body', 'published_at'));
        $post->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Post updated',
            'data' => [
                'post' => new PostResource($post->load('user'))
            ]
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Post $post): JsonResponse
    {
        // if ($post->user_id !== $request->user()->id) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Forbidden',
        //     ], 403);
        // }
        $this->authorize('delete',$post);

        $post->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Post deleted',
        ], 200);
    }
}
