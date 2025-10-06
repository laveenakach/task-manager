<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function() {
    return redirect()->route('tasks.index');
});

Route::resource('tasks', TaskController::class);

// extra routes for ajax actions
Route::post('tasks/{task}/toggle-complete', [TaskController::class, 'toggleComplete'])->name('tasks.toggleComplete');
Route::post('tasks/reorder', [TaskController::class, 'reorder'])->name('tasks.reorder');
