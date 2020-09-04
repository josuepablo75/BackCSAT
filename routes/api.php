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
Route::post('/auth/logout', 'AuthController@logout');

Route::group(['middleware'=>'auth:api'], function(){
    Route::get('/usuarios/usuarios/{estado}', 'UsuariosController@get_usuarios');

    Route::post('/usuarios/actualizarestado', 'UsuariosController@actualizar_estado');
    Route::get('/usuarios/menu_opciones/{id}', 'UsuariosController@menu_opciones');
});


Route::post('/usuarios/actualizarusuario', 'UsuariosController@actualizar_usuario');
Route::post('/usuarios/registro', 'UsuariosController@registro_usuarios');
Route::get('/usuarios/menu', 'UsuariosController@menu');
Route::get('/usuarios/tipo', 'UsuariosController@tipo_usuarios');
Route::get('/usuarios/actualizar/{id}', 'UsuariosController@get_usuarios_byid');
Route::get('/formulario/tipodato', 'FormularioController@get_tipo_dato');
Route::post('/formulario/registar', 'FormularioController@registro_formulario');
Route::get('/formulario/formularios/{estado}', 'FormularioController@get_formularios');
Route::get('/formulario/formulario/{id}', 'FormularioController@get_formulario');
Route::post('/formulario/actualizarformulario', 'FormularioController@actualizar_formulario');
Route::post('/formulario/actualizarestado', 'FormularioController@actualizar_estado');
Route::post('/formulario/asignarformulario', 'FormularioController@asignar_formulario');
Route::post('/formulario/desasignarformulario', 'FormularioController@desasignar_formulario');
Route::get('/formulario/usuarios/{idformulario}', 'FormularioController@get_usuarios_formulario');
Route::get('/formulario/usuariosasignados/{idformulario}', 'FormularioController@get_usuarios_asignados');