<?php

use Illuminate\Support\Facades\Route;
use App\Models\Task;
use App\Http\Requests\TaskRequest;

// Rota inicial redireciona para a lista de tasks
Route::get('/', function () {
    return redirect()->route('tasks.index');
});

// Listagem de tasks
Route::get('/tasks', function () {
    $tasks = Task::latest()->paginate(5);
    return view('index', ['tasks' => $tasks]);
})->name('tasks.index');

// Formulário de criação
Route::get('/tasks/create', function () {
    return view('create');
})->name('tasks.create');

// Exibir uma task específica
Route::get('/tasks/{task}', function (Task $task) {
    return view('show', ['task' => $task]);
})->name('tasks.show');

// Formulário de edição
Route::get('/tasks/{task}/edit', function (Task $task) {
    return view('edit', ['task' => $task]);
})->name('tasks.edit');

// Criar uma nova task
Route::post('/tasks', function (TaskRequest $request) {
    $data = $request->validated();
    $task = Task::create($data);

    return redirect()->route('tasks.show', $task->id)
        ->with('success', 'Task created successfully!');
})->name('tasks.store');

// Atualizar uma task existente
Route::put('/tasks/{task}', function (Task $task, TaskRequest $request) {
    $data = $request->validated();
    $task->update($data);
    $task->completed = false;
    $task->save();

    return redirect()->route('tasks.show', $task->id)
        ->with('success', 'Task updated successfully!');
})->name('tasks.update');

// Deletar uma task
Route::delete('/tasks/{task}', function (Task $task) {
    $task->delete();
    return redirect()->route('tasks.index')
        ->with('success', 'Task deleted successfully!');
})->name('tasks.destroy');

Route::put('tasks/{task}/toggle-complete', function (Task $task) {
    $task->ToggleComplete();

    return redirect()->back()->with('success', 'Task updated successfully!');
})->name('tasks.toggle-complete');

// Rotas extras de exemplo
// Route::get('/hello', fn() => 'Hello')->name('hello');
// Route::get('/hallo', fn() => redirect()->route('hello'));
// Route::get('/greet/{name}', fn($name) => "Hello, $name!");
// Route::fallback(fn() => 'Still got somewhere!');
