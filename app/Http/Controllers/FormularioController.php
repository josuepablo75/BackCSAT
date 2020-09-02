<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormularioController extends Controller
{
    public function get_tipo_dato(Request $request)
    {
        $tipodato = DB::select('call GET_TIPO_DATO()');

        return response()->json(
            $tipodato
            , 200);
    }

    public function registro_formulario(Request $request){


        $formulario = DB::select('call PROC_INS_FORMULARIO(?)', [$request->getContent() ]);

        if ($formulario[0]->P_RESULT === -1){
            return response()->json([
                'estado'=>false,
                'mensaje'=> $formulario[0]-> MESSAGE_TEXT
            ], 406);
        }
        else
        {
            return response()->json([
                'estado'=>true,
                'mensaje'=> $formulario[0]->P_MSJ
            ], 200);
        }

    }

    public function get_formularios(Request $request)
    {
        $formulario = DB::select('call GET_FORMMULARIOS()');

        return response()->json(
            $formulario
            , 200);
    }

    public function get_formulario(Request $request, $id){
        $formulario = DB::select('call GET_FORMULARIO(?)',[$id]);
        foreach ($formulario as $item) {

            $agrupaciones = DB::select('call GET_AGRUPACIONES(?)', [$item->ID_FORMULARIO]);

            foreach ($agrupaciones as $agrupacion) {
                $preguntas = DB::select('call GET_PREGUNTAS(?)', [$agrupacion->ID_AGRUPACION]);

                foreach ($preguntas as $pregunta){
                    $opciones = DB::select('call GET_OPCIONES_PREGUNTA(?)', [$pregunta->ID_PREGUNTA]);
                    $pregunta->OPCIONES = $opciones;
                }

                $agrupacion->PREGUNTAS = $preguntas;
            }

            $item->AGRUPACIONES = $agrupaciones;
        }

        return response()->json(
            $formulario
            , 200);
    }

    public function actualizar_formulario(Request $request){


        $formulario = DB::select('call PROC_UPD_FORMULARIO(?)', [$request->getContent() ]);

        if ($formulario[0]->P_RESULT === -1){
            return response()->json([
                'estado'=>false,
                'mensaje'=> $formulario[0]-> MESSAGE_TEXT
            ], 406);
        }
        else
        {
            return response()->json([
                'estado'=>true,
                'mensaje'=> $formulario[0]->P_MSJ
            ], 200);
        }

    }

    public function actualizar_estado(Request $request){
        $query = DB::select('call PROC_UPD_ESTADOFORMULARIO(?,?)',
            [   $request->P_ID_FORMULARIO,$request->P_ESTADO
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

}
