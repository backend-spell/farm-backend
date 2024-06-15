<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController,
    RoleController,
    UserController
};


Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::group(['middleware' => ['auth:api', 'admin']], function () {

        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);


        Route::resources([
            'roles' => RoleController::class,
            'users' => UserController::class
        ]);
    });
});
