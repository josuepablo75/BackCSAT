<?php

namespace App\Http\Controllers;

use function Sodium\add;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuariosController extends Controller
{
    public function get_usuarios(Request $request, $estado)
    {
        $usuarios = DB::select('call GET_USUARIOS(?)', [$estado]);

            return response()->json(
                 $usuarios
            , 200);
    }

    public function get_usuarios_byid(Request $request, $id)
    {
        $usuario = DB::select('call GET_USUARIO(?)',[$id]);



        foreach ($usuario as $item) {

            $opciones = DB::select('call GET_PERMISOS(?)', [$item->ID_USUARIO]);
            $item->PERMISOS = $opciones;
        }

        return response()->json(
            $usuario
            , 200);

    }

    public function registro_usuarios(Request $request){
        $usuarios = $request->only('PRIMER_NOMBRE', 'PRIMER_APELLIDO', 'USERNAME', 'PASSWORD');

        $validator = Validator::make($usuarios, [
            'PRIMER_NOMBRE'=> 'required|string',
            'PRIMER_APELLIDO'=> 'required|string',
            'USERNAME' => 'required',
            'PASSWORD' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'estado'=>false,
                'message'=> 'Error en la validacion de los parametros',
                'errores'=> $validator->errors()

            ], 422);
        }
        else{

                $query = DB::select('call PROC_INS_USUARIO(?,?,?,?,?,?,?,?,?,?,?,?,?)',
                [   $request->ID_TIPO_USUARIO, $request->CUI, $request->PRIMER_NOMBRE,
                    $request->SEGUNDO_NOMBRE, $request->PRIMER_APELLIDO, $request->SEGUNDO_APELLIDO,
                    $request->FECHA_NACIMIENTO, $request->GENERO, $request->USERNAME,
                    Hash::make($request->PASSWORD), $request->EMAIL, $request->FOTO, $request->ID_USUARIO_REGISTRA
                ]);

               if ($query[0]->P_RESULT === -1){
                   return response()->json([
                       'estado'=>false,
                       'mensaje'=> $query[0]->P_MENSAJE
                   ], 406);
               }
               else
               {
                   foreach ($request->PERMISOS as $item){
                       $opciones = DB::select('call PROC_INS_OPCIONES(?,?)',
                           [ $item, $query[0]->P_RESULT
                           ]);
                   }
                   return response()->json([
                       'estado'=>true,
                       'mensaje'=> $query[0]->P_MENSAJE
                   ], 200);
               }

        }
    }

    public function menu()
    {
       $modulo = DB::select('call GET_MODULOS()');

       foreach ($modulo as $item) {

           $opciones = DB::select('call GET_OPCIONES(?)', [$item->ID_MODULO]);

           foreach ($opciones as $opcion){
               $opcion->CHECKED = false;

           }
           $item->OPCIONES = $opciones;


       }

       return response()->json(
            $modulo
       , 200);

    }

    public function tipo_usuarios()
    {
        $tipo_usuarios = DB::select('call GET_TIPO_USUARIO()');

        return response()->json(
            $tipo_usuarios
        , 200);
    }

    public function actualizar_usuario(Request $request){
        $usuarios = $request->only('PRIMER_NOMBRE', 'PRIMER_APELLIDO', 'USERNAME', 'PWS');

        $validator = Validator::make($usuarios, [
            'PRIMER_NOMBRE'=> 'required|string',
            'PRIMER_APELLIDO'=> 'required|string',
            'USERNAME' => 'required',
            'PWS' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'estado'=>false,
                'message'=> 'Error en la validacion de los parametros',
                'errores'=> $validator->errors()

            ], 422);
        }
        else{

            if($request->PASSWORD == 'default')
            {
                $query = DB::select('call PROC_UPD_USUARIO(?,?,?,?,?,?,?,?,?,?,?,?)',
                    [   $request->ID_USUARIO, $request->ID_TIPO_USUARIO, $request->CUI, $request->PRIMER_NOMBRE,
                        $request->SEGUNDO_NOMBRE, $request->PRIMER_APELLIDO, $request->SEGUNDO_APELLIDO,
                        $request->FECHA_NACIMIENTO, $request->GENERO, $request->USERNAME,
                        $request->PWS, $request->EMAIL
                    ]);
            }
            else
            {
                $query = DB::select('call PROC_UPD_USUARIO(?,?,?,?,?,?,?,?,?,?,?,?)',
                    [   $request->ID_USUARIO,$request->ID_TIPO_USUARIO, $request->CUI, $request->PRIMER_NOMBRE,
                        $request->SEGUNDO_NOMBRE, $request->PRIMER_APELLIDO, $request->SEGUNDO_APELLIDO,
                        $request->FECHA_NACIMIENTO, $request->GENERO, $request->USERNAME,
                        Hash::make($request->PASSWORD), $request->EMAIL
                    ]);
            }


            if ($query[0]->P_RESULT === -1){
                return response()->json([
                    'estado'=>false,
                    'mensaje'=> $query[0]->P_MENSAJE
                ], 406);
            }
            else
            {
                foreach ($request->NUEVOS_PERMISOS as $item){
                    $opciones = DB::select('call PROC_INS_OPCIONES(?,?)',
                        [ $item, $request->ID_USUARIO
                        ]);
                }

                foreach ($request->DESHABILITAR_PERMISOS as $item){
                    $opciones = DB::select('call PROC_UPD_OPCIONES(?,?)',
                        [  $request->ID_USUARIO, $item
                        ]);
                }

                return response()->json([
                    'estado'=>true,
                    'mensaje'=> $query[0]->P_MENSAJE
                ], 200);
            }

        }
    }

    public function actualizar_estado(Request $request){
        $query = DB::select('call PROC_UPD_ESTADOUSUARIO(?,?)',
            [   $request->ID_USUARIO,$request->ESTADO
            ]);

            if ($query[0]->P_RESULT === -1){
                return response()->json([
                    'estado'=>false,
                    'mensaje'=> $query[0]->P_MENSAJE
                ], 406);
            }
            else
            {
                     return response()->json([
                    'estado'=>true,
                    'mensaje'=> $query[0]->P_MENSAJE
                ], 200);
            }

    }

    public function menu_opciones(Request $request, $id)
    {
        $modulo = DB::select('call GET_MODULO_USUARIO(?)', [$id]);

        foreach ($modulo as $item) {
            $opciones = DB::select('call GET_OPCIONES_USUARIO(?, ?)', [$id,$item->ID_MODULO]);
            $item->OPCIONES = $opciones;
        }

        return response()->json(
            $modulo
            , 200);

    }

}
