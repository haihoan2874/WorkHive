<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
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
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => [
                'tasks' => TaskResource::collection($tasks),
                'meta' => [
                    'current_page' => $tasks->currentPage(),
                    'last_page' => $tasks->lastPage(),
                    'per_page' => $tasks->perPage(),
                    'total' => $tasks->total(),
                ]
            ]
        ], 200);
    }
    public function getProjectTasks(Project $project): JsonResponse
    {
        // Kiểm tra user có quyền xem project này không
        $this->authorize('view', $project);

        // Lấy tasks của project
        $tasks = $project->tasks()
            ->with(['assignee'])
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => TaskResource::collection($tasks)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        // Validate request
        // $request->validate([
        //     'title' => 'required|string|max:255',
        //     'description' => 'nullable|string',
        //     'project_id' => 'required|exists:projects,id',
        //     'assignee_id' => 'nullable|exists:users,id',
        //     'status' => 'nullable|in:pending,in_progress, completed',
        //     'due_date' => 'nullable|date|after:today',
        // ]);



        // Kiểm tra user có quyền tạo task trong project này không
        $project =  Project::findOrFail($request->validated()['project_id']);
        // if ($project->owner_id !== $request->user()->id) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Unauthorized to create task in this project',
        //     ], 403);
        // }
        $this->authorize('create', [Task::class, $project->owner_id]);
        // Create task
        // $task = Task::create([
        //     'title' => $request->title,
        //     'description' => $request->description,
        //     'project_id' => $request->project_id,
        //     'assignee_id' => $request->assignee_id,
        //     'status' => $request->status ?? 'pending',
        //     'due_date' => $request->due_date,
        // ]);

        $task = Task::create($request->validated());
        // Return response
        return response()->json([
            'status' => 'success',
            'message' => 'Task created successfully',
            'data' => [
                'task' => new TaskResource($task->load(['project', 'assignee'])),
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
        // if (
        //     $task->assignee_id !== $request->user()->id &&
        //     $task->project->owner_id !== $request->user()->id
        // ) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Forbidden',
        //     ], 403);
        // }

        $this->authorize('view', $task);

        return response()->json([
            'status' => 'success',
            'data' => [
                'task' => new TaskResource($task->load(['project', 'assignee'])),
            ]
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        // Kiểm tra user có quyền cập nhật task này không
        // if ($task->assignee_id !== $request->user()->id && $task->project->owner_id !== $request->user()->id) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Forbidden',
        //     ], 403);
        // }
        $this->authorize('update', $task);


        // Validate request
        // $request->validate([
        //     'title' => 'sometimes|required|string|max:255',
        //     'description' => 'nullable|string',
        //     'assignee_id' => 'nullable|exists:users,id',
        //     'status' => 'sometimes|in:pending, in_progress, completed',
        //     'due_date' => 'nullable|date|after:today',
        // ]);
        // Cập nhật task
        // $task->update($request->only('title', 'description', 'assignee_id', 'status', 'due_date'));

        $task->update($request->validated());

        // Trả về response
        return response()->json([
            'status' => 'success',
            'message' => 'Task updated successfully',
            'data' => [
                'task' => new TaskResource($task->load(['project', 'assignee'])),
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
        // if ($task->project->owner_id !== $request->user()->id) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Forbidden',
        //     ], 403);
        // }
        $this->authorize('delete', $task);

        // Xóa task
        $task->delete();

        // Trả về response
        return response()->json([
            'status' => 'success',
            'message' => 'Task deleted successfully',
        ], 200);
    }
}
