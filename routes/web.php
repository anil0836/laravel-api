<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PostController;


Route::get('/', function () {
    return view('welcome');
});



Route::get('/db-check', function () {
    
    try {
        // Attempt to get the PDO instance
        $pdo = DB::connection()->getPdo();
        
        // Optionally fetch the current database name
        $dbName = DB::connection()->getDatabaseName();
        
        return response()->json([
            'status'      => 'success',
            'database'    => $dbName,
            'driver'      => config('database.default'),
        ]);
        
    } catch (\Exception $e) {
        // Catch & display error message
        return response()->json([
            'status'  => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
});



Route::get('/', [PostController::class, 'index']);
Route::get('/posts', [PostController::class, 'fetchPosts'])->name('posts.fetch');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::put('/posts/{id}', [PostController::class, 'update'])->name('posts.update');
Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
