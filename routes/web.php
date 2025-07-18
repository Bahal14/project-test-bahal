<?php
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IdeaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/proxy/ideas', function () {
    $response = Http::get('https://suitmedia-backend.suitdev.com/api/ideas', request()->query());
    return $response->json();
});

Route::get('/ideas', [IdeaController::class, 'index']);
Route::get('/', [IdeaController::class, 'index']);