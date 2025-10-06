<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Task Manager</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap (optional) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .task-item { cursor: grab; }
        .task-completed { text-decoration: line-through; opacity: .6; }
    </style>
</head>
<body class="bg-light">
<div class="container py-4">
    <h1 class="mb-3">Tasks</h1>

    <div class="mb-3 d-flex gap-2">
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">Create Task</a>

        <div class="btn-group" role="group">
            <a href="{{ route('tasks.index', ['filter' => 'all']) }}" class="btn btn-outline-secondary {{ $filter === 'all' ? 'active' : '' }}">All</a>
            <a href="{{ route('tasks.index', ['filter' => 'completed']) }}" class="btn btn-outline-secondary {{ $filter === 'completed' ? 'active' : '' }}">Completed</a>
            <a href="{{ route('tasks.index', ['filter' => 'incomplete']) }}" class="btn btn-outline-secondary {{ $filter === 'incomplete' ? 'active' : '' }}">Incomplete</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <ul id="tasksList" class="list-group">
                @foreach($tasks as $task)
                    <li class="list-group-item d-flex justify-content-between align-items-start task-item"
                        data-id="{{ $task->id }}">
                        <div class="ms-2 me-auto">
                            <div class="{{ $task->completed ? 'task-completed' : '' }}">
                                <strong>{{ $task->title }}</strong>
                            </div>
                            <div class="small">{{ $task->description }}</div>
                        </div>

                        <div class="btn-group btn-group-sm" role="group" aria-label="task-actions">
                            <button class="btn btn-outline-success toggle-complete"
                                    data-id="{{ $task->id }}">
                                {{ $task->completed ? 'Unmark' : 'Complete' }}
                            </button>
                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-outline-primary">Edit</a>

                            <form method="POST" action="{{ route('tasks.destroy', $task) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger" onclick="return confirm('Delete task?')">Delete</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
            <div id="reorderHint" class="mt-3 text-muted small">Drag & drop to reorder tasks</div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // Enable Sortable
    new Sortable(document.getElementById('tasksList'), {
        animation: 150,
        handle: '.task-item',
        onEnd: async function (evt) {
            // collect ids in order
            const ids = Array.from(document.querySelectorAll('#tasksList > li'))
                .map(li => li.getAttribute('data-id'));
            try {
                const res = await fetch("{{ route('tasks.reorder') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ order: ids })
                });
                const data = await res.json();
                if (!data.success) {
                    alert('Failed to reorder tasks');
                }
            } catch (err) {
                console.error(err);
                alert('Error saving order');
            }
        }
    });

    // Toggle complete
    document.querySelectorAll('.toggle-complete').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            const id = btn.getAttribute('data-id');
            try {
                const res = await fetch(`/tasks/${id}/toggle-complete`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                if (data.success) {
                    // reload page to update UI / or toggle client-side
                    location.reload();
                } else {
                    alert('Could not update');
                }
            } catch (err) {
                console.error(err);
                alert('Error toggling completion');
            }
        });
    });
</script>
</body>
</html>
