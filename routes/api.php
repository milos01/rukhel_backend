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
Route::group(['prefix' => 'auth', 'middleware' => 'cors'], function () {
    Route::post('signin', 'AuthController@signin');
    Route::post('signup', 'UserController@signup');
    Route::post('reset-link', 'AuthController@sendResetLink');
    Route::get('check-hash', 'AuthController@checkHash');

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', 'AuthController@logout');
    });
});

Route::group(['prefix' => 'user', 'middleware' => ['auth:api', 'token']], function (){
    Route::get('/', function (Request $request) {
        return response($request->user(), 200);
    })->middleware("role:ADMIN,USER");

    Route::put('/', 'UserController@updateUser')->name('updateUser');
    Route::get('/', 'UserController@getUser');
    Route::post('/change-password', 'UserController@changePassword');
    Route::get('/{id}', 'UserController@getUserByUsername');
    Route::delete('/{username}', 'UserController@deleteUserByUsername')->middleware("role:ADMIN");
    Route::get('/activate/{username}', 'UserController@activateUser')->middleware("role:ADMIN");
    Route::get('/moderators','UserControllerm@getStaff')->middleware("role:ADMIN");
    Route::post('/find','UserController@findUsers'); //find users for tasks (user task search)
    Route::get('/upgrade/{username}', 'UserController@upgradeToAdmin')->middleware("role:ADMIN");
    Route::get('/downgrade/{username}', 'UserController@downgradeToModerator')->middleware("role:ADMIN");
    Route::post('/add-staff', 'UserController@addStaff')->middleware("role:ADMIN");
    Route::get('/task/assigned', 'UserController@getAssignedTasks')->middleware("role:ADMIN,MODERATOR");
});

Route::group(['middleware' => ['auth:api', 'token']], function (){
    Route::post('/category', 'CategoryController@addCategory')->middleware("role:ADMIN");
    Route::delete('/category/{name}', 'CategoryController@deleteCategory')->middleware("role:ADMIN");
    Route::put('/category/{name}', 'CategoryController@activateCategory')->middleware("role:ADMIN");
    Route::get('/categories', 'CategoryController@getCategories')->middleware("role:ADMIN");
});

Route::group(['prefix' => 'task', 'middleware' => ['auth:api', 'token']], function (){
    Route::post('/', 'TaskController@addTask')->middleware("role:ADMIN,MODERATOR,USER");
    Route::get('/{id}/single', 'TaskController@getTask')->middleware("role:ADMIN,MODERATOR,USER");
    Route::get('/{id}/take', 'TaskController@takeTask')->middleware("role:ADMIN,MODERATOR");
    Route::delete('/{id}', 'TaskController@inactiveTask')->middleware("role:ADMIN,MODERATOR");
    Route::get('/{id}/assigned', 'TaskController@getAssignedUsers')->middleware("role:ADMIN,MODERATOR");
    Route::get('/{id}/assign/{user_id}', 'TaskController@assignUserToTask')->middleware("role:ADMIN,MODERATOR");
    Route::delete('/{id}/assign/{user_id}', 'TaskController@dismissUserToTask')->middleware("role:ADMIN,MODERATOR");
    Route::put('/{id}/engage', 'TaskController@resetTaskExpire')->middleware("role:ADMIN,MODERATOR,USER");
    Route::get('/find', 'TaskController@getAllTasks')->middleware("role:ADMIN,MODERATOR,USER");
});

Route::group(['prefix' => 'upload', 'middleware' => ['auth:api', 'token']], function (){
    Route::post('/profile-picture', 'UploadController@uploadProfilePicture')->middleware("role:ADMIN,MODERATOR,USER");
    Route::post('/task/{id}/file', 'UploadController@uploadFile')->middleware("role:ADMIN,MODERATOR,USER");
});


Route::get('/test', function (Request $request) {
    dd(UserType::ADMIN()->getValue());
});
