<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;

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

// 授权登录
//Route::middleware('wechat.auth')->any('wechat/auth', 'WechatController@auth');
Route::any('wechat', 'ApiAuthorizationsController@auth');

Route::group([
    'middleware' => 'jwt.user',
    'prefix'  => 'v1',
    'namespace' => '\\App\\Http\\Controllers\\Api\\v1',
], function (Router $router) {
    $router->post('express', 'ExpressController@show');
});
