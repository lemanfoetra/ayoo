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

/**
 * Auth Seeker
 */
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

/**
 * Auth Owner
 */
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

/**
 * Auth Add Sarana Olahraga
 */
Route::group([
    'middleware' => ['api', 'add_sarol'],
    'prefix' => 'add_sarana'
], function ($router) {
    // Step 1
    Route::get('basic_information/{id_sarana?}', 'AddSaranaOlahraga\AddBasicInformation@index');
    Route::post('basic_information', 'AddSaranaOlahraga\AddBasicInformation@store');

    // Step 2
    Route::get('address/{id_sarana}', 'AddSaranaOlahraga\AddAddress@index');
    Route::post('address/{id_sarana}', 'AddSaranaOlahraga\AddAddress@store');

    // Step 3
    Route::get('photos/{id_sarana}', 'AddSaranaOlahraga\AddPhotos@index');
    Route::post('photos/{id_sarana}', 'AddSaranaOlahraga\AddPhotos@addPhoto');
    Route::post('photos_submit/{id_sarana}', 'AddSaranaOlahraga\AddPhotos@submitPhotos');
    Route::delete('photos/{id_sarana}/{id_photo}', 'AddSaranaOlahraga\AddPhotos@delete');

    // Step 4
    Route::get('prices/{id_sarana}', 'AddSaranaOlahraga\AddPrices@index');
    Route::post('prices/{id_sarana}', 'AddSaranaOlahraga\AddPrices@store');
});


/**
 * Profile Owner
 */
Route::group([
    'middleware' => 'api',
    'prefix' => 'profile_owner'
], function ($router) {
    Route::get('', 'ProfileOwner\ProfileOwner@index');
    Route::post('basic_information', 'ProfileOwner\ProfileOwner@updateBasicInformationProfile');
    Route::post('photo_profile', 'ProfileOwner\ProfileOwner@updatePhotoProfile');
});
