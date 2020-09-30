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
Route::post('/usuarios/uploadperfil', 'UsuariosController@upload_foto');

Route::group(['middleware'=>'auth:api'], function(){
    //RUTAS USUARIO 
    Route::get('/usuarios/usuarios/{estado}', 'UsuariosController@get_usuarios');
    Route::post('/usuarios/actualizarestado', 'UsuariosController@actualizar_estado');
    Route::get('/usuarios/menu_opciones/{id}', 'UsuariosController@menu_opciones');
    Route::post('/usuarios/actualizarusuario', 'UsuariosController@actualizar_usuario');
    Route::post('/usuarios/registro', 'UsuariosController@registro_usuarios');
    Route::get('/usuarios/menu', 'UsuariosController@menu');
    Route::get('/usuarios/tipo', 'UsuariosController@tipo_usuarios');
    Route::get('/usuarios/actualizar/{id}', 'UsuariosController@get_usuarios_byid');

    //RUTAS FORMULARIOS

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
    Route::get('/formulario/formulariosasignados/{idUsuario}', 'FormularioController@get_formularios_asignados');
    Route::get('/formulario/formulariorespuesta/{id}', 'FormularioController@get_formulario_respuesta');
    Route::post('/formulario/registrarcatalogo', 'FormularioController@registrar_catalogo');
    Route::get('/formulario/catalogos', 'FormularioController@get_catalogos');
    Route::post('/formulario/catalogos/actualizarestado', 'FormularioController@actualizarestado_catalogo');

    //ENCUESTAS
    
    Route::get('/encuesta/encuestas/{idFormulario}/{idUsuario}', 'EncuestasController@get_encuestas');
    Route::get('/encuesta/respuestas/{idEncuesta}', 'EncuestasController@get_respuestas');
    Route::post('/encuesta/registar', 'EncuestasController@registrar_respuestas');
    Route::post('/encuesta/actualizar', 'EncuestasController@actualizar_respuestas');
    Route::get('/encuesta/encuestasrespondidas/{idFormulario}', 'EncuestasController@get_encuestas_respondidas');

    //ARCHIVOS
    Route::get('/usuarios/ver/{foto}', 'UsuariosController@ver_foto');
    Route::get('/archivos/eliminar/{path_archivo}', 'UsuariosController@eliminar_archivo');

    //CATALOGOS

    Route::get('/catalogos/catalogo/{id}', 'CatalogosController@get_catalogo');
    Route::get('/catalogos/catalogohijo/{tabla}/{id}', 'CatalogosController@get_catalogo_data');

});







