<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

$path = '/'.Config::get('ghpb::index_route','ghpb') .'/';
Route::get($path,                            array('as' => 'indexpage',       'uses' => 'vyze\ghpb\GhpbController@showIndexPage'));
Route::get($path.'project',                  array('as' => 'project',         'uses' => 'vyze\ghpb\GhpbController@showProject'));
Route::post($path.'search',                  array('as' => 'search',          'uses' => 'vyze\ghpb\GhpbController@showSearch'));
Route::get($path.'user',                     array('as' => 'user',            'uses' => 'vyze\ghpb\GhpbController@showUser'));
Route::post($path.'changelike',              array('as' => 'changelike',      'uses' => 'vyze\ghpb\GhpbController@changeLikeStatus'));