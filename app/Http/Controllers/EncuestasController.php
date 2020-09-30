<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EncuestasController extends Controller
{
    public function get_encuestas(Request $request, $idFormulario, $idUsuario)
    {


        $encuestas = DB::select('call GET_ENCUESTAS(?,?)', [$idFormulario, $idUsuario]);

        return response()->json(
        $encuestas
        , 200);
    }

        public function get_encuestas_respondidas(Request $request, $idFormulario)
    {

        $encuestas = DB::select('call GET_ENCUESTAS_FORMULARIO(?)', [$idFormulario]);

        return response()->json(
        $encuestas
        , 200);
    }


    

    public function get_respuestas(Request $request, $idEncuesta){
        $respuestas = DB::select('call GET_RESPUESTAS(?)',[$idEncuesta]);
        foreach ($respuestas as $item) {

            $opcion_respuesta = DB::select('call GET_RESPUESTA_OPCION(?)', [$item->ID_RESPUESTA]);

            $item->OPCION_RESPUESTA = $opcion_respuesta;

            $respuesta_hijo = DB::select('call GET_RESPUESTA_HIJO(?)', [$item->ID_RESPUESTA]);
            foreach ($respuesta_hijo as $p_hijo) {
                $opcion_respuesta_hijo = DB::select('call GET_RESPUESTA_OPCION(?)', [$p_hijo->ID_RESPUESTA]);

                $p_hijo->OPCION_RESPUESTA = $opcion_respuesta_hijo;
            }

            $item->RESPUESTA_HIJO = $respuesta_hijo;
            
        }

        return response()->json(
            $respuestas
            , 200);
    }

    public function registrar_respuestas(Request $request){


    $respuestas = DB::select('call PROC_INS_ENCUESTA(?)', [$request->getContent() ]);

    if ($respuestas[0]->P_RESULT === -1){
        return response()->json([
            'estado'=>false,
            'mensaje'=> $respuestas[0]-> MESSAGE_TEXT
        ], 406);
    }
    else
    {
        return response()->json([
            'estado'=>true,
            'mensaje'=> $respuestas[0]->P_MSJ
        ], 200);
    }

}

    public function actualizar_respuestas(Request $request){


        $respuestas = DB::select('call PROC_UPD_ENCUESTA(?)', [$request->getContent() ]);

        if ($respuestas[0]->P_RESULT === -1){
            return response()->json([
                'estado'=>false,
                'mensaje'=> $respuestas[0]-> MESSAGE_TEXT
            ], 406);
        }
        else
        {
            return response()->json([
                'estado'=>true,
                'mensaje'=> $respuestas[0]->P_MSJ
            ], 200);
        }

    }
}
