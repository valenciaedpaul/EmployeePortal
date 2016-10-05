<?php

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'employees', 'namespace' => 'Modules\Employees\Http\Controllers'], function()
{
    Route::get('/', 'EmployeesController@index');
});

Route::group(['middleware' => 'web', 'prefix' => 'employees', 'namespace' => 'Modules\Employees\Http\Controllers'], function()
{
    Route::get('/login', 'EmployeesController@getLogin');
    Route::post('/login', 'EmployeesController@login');
    Route::get('/logout', 'EmployeesController@logout');
});
