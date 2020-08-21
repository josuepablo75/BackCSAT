<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/login', 'AuthController@login');

Route::group(['middleware'=>'auth:api'], function(){
    Route::get('/usuarios/usuarios', 'UsuariosController@get_usuarios');

});

Route::post('/usuarios/registro', 'UsuariosController@registro_usuarios');
Route::get('/usuarios/menu', 'UsuariosController@menu');
Route::get('/usuarios/tipo', 'UsuariosController@tipo_usuarios');