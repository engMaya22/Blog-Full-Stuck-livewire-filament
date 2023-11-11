<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\SiteController;
use App\Models\Article;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return redirect('register');
// });

// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::get('/admin', function () {//rename to home
//         return view('home');

//     })->name('dashboard');
// });

Route::get('/', [ArticleController::class, 'home'])->name('home');
Route::get('/category/{category}', [ArticleController::class, 'byCategory'])->name('by-category');
Route::get('/{article}', [ArticleController::class, 'show'])->name('view');
Route::get('/about-us', [SiteController::class, 'about'])->name('about-us');

