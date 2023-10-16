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
    Route::get('hr/download/{user_id}', 'HRController@download');

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
        Route::get('user/on-going-job','UserController@onGoingJob');
        Route::get('user/unread-messages','UserController@unreadMessages');
        Route::get('hr-org', 'HrOrganizationController@listAll');
        Route::get('company-option', 'CompanyController@listAll');

        Route::get('offer', 'OfferController@index');
        Route::get('offer/{id}', 'OfferController@detail');

        Route::get('options', 'BaseController@Options');

        Route::get('profile', 'AuthController@getProfile');
        Route::put('profile', 'AuthController@update');
        // User
        Route::apiResource('user', 'UserController');
        Route::get('notify', 'UserController@getNotify');
        Route::get('notification/distribution/other', 'NotificationController@otherList');
        Route::get('notification', 'NotificationController@index');
        Route::get('notification/{id}', 'NotificationController@show');
        Route::put('notification/{id}', 'NotificationController@update');
        Route::delete('notification/{id}', 'NotificationController@destroy');


        Route::group(['middleware' => ['type:super_admin']], function () {
            Route::post('notification', 'NotificationController@store');
        });

        //super admin and company
        Route::group(['middleware' => ['type:company_manager']], function () {
            Route::get('company', 'CompanyController@index');
            Route::post('update-status-company', 'UserController@updateStatusCompany');
        });

        Route::group(['middleware' => 'type:company_manager|company'], function (){
            Route::get('company/{id}', 'CompanyController@show');
            Route::put('company/{id}', 'CompanyController@update');
        });


        //super admin and HR
        Route::group(['middleware' => ['type:hr_manager']], function () {
            Route::post('update-status-hr', 'UserController@updateStatusHR');
            Route::get('hr-organization', 'HrOrganizationController@index');
        });

        Route::group(['middleware' => ['type:hr_manager|hr']], function(){
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

        });

        // Hr
        Route::get('hr', 'HRController@index');
        Route::get('hr/{id}', 'HRController@show');
        Route::group(['middleware' => ['type:hr|hr_manager']], function () {
            Route::post('hr', 'HRController@store');
            Route::put('hr/{id}', 'HRController@update');
            Route::put('hr/update-file/{id}', 'HRController@updateFileHR');
            Route::delete('hr/{id}', 'HRController@destroy');
            Route::post('hr/import', 'HRController@importFile');
            Route::post('hr/check-file-import', 'HRController@checkFileImport');
            Route::post('hr/hide', 'HRController@hide');
            Route::post('/offer/update-status/{id}', 'OfferController@updateStatus');

        });

        Route::group(['middleware' => ['type:hr|hr_manager']], function () {
            Route::post('/entry', 'EntryController@store');
            Route::put('/interview/confirm-calendar/{id}', 'InterviewController@confirmedCalendar');
            Route::put('/interview/confirm-interview-hr-decline/{id}', 'InterviewController@confirmedInterviewHrDecline');
            Route::put('/interview/setup-zoom/{id}', 'InterviewController@setupZoom');
        });
        Route::post('/entry/hide', 'EntryController@hide');
        Route::post('/interview/hide', 'InterviewController@hide');
        Route::get('/entries', 'EntryController@index');
        Route::get('/entry/{id}', 'EntryController@show');

        Route::post('offer/remove-offer', 'OfferController@removeOffer');
        // result
        Route::get('/result', 'ResultController@index');
        Route::post('/result/hide', 'ResultController@hide');
        Route::get('/result/{id}', 'ResultController@show');
        Route::put('/result/{id}', 'ResultController@update');
        Route::post('result', 'ResultController@store');

        Route::post('/entry/update-status/{id}', 'EntryController@updateStatus');
        Route::resource('/interview', 'InterviewController');


        Route::group(['middleware' => ['type:company|company_manager']], function () {
            Route::put('/interview/setup-calendar/{id}', 'InterviewController@setupCalendar');
            Route::put('/interview/confirm-interview-company-cancel/{id}', 'InterviewController@confirmedInterviewCompanyCancel');
            Route::put('/interview/confirm-interview-company-review/{id}', 'InterviewController@review');
        });
//        Route::group(['middleware' => ['type:hr']], function () {
//            Route::put('/interview/{id}/confirm-calendar', 'InterviewController@confirmedCalendar');
//        });

    });

});

