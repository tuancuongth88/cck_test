<?php

use App\Http\Controllers\Api;
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

Route::group(['namespace' => 'App\Http\Controllers\Api'], function () {
    Route::get('city', 'CityController@index');
    Route::get('job-type', 'JobController@show');
    Route::post('upload', 'UploadFileController@store');
    Route::post('hr-organization', 'HrOrganizationController@store');
    Route::post('company', 'CompanyController@store');
    Route::get('download-fidelity/{id}', 'UploadFileController@downloadFidelity'); // hàm download chung


    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'AuthController@login');
        Route::post('forget-password', 'AuthController@forgetPassword');
        Route::put('password-reset', 'AuthController@resetPassword');

    });

    Route::group(['middleware' => 'auth:user'], function () {
        Route::group(['prefix' => 'auth'], function () {
            Route::post('refresh', 'AuthController@refresh');
            Route::get('logout', 'AuthController@logout');
            Route::post('confirm-password', 'AuthController@confirmPassword');
            Route::put('change-password', 'AuthController@changePassword');
            Route::get('company/{id}', 'CompanyController@show');

        });

        Route::get('offer', 'OfferController@index');
        Route::get('offer/{id}', 'OfferController@detail');

        Route::get('options', 'BaseController@Options');

        Route::get('profile', 'AuthController@getProfile');
        Route::put('profile', 'AuthController@update');
        // User
        Route::apiResource('user', 'UserController');
        Route::get('notify', 'UserController@getNotify');


        Route::group(['middleware' => ['type:super_admin']], function () {
            Route::apiResource('notification', 'NotificationController');
        });

        //super admin and company
        Route::group(['middleware' => ['type:company_manager']], function () {
            Route::get('company', 'CompanyController@index');
            Route::get('company/{id}', 'CompanyController@show');
            Route::post('update-status-company', 'UserController@updateStatusCompany');
        });

        Route::group(['middleware' => 'type:company_manager|company'], function (){
            Route::put('company/{id}', 'CompanyController@update');
        });


        //super admin and HR
        Route::group(['middleware' => ['type:hr_manager']], function () {
            Route::post('update-status-hr', 'UserController@updateStatusHR');
            Route::get('hr-organization', 'HrOrganizationController@index');
            Route::get('hr-organization/{id}', 'HrOrganizationController@show');
            Route::put('hr-organization/{id}', 'HrOrganizationController@update');

        });

        Route::get('work', 'WorkController@index')->name('work.index');
        Route::get('work/{id}', 'WorkController@show')->name('work.show');

        Route::post('user-favorite', 'UserFavoriteController@store');
        Route::delete('user-favorite/delete', 'UserFavoriteController@destroy');
        Route::get('user-favorite', 'UserFavoriteController@index');

        Route::get('work/{id}', 'WorkController@show')->name('work.show');

        // Company
        Route::group(['middleware' => ['type:company|company_manager']], function () {
            Route::delete('work', 'WorkController@destroy')->name('work.delete');
            Route::post('work', 'WorkController@store')->name('work.store');
            Route::post('work/{id}', 'WorkController@update')->name('work.update');
            Route::post('work/update-status-work/{id}', 'WorkController@updateStatusWork')->name('work.updateStatusWork');
            Route::post('offer', 'OfferController@store');
            Route::post('offer/remove-offer', 'OfferController@removeOffer');

        });

        // Hr
        Route::get('hr', 'HRController@index');
        Route::get('hr/{id}', 'HRController@show');
        Route::group(['middleware' => ['type:hr|hr_manager']], function () {
            Route::post('hr', 'HRController@store');
            Route::put('hr/{id}', 'HRController@update');
            Route::put('hr/update-file/{id}', 'HRController@updateFileHR');
            Route::delete('hr/{id}', 'HRController@destroy');
            Route::post('hr/download', 'HRController@download');
            Route::post('hr/import', 'HRController@importFile');
            Route::post('hr/check-file-import', 'HRController@checkFileImport');
            Route::post('hr/hide', 'HRController@hide');
            Route::post('/offer/update-status/{id}', 'OfferController@updateStatus');

        });

        Route::group(['middleware' => ['type:hr|hr_manager']], function () {
            Route::post('/entry', 'EntryController@store');
        });
        Route::post('/entry/hide', 'EntryController@hide');
        Route::post('/interview/hide', 'InterviewController@hide');
        Route::get('/entries', 'EntryController@index');
        Route::get('/entry/{id}', 'EntryController@show');

        // result
        Route::get('/result', 'ResultController@index');
        Route::post('/result/hide', 'ResultController@hide');
        Route::get('/result/{id}', 'ResultController@show');
        Route::put('/result/{id}', 'ResultController@update');

        Route::post('/entry/update-status/{id}', 'EntryController@updateStatus');
        Route::resource('/interview', 'InterviewController');
        Route::group(['middleware' => ['type:company', 'type:company_manager']], function () {
            Route::put('/interview/{id}/setup-calendar', 'InterviewController@setupCalendar');
            Route::put('/interview/{id}/setup-zoom', 'InterviewController@setupZoom');
            Route::put('/interview/{id}/review', 'InterviewController@review');
        });
        Route::group(['middleware' => ['type:hr']], function () {
            Route::put('/interview/{id}/confirm-calendar', 'InterviewController@confirmedCalendar');
        });

    });

});

