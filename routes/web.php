<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::resource('book', BookController::class);

Route::get('/', function () {
    return view('main');
})->name('index');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/list', function () {
    return view('list');
})->name('list');

Route::get('/details', function () {
    return view('details');
})->name('details');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/signup', function () {
    return view('signup');
})->name('signup');



Route::post('/signup', 'App\Http\Controllers\UserController@store');
Route::post('/login', 'App\Http\Controllers\UserController@login');
Route::get('/logout', 'App\Http\Controllers\UserController@logout')->name('logout');

Route::get('/login', function () {
    return view('login');
})->name('login');

route::fallback(function () {
    return view('notfound');
}, 404);


// CRUD routes (only accessible to authenticated users)
Route::middleware('auth')->group(function() {

    Route::get('/books', [BookController::class, 'index'])->name('book.index');

    Route::get('/book/create', [BookController::class, 'create'])->name('book.create');
    Route::get('/book/{book}', [BookController::class, 'show'])->name('book.show');
    Route::post('/book', [BookController::class, 'store'])->name('book.store');
    Route::get('/book/{book}/edit', [BookController::class, 'edit'])->name('book.edit');
    Route::put('/book/{book}', [BookController::class, 'update'])->name('book.update');
    Route::delete('/book/{book}', [BookController::class, 'destroy'])->name('book.destroy');
    
});


//  PROFILE 

Route::get('/profile', function () {    return view('profile');})->name('profile');

Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
 