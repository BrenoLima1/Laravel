<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Task;

Route::get('/', function () {
    return redirect()->route('tasks.index');
});

Route::get('/tasks', function () {
    $tasks = Task::all(); // busca do banco
    return view('index', ['tasks' => $tasks]);
})->name('tasks.index');

Route::view('/tasks/create', 'create')->name('tasks.create');

Route::get('/tasks/{id}', function ($id) {
    return view('show', ['task' => Task::findOrFail($id)]);
})->name('tasks.show');

Route::post('/tasks', function (Request $request) {
    $data = $request->validate([
        'title' => 'required|max:255',
        'description' => 'required',
        'long_description' => 'nullable',
    ]);

    $task = new Task($data);
    $task->completed = false;
    $task->save();

    return redirect()->route('tasks.index');
})->name('tasks.store');

Route::get('/hello', fn() => 'Hello')->name('hello');
Route::get('/hallo', fn() => redirect()->route('hello'));
Route::get('/greet/{name}', fn($name) => "Hello, $name!");

Route::fallback(fn() => 'Still got somewhere!');
