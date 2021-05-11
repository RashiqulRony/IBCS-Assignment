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

Route::group(['middleware' => ['api', 'cross'], 'namespace' => 'Api',], function ($router) {
    Route::get('/', function () {
        echo 'worked!';
    });

    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('refresh-token', 'AuthController@refreshToken');

    Route::group(['middleware' => ['jwt.auth']], function () {
        Route::post('me', 'AuthController@me');
        Route::post('logout', 'AuthController@logout');


        #Extra Api....
        Route::get('grade/previous', 'GradeController@previousGrade');

        Route::group(['prefix' => 'transaction'], function () {
            Route::get('company', 'DashboardController@companyTransaction');
            Route::get('employee', 'DashboardController@employeeTransaction');
        });

        Route::group(['prefix' => 'salary'], function () {
            Route::get('summary', 'SalaryController@salarySummary');
            Route::post('payment-salary', 'SalaryController@paymentSalary');
        });

        #All Resource Api...
        Route::apiResources([
            'grade'     => 'GradeController',
            'employee'  => 'EmployeeController',
        ]);

    });
});
