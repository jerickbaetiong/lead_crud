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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


    Route::get('all-leads', 'LeadController@index');
    Route::post('lead', 'LeadController@store');
    Route::delete('lead/{id}', 'LeadController@destroy');
    Route::put('lead/{id}', 'LeadController@update');

    Route::get('all-tags', 'TagController@index');
    Route::post('tag', 'TagController@store');
