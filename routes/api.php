<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth_seeker'
], function ($router) {
    Route::post('login', 'Auth\SeekerAuth@login');
    Route::post('register', 'Auth\SeekerAuth@register');
    Route::post('logout', 'Auth\SeekerAuth@logout');
    Route::post('refresh', 'Auth\SeekerAuth@refresh');
    Route::post('me', 'Auth\SeekerAuth@me');
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth_owner'
], function ($router) {
    Route::post('login', 'Auth\OwnerAuth@login');
    Route::post('register', 'Auth\OwnerAuth@register');
    Route::post('logout', 'Auth\OwnerAuth@logout');
    Route::post('refresh', 'Auth\OwnerAuth@refresh');
    Route::post('me', 'Auth\OwnerAuth@me');
});