<?php

use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ProdutoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [LoginController::class, 'login']);
Route::post('/valida-token', [LoginController::class, 'validaToken']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::get('/categorias/all', [CategoriaController::class, 'all']);
    Route::apiResource('categorias', CategoriaController::class);
    Route::apiResource('produtos', ProdutoController::class);
});
Route::get('imagens/{filename}', function ($filename) {
    $path = base_path() . '/public/imagens/' . $filename;

    /*if (!file_exists($path)) {
        abort(404);
    }*/

    return response()->file($path);
})->where('filename', '.*');
