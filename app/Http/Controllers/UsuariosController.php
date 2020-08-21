<?php

namespace App\Http\Controllers;

use function Sodium\add;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuariosController extends Controller
{
    public function get_usuarios()
    {
        $usuarios = DB::select('call GET_USUARIOS()');

            return response()->json([
                'data'=> $usuarios
            ], 200);
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

}
