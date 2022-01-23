
<?php

use Illuminate\Http\Request;

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
Route::group(['prefix' => 'v1'], function () {
    Route::post('/login', 'UsersController@login');
    Route::post('/register', 'UsersController@register');
    Route::get('/logout', 'UsersController@logout')->middleware('auth:api');
    Route::middleware('auth:api')->group(function () {
        Route::get('/user', 'UsersController@details');
        Route::get('/doctors/{name}', 'UsersController@search');
        Route::post('/appointment/{id}', 'UsersController@makeAppoin');
        Route::post('/ButtonAccept/{id}', 'UsersController@ButtonAccept');
        Route::post('/ButtonRefuse/{id}', 'UsersController@ButtonRefuse');

        Route::post('/ButtonAnnule/{id}', 'UsersController@ButtonAnnule');


        Route::get('/checkProposition/{id}', 'UsersController@checkProposition');
        Route::get('/checkAccept/{id}', 'UsersController@checkAccept');
        Route::get('/checkRefuse/{id}', 'UsersController@checkRefuse');
        Route::get('/checkRequest/{id}', 'UsersController@checkRequest');
        Route::get('/yourDoctors', 'UsersController@yourDoctors');
        
        Route::get('/isNewDoctor/{id}', 'UsersController@isNewDoctor');
        Route::get('/activeButtonMake/{id}', 'UsersController@activeButtonMake');
        
        Route::get('/isNewPatient', 'UsersController@isNewPatient');
        
        Route::get('/categories', 'UsersController@categories');
        
        
        Route::get('/appointmentUser', 'UsersController@appointmentUser');

        Route::get('/checkPropositionUser', 'UsersController@checkPropositionUser');
        Route::get('/checkAcceptUser', 'UsersController@checkAcceptUser');
        Route::get('/checkRefuseUser', 'UsersController@checkRefuseUser');
        Route::get('/checkRequestUser', 'UsersController@checkRequestUser');
       
       

        
       
        
        

    });
    
});