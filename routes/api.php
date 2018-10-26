<?php

use Illuminate\Http\Request;
use \App\Model\Enums\UserType;
use \App\Repository\UserRepository;
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
Route::group(['prefix' => 'user', 'middleware' => 'auth:api'], function (){
    Route::get('/', function (\App\Http\Requests\UserRequest $request, UserRepository $repository) {
//        $articles = $repository->search($request->q);
        return response($request->user(), 200);
    });

    Route::put('/', 'UserController@updateUser')->name('updateUser');
    Route::post('resetpassword', 'UserController@resetPassword')->name('resetPassword');
    Route::get('/{id}', 'UserController@getUserById');
    Route::get('/moderators','UserController@getModerators');
    Route::post('/find','UserController@findUser'); //find users for tasks (user task search)
    Route::post('api/application/getusers2','UserController@getApiUsers2'); //find all users (user search)
});



Route::get('/test', function (Request $request) {
    dd(UserType::ADMIN()->getValue());
});
