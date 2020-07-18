<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
define('PAGINATION_COUNT', 10);


Route::group([
    'namespace'=>'Admin',
    'middleware'=>'guest:admin'],
    function(){
        Route::get('login', 'LoginController@getLogin');
        Route::post('login', 'LoginController@login' )->name('admin.login');
    });

    Route::group([
        'namespace'=>'Admin',
        'middleware'=>'auth:admin'],
        function (){
        Route::get('/', 'DashboardController@index')->name('admin.dashboard');
        //##################### Begin Languages Routes#############################################//
            Route::group(['prefix'=>'languages'], function (){
                Route::get('/', 'LanguagesController@index')->name('admin.languages');
                Route::get('create', 'LanguagesController@create')->name('admin.languages.create');
                Route::post('store', 'LanguagesController@store')->name('admin.languages.store');
                Route::get('edit/{id}', 'LanguagesController@edit')->name('admin.languages.edit');
                Route::post('update/{id}', 'LanguagesController@update')->name('admin.languages.update');
                Route::get('delete/{id}', 'LanguagesController@destroy')->name('admin.languages.delete');
            });

        //##################### End Languages Routes#############################################//

            //##################### Begin mainCategories Routes#############################################//
            Route::group(['prefix'=>'mainCategories'], function (){
                Route::get('/', 'MainCategoryController@index')->name('admin.mainCategories');
                Route::get('create', 'MainCategoryController@create')->name('admin.mainCategories.create');
                Route::post('store', 'MainCategoryController@store')->name('admin.mainCategories.store');
                Route::get('edit/{id}', 'MainCategoryController@edit')->name('admin.mainCategories.edit');
                Route::post('update/{id}', 'MainCategoryController@update')->name('admin.mainCategories.update');
                Route::get('delete/{id}', 'MainCategoryController@destroy')->name('admin.mainCategories.delete');
                Route::get('changeStatus/{id}', 'MainCategoryController@changeStatus')->name('admin.mainCategories.status');


            });


            //##################### End mainCategories Routes#############################################//

            //##################### Begin Vendors Routes#############################################//
            Route::group(['prefix'=>'vendors'], function (){
                Route::get('/', 'vendorsController@index')->name('admin.vendors');
                Route::get('create', 'vendorsController@create')->name('admin.vendors.create');
                Route::post('store', 'vendorsController@store')->name('admin.vendors.store');
                Route::get('edit/{id}', 'vendorsController@edit')->name('admin.vendors.edit');
                Route::post('update/{id}', 'vendorsController@update')->name('admin.vendors.update');
                Route::get('delete/{id}', 'vendorsController@destroy')->name('admin.vendors.delete');
                Route::get('changeStatus/{id}', 'vendorsController@changeStatus')->name('admin.vendors.status');

            });


            //##################### End Vendors Routes#############################################//
        });


