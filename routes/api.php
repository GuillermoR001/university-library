<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Books\BooksController;
use App\Http\Controllers\API\Checkouts\CheckoutsController;
use App\Http\Controllers\API\Genres\GenresController;
use App\Http\Controllers\API\Users\UsersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => 'auth:api'], function ($router) {

    /*
    |--------------------------------------------------------------------------
    | Auth routes
    |--------------------------------------------------------------------------
    |
    */
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('refresh-token', [AuthController::class, 'refresh']);
    Route::get('profile', [AuthController::class, 'profile']);

    /*
    |--------------------------------------------------------------------------
    | genres
    |--------------------------------------------------------------------------
    |
    */
    Route::resource('genres', GenresController::class);

    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    |
    */
    Route::resource('users', UsersController::class)->middleware('librarian');

    /*
    |--------------------------------------------------------------------------
    | Books
    |--------------------------------------------------------------------------
    |
    */
    Route::group(['prefix' => 'books'], function ($router) {
        Route::get('list', [BooksController::class, 'index']);
        Route::post('filter', [BooksController::class, 'filter']);
        Route::post('store', [BooksController::class, 'store'])->middleware('librarian');
        Route::get('detail/{book}', [BooksController::class, 'show']);
        Route::put('edit/{book}', [BooksController::class, 'update'])->middleware('librarian');
    });


    /*
    |--------------------------------------------------------------------------
    | Checkouts
    |--------------------------------------------------------------------------
    |
    */
    Route::group(['prefix' => 'checkouts'], function ($router) {
        Route::get('list', [CheckoutsController::class, 'index'])->middleware('librarian');
        Route::get('show/{checkout}', [CheckoutsController::class, 'show']);
        Route::post('store', [CheckoutsController::class, 'store'])->middleware('librarian');
        Route::post('add', [CheckoutsController::class, 'addCheckout'])->middleware('student');
        Route::get('my-checkouts', [CheckoutsController::class, 'studentCheckouts'])->middleware('student');
        Route::put('return-book/{checkout}', [CheckoutsController::class, 'markAsReturned']);
    });
});

/*
|--------------------------------------------------------------------------
| Fallback route for 404 exceptios
|--------------------------------------------------------------------------
|
*/
Route::fallback(function () {
    return response()->json([
        'message' => 'Not found'
    ], 404);
});