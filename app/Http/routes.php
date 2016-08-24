<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return redirect('/home');
});

// Route::group(['prefix' => '', 'middleware' => ['role:group1']], function() {
    
// });

Route::get('blogs/delete', 'BlogController@getDelete');

Route::get('blogs/edit', 'BlogController@getEdit');
Route::post('blogs/edit', 'BlogController@postEdit');

Route::get('blogs/create', 'BlogController@getCreate');
Route::post('blogs/create', 'BlogController@postCreate');

Route::get('blogs', 'BlogController@index');
Route::get('blogs/ajax', 'BlogController@getAjaxblog');

Route::get('pages', 'PageController@index');

Route::auth();

Route::get('/home', 'HomeController@index');

Route::post('users/store', 'UserController@store');

Route::get('users/create', 'UserController@create');

Route::get('users/confirm', 'UserController@getConfirm');


Route::get('/users/resetpassword', 'UserController@getResetpassword');
Route::post('/users/resetpassword', 'UserController@postResetpassword');

Route::get('/forgotpassword', 'UserController@getForgotpassword');
Route::post('/forgotpassword', 'UserController@postForgotpassword');

Route::get('/confirmforgotpass', 'UserController@getConfirmforgotpass');
Route::post('/confirmforgotpass', 'UserController@postConfirmforgotpass');

Route::post('/login', 'Auth\AuthController@postLogin');
