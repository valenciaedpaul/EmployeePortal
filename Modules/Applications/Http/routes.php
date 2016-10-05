<?php

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'applications', 'namespace' => 'Modules\Applications\Http\Controllers'], function()
{
    Route::get('/', 'ApplicationsController@index');

    Route::get('/get/all', 'ApplicationsController@getAllApplications');
    Route::get('/get/supervisor_level/', 'ApplicationsController@getSupervisedApplications');
    Route::get('/get/employee_level/{employee_id}', 'ApplicationsController@getEmployeeApplications');

    Route::get('/form', 'ApplicationsController@getForm');
    Route::post('/form', 'ApplicationsController@save');
    Route::get('/form/edit/{id}', 'ApplicationsController@getEdit');

    Route::get('/view/{id}', 'ApplicationsController@viewApplication');

    Route::post('/approve', 'ApplicationsController@approve');
    Route::post('/deny', 'ApplicationsController@deny');

    Route::post('/delete', 'ApplicationsController@delete');
});
