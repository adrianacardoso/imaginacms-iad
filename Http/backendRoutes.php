<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/iad'], function (Router $router) {
    $router->bind('category', function ($id) {
        return app('Modules\Iad\Repositories\CategoryRepository')->find($id);
    });
    $router->get('categories', [
        'as' => 'admin.iad.category.index',
        'uses' => 'CategoryController@index',
        'middleware' => 'can:iad.categories.index'
    ]);
    $router->get('categories/create', [
        'as' => 'admin.iad.category.create',
        'uses' => 'CategoryController@create',
        'middleware' => 'can:iad.categories.create'
    ]);
    $router->post('categories', [
        'as' => 'admin.iad.category.store',
        'uses' => 'CategoryController@store',
        'middleware' => 'can:iad.categories.create'
    ]);
    $router->get('categories/{category}/edit', [
        'as' => 'admin.iad.category.edit',
        'uses' => 'CategoryController@edit',
        'middleware' => 'can:iad.categories.edit'
    ]);
    $router->put('categories/{category}', [
        'as' => 'admin.iad.category.update',
        'uses' => 'CategoryController@update',
        'middleware' => 'can:iad.categories.edit'
    ]);
    $router->delete('categories/{category}', [
        'as' => 'admin.iad.category.destroy',
        'uses' => 'CategoryController@destroy',
        'middleware' => 'can:iad.categories.destroy'
    ]);
    $router->bind('ad', function ($id) {
        return app('Modules\Iad\Repositories\AdRepository')->find($id);
    });
    $router->get('ads', [
        'as' => 'admin.iad.ad.index',
        'uses' => 'AdController@index',
        'middleware' => 'can:iad.ads.index'
    ]);
    $router->get('ads/create', [
        'as' => 'admin.iad.ad.create',
        'uses' => 'AdController@create',
        'middleware' => 'can:iad.ads.create'
    ]);
    $router->post('ads', [
        'as' => 'admin.iad.ad.store',
        'uses' => 'AdController@store',
        'middleware' => 'can:iad.ads.create'
    ]);
    $router->get('ads/{ad}/edit', [
        'as' => 'admin.iad.ad.edit',
        'uses' => 'AdController@edit',
        'middleware' => 'can:iad.ads.edit'
    ]);
    $router->put('ads/{ad}', [
        'as' => 'admin.iad.ad.update',
        'uses' => 'AdController@update',
        'middleware' => 'can:iad.ads.edit'
    ]);
    $router->delete('ads/{ad}', [
        'as' => 'admin.iad.ad.destroy',
        'uses' => 'AdController@destroy',
        'middleware' => 'can:iad.ads.destroy'
    ]);
    $router->bind('field', function ($id) {
        return app('Modules\Iad\Repositories\FieldRepository')->find($id);
    });
    $router->get('fields', [
        'as' => 'admin.iad.field.index',
        'uses' => 'FieldController@index',
        'middleware' => 'can:iad.fields.index'
    ]);
    $router->get('fields/create', [
        'as' => 'admin.iad.field.create',
        'uses' => 'FieldController@create',
        'middleware' => 'can:iad.fields.create'
    ]);
    $router->post('fields', [
        'as' => 'admin.iad.field.store',
        'uses' => 'FieldController@store',
        'middleware' => 'can:iad.fields.create'
    ]);
    $router->get('fields/{field}/edit', [
        'as' => 'admin.iad.field.edit',
        'uses' => 'FieldController@edit',
        'middleware' => 'can:iad.fields.edit'
    ]);
    $router->put('fields/{field}', [
        'as' => 'admin.iad.field.update',
        'uses' => 'FieldController@update',
        'middleware' => 'can:iad.fields.edit'
    ]);
    $router->delete('fields/{field}', [
        'as' => 'admin.iad.field.destroy',
        'uses' => 'FieldController@destroy',
        'middleware' => 'can:iad.fields.destroy'
    ]);
    $router->bind('schedule', function ($id) {
        return app('Modules\Iad\Repositories\ScheduleRepository')->find($id);
    });
    $router->get('schedules', [
        'as' => 'admin.iad.schedule.index',
        'uses' => 'ScheduleController@index',
        'middleware' => 'can:iad.schedules.index'
    ]);
    $router->get('schedules/create', [
        'as' => 'admin.iad.schedule.create',
        'uses' => 'ScheduleController@create',
        'middleware' => 'can:iad.schedules.create'
    ]);
    $router->post('schedules', [
        'as' => 'admin.iad.schedule.store',
        'uses' => 'ScheduleController@store',
        'middleware' => 'can:iad.schedules.create'
    ]);
    $router->get('schedules/{schedule}/edit', [
        'as' => 'admin.iad.schedule.edit',
        'uses' => 'ScheduleController@edit',
        'middleware' => 'can:iad.schedules.edit'
    ]);
    $router->put('schedules/{schedule}', [
        'as' => 'admin.iad.schedule.update',
        'uses' => 'ScheduleController@update',
        'middleware' => 'can:iad.schedules.edit'
    ]);
    $router->delete('schedules/{schedule}', [
        'as' => 'admin.iad.schedule.destroy',
        'uses' => 'ScheduleController@destroy',
        'middleware' => 'can:iad.schedules.destroy'
    ]);
// append




});
