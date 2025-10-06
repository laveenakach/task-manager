<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all'); // all | completed | incomplete

        $query = Task::ordered();

        if ($filter === 'completed') {
            $query->where('completed', true);
        } elseif ($filter === 'incomplete') {
            $query->where('completed', false);
        }

        $tasks = $query->get();

        // If request is AJAX, return JSON
        if ($request->wantsJson()) {
            return response()->json($tasks);
        }

        return view('tasks.index', compact('tasks', 'filter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // set position to end
        $maxPosition = Task::max('position');
        $data['position'] = is_null($maxPosition) ? 1 : ($maxPosition + 1);

        $task = Task::create($data);

        return redirect()->route('tasks.index')->with('success', 'Task created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $task->update($data);

        return redirect()->route('tasks.index')->with('success', 'Task updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted.');
    }

    public function toggleComplete(Request $request, Task $task)
    {
        $task->completed = !$task->completed;
        $task->save();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'completed' => $task->completed]);
        }

        return redirect()->back();
    }

    public function reorder(Request $request)
    {
        $order = $request->input('order'); // expected: [id1, id2, id3]

        if (!is_array($order)) {
            return response()->json(['success' => false, 'message' => 'Invalid order array'], 422);
        }

        \DB::transaction(function () use ($order) {
            foreach ($order as $index => $id) {
                Task::where('id', $id)->update(['position' => $index + 1]);
            }
        });

        return response()->json(['success' => true]);
    }
}
