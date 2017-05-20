<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home.index');

$router->group(['middleware' => ['auth']], function ($router) {


    // users 
    $router->get('user', ['as' => 'user.index', 'uses' => 'UserController@index']);
    $router->get('user/create', ['as' => 'user.create', 'uses' => 'UserController@create']);
    $router->post('user', ['as' => 'user.store', 'uses' => 'UserController@store']);

    $router->get('user/{user}/edit', ['as' => 'user.edit', 'uses' => 'UserController@edit']);
    $router->patch('user/{user}', ['as' => 'user.update', 'uses' => 'UserController@update']);

    $router->get('user/{user}/delete', ['as' => 'user.delete', 'uses' => 'UserController@delete']);
    $router->delete('user/{user}', ['as' => 'user.destroy', 'uses' => 'UserController@destroy']);



    // Roles
    $router->get('role/sync', ['as' => 'role.sync', 'uses' => 'RoleController@sync']);
    $router->get('role', ['as' => 'role.index', 'uses' => 'RoleController@index']);
    $router->get('role/create', ['as' => 'role.create', 'uses' => 'RoleController@create']);
    $router->post('role', ['as' => 'role.store', 'uses' => 'RoleController@store']);

    $router->get('role/{role}/edit', ['as' => 'role.edit', 'uses' => 'RoleController@edit']);
    $router->patch('role/{role}', ['as' => 'role.update', 'uses' => 'RoleController@update']);

    $router->get('role/{role}/delete', ['as' => 'role.delete', 'uses' => 'RoleController@delete']);
    $router->delete('role/{role}', ['as' => 'role.destroy', 'uses' => 'RoleController@destroy']);

    $router->get('role/{role}/policy-category/{policyCategory?}/policy/{policy?}', ['as' => 'role.show', 'uses' => 'RoleController@show']);

//    $router->get('role/{role}/{policy?}', ['as' => 'role.show', 'uses' => 'RoleController@show']);
    $router->patch('role/{role}/{policy}/method', ['as' => 'role.update.method', 'uses' => 'RoleController@updateMethod']);
    $router->patch('role/{role}/{policy}/encryption', ['as' => 'role.update.encryption', 'uses' => 'RoleController@updateEncryption']);


    $router->get('role/{role}/policy/permission', ['as' => 'role.permission', 'uses' => 'RoleController@permission']);
    $router->get('role/{role}/policy/{policy}/permission', ['as' => 'role.get.permission', 'uses' => 'RoleController@getPolicyMethods']);
//    $router->post('role/{role}/policy/set-permission', ['as' => 'role.set.permission', 'uses' => 'RoleController@setPermission']);

    $router->get('role/{role}/policy-category/{policyCategory}/policy/{policy}/category-change', ['as' => 'category.policy.update', 'uses' => 'RoleController@changeCategory']);


});