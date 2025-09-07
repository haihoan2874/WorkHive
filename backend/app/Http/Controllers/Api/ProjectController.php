<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    //lấy danh sách projects của user hiện tại 
    public function index(Request $request): JsonResponse
    {
        $projects = $request->user()
            ->projects()
            ->with(['owner', 'tasks'])
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => ['projects' => $projects]
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

    //Tạo project mới
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'status' => 'nullable|in:pending,in_progress,completed',
        ]);

        $project = $request->user()->projects()->create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Project created',
            'data' => ['project' => $project]
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //lấy thông tin chi tiết 1 project
    public function show(Request $request, Project $project): JsonResponse
    {
        if ($project->owner_id !== $request->user()->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden'
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => ['project' => $project->load(['owner', 'tasks'])]
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

    //Cập nhật thông tin project
    public function update(Request $request, Project $project): JsonResponse
    {
        if ($project->owner_id !== $request->user()->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden'
            ], 403);
        }

        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'status' => 'nullable|in:pending,in_progress,completed',
        ]);

        $project->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Project updated',
            'data' => ['project' => $project]
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //Xóa project
    public function destroy(Request $request, Project $project): JsonResponse
    {
        $user = $request->user();
        if ($project->owner_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden'
            ], 403);
        }

        $project->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Project deleted',
        ], 200);
    }
}
