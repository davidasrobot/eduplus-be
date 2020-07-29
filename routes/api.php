<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('attempt/login', 'Auth\AuthController@authenticate');
Route::post('attempt/eduplus/login', 'Auth\AdminAuthController@authenticate');
Route::post('attempt/get-access', 'Auth\AuthController@register');
Route::middleware(['jwt.auth'])->group(function () {
    Route::prefix('operator')->middleware(['assign.guard:api'])->group(function(){
        // auth
        Route::post('logout', 'Auth\AuthController@logout');
        Route::post('refresh', 'Auth\AuthController@refresh');

        // school
        Route::get('school', 'Operator\SchoolController@index');
        Route::post('school/request', 'Operator\SchoolController@store');
        Route::post('school/remove_facility', 'Operator\SchoolController@destroy_facility');
        Route::post('school/remove_extracurricular', 'Operator\SchoolController@destroy_extracurricular');
        Route::post('school/remove_major', 'Operator\SchoolController@destroy_major');
        Route::post('school/image-upload', 'Operator\SchoolController@upload_image');
        Route::post('school/image-delete/{id}', 'Operator\SchoolController@destroy_image');
        Route::post('change_password', 'Operator\OperatorController@update_password');
        Route::post('change_avatar', 'Operator\OperatorController@update_avatar');
    });
    Route::prefix('eduplus-administrator')
        ->middleware('assign.guard:api-admin')
        ->group(function(){
            // auth
            Route::post('logout', 'Auth\AdminAuthController@logout');
            Route::post('refresh', 'Auth\AdminAuthController@refresh');
            
            // admin
            Route::get('/', 'Administrator\AdministratorController@index');
            Route::post('/add', 'Administrator\AdministratorController@store');
            Route::get('/admin/{uuid}', 'Administrator\AdministratorController@show');
            Route::post('/admin/update/{uuid}', 'Administrator\AdministratorController@update');
            Route::post('/admin/delete/{uuid}', 'Administrator\AdministratorController@destroy');

            // operator
            Route::get('/operator/', 'Administrator\OperatorController@index');
            Route::get('/operator/request/acc/{id}', 'Administrator\RequestController@operator_acc');
            Route::get('/school/request/acc/{id}', 'Administrator\RequestController@school_acc');
            Route::get('/operator/request/{order}/{orderBy}/{educational_stage?}', 'Administrator\RequestController@operators');
            Route::get('/school/request/{order}/{orderBy}/{educational_stage?}', 'Administrator\RequestController@schools');

            // promo
            Route::get('/promo/', 'Administrator\PromoController@index');
            Route::post('/promo/', 'Administrator\PromoController@store');
            Route::get('/promo/{id}', 'Administrator\PromoController@show');
            Route::post('/promo/{id}', 'Administrator\PromoController@update');
            Route::post('/promo/{id}/delete', 'Administrator\PromoController@destroy');

            // jumbotron
            Route::get('/jumbotron/', 'Administrator\JumbotronController@index');
            Route::post('/jumbotron/', 'Administrator\JumbotronController@store');
            Route::get('/jumbotron/{id}', 'Administrator\JumbotronController@show');
            Route::post('/jumbotron/{id}', 'Administrator\JumbotronController@update');
            Route::post('/jumbotron/{id}/delete', 'Administrator\JumbotronController@destroy');

            // inbox
            Route::get('/inbox', 'Administrator\InboxController@index');
            Route::get('/inbox/{id}', 'Administrator\InboxController@show');
            Route::post('/inbox/{id}/delete', 'Administrator\InboxController@destroy');
    });
});

Route::get('/verification/{uuid}', 'Auth\EmailVerificationController@verification');

// mainpage & Favorite Search
Route::get('/mainpage/{stage}', 'MainpageController@index');
Route::get('/province/{province}/{stage}', 'MainpageController@province');
Route::get('/regency/{regency}/{stage}', 'MainpageController@regency');
Route::get('/district/{district}/{stage}', 'MainpageController@district');
Route::get('/village/{village}/{stage}', 'MainpageController@village');

// Promo
Route::get('/promo', 'PromoController@index');
Route::get('/promo/{id}', 'PromoController@show');

// School
Route::get('/schools/{id}', 'SchoolController@show');

// Search
Route::get('/search/init', 'SearchController@initSearch');
Route::get('/search/get-regency/{province}', 'SearchController@getRegency');
Route::get('/search/get-district/{regency}', 'SearchController@getDistrict');
Route::get('/search/get-village/{district}', 'SearchController@getVillage');
Route::post('/search', 'SearchController@store');
Route::post('/search/favorite', 'SearchController@searchFavorite');
Route::post('/search/schools', 'SearchController@searchByName');

// contact us
Route::post('/contact', 'ContactUsController@store');


Route::post('/location', 'SchoolController@location');
Route::post('/schools/update/{district}', 'SchoolController@update');

// Route::post('/kabupaten', 'SchoolController@regency_edit');

// Route::get('/change_school', 'SchoolController@update');

// Route::post('/add-admin', 'Administrator\AdministratorController@store');