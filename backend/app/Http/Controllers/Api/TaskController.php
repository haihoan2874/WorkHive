<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): JsonResponse
    {
        $tasks = $request->user()
            ->assignedTasks()
            ->with(['project', 'assignee'])
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'tasks' => $tasks
            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        // Validate request
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'assignee_id' => 'nullable|exists:users,id',
            'status' => 'nullable|in:pending,in_progress, completed',
            'due_date' => 'nullable|date|after:today',
        ]);



        // Kiểm tra user có quyền tạo task trong project này không
        $project =  Project::findOrFail($request->project_id);
        if ($project->owner_id !== $request->user()->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to create task in this project',
            ], 403);
        }

        // Create task
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'project_id' => $request->project_id,
            'assignee_id' => $request->assignee_id,
            'status' => $request->status ?? 'pending',
            'due_date' => $request->due_date,
        ]);

        // Return response
        return response()->json([
            'status' => 'success',
            'message' => 'Task created successfully',
            'data' => [
                'task' => $task->load(['project', 'assignee']),
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Request  $request, Task $task): JsonResponse
    {
        // Kiểm tra user có quyền xem task này không
        if (
            $task->assignee_id !== $request->user()->id &&
            $task->project->owner_id !== $request->user()->id
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden',
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => ['task' => $task->load(['project', 'assignee'])]
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task): JsonResponse
    {
        // Kiểm tra user có quyền cập nhật task này không
        if ($task->assignee_id !== $request->user()->id && $task->project->owner_id !== $request->user()->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden',
            ], 403);
        }

        // Validate request
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'assignee_id' => 'nullable|exists:users,id',
            'status' => 'sometimes|in:pending, in_progress, completed',
            'due_date' => 'nullable|date|after:today',
        ]);
        // Cập nhật task
        $task->update($request->only('title', 'description', 'assignee_id', 'status', 'due_date'));

        // Trả về response
        return response()->json([
            'status' => 'success',
            'message' => 'Task updated successfully',
            'data' => [
                'task' => $task->load(['project', 'assignee'])
            ]
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Task $task): JsonResponse
    {
        // Kiểm tra user có quyền xóa task này không
        if ($task->project->owner_id !== $request->user()->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden',
            ], 403);
        }

        // Xóa task
        $task->delete();

        // Trả về response
        return response()->json([
            'status' => 'success',
            'message' => 'Task deleted successfully',
        ], 200);
    }
}
