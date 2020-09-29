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

    public function get_formularios(Request $request, $estado)
    {
        $formulario = DB::select('call GET_FORMMULARIOS(?)', [$estado]);

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
                $agrupacion->PREGUNTAS = $preguntas;

                foreach ($preguntas as $pregunta){
                    $opciones = DB::select('call GET_OPCIONES_PREGUNTA(?)', [$pregunta->ID_PREGUNTA]);

                    $pregunta->OPCIONES = $opciones;

                    foreach($opciones as $opcioneshijos){
                        $opciones_hijos = DB::select('call GET_OPCIONES_PHIJO(?)', [$opcioneshijos->ID_OPCION_RESPUESTA]);
                        $opcioneshijos->OPCIONES_HIJO = $opciones_hijos;
                    }

                    foreach($opciones as $preguntarealcion){
                        $pregunta = DB::select('call GET_PREGUNTAS_HIJO(?,?)', [$preguntarealcion->ID_PREGUNTA, $preguntarealcion->ID_OPCION_RESPUESTA]);
                         foreach($pregunta as $opcionespregunta){
                             $opciones = DB::select('call GET_OPCIONES_PREGUNTA(?)', [$opcionespregunta->ID_PREGUNTA]);
                             // opciones hijo
                             foreach($opciones as $opcioneshijo){
                                 $opciones_hijo = DB::select('call GET_OPCIONES_PHIJO(?)', [$opcioneshijo->ID_OPCION_RESPUESTA]);
                                 $opcioneshijo->OPCIONES_HIJO = $opciones_hijo;
                            }
                             // opciones hijo
                             $opcionespregunta->OPCIONES = $opciones;
                        }
                        
                        $preguntarealcion->PREGUNTA_RELACION = $pregunta; 
                    }

  

                }

                
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

    public function asignar_formulario(Request $request){


        $formulario = DB::select('call PROC_INS_ASIGNACIONES(?)', [$request->getContent() ]);

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

    public function desasignar_formulario(Request $request){


        $formulario = DB::select('call PROC_UPD_ASIGNACIONES(?)', [$request->getContent() ]);

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

    public function get_usuarios_formulario(Request $request, $idformulario)
    {
        $asignacion = DB::select('call GET_USUARIOS_ASIGNACION(?)',[$idformulario]);


        return response()->json(
            $asignacion
            , 200);
    }

    public function get_usuarios_asignados(Request $request, $idformulario)
    {
        $asignacion = DB::select('call GET_USUARIOS_ASIGNADOS(?)',[$idformulario]);


        return response()->json(
            $asignacion
            , 200);
    }

    public function get_formularios_asignados(Request $request, $idUsuario){
        $asignacion = DB::select('call GET_FORMULARIOS_ASIGNADOS(?)',[$idUsuario]);

        return response()->json(
            $asignacion
            , 200);
    }

    public function get_formulario_respuesta(Request $request, $id)
    {
        $formulario = DB::select('call GET_FORMULARIO_RESP(?)', [$id]);
        foreach ($formulario as $item) {

            $agrupaciones = DB::select('call GET_AGRUPACIONES__RESP(?)', [$item->ID_FORMULARIO]);

            foreach ($agrupaciones as $agrupacion) {
                $preguntas = DB::select('call GET_PREGUNTAS_RESP(?)', [$agrupacion->ID_AGRUPACION]);

                foreach ($preguntas as $pregunta) {
                    $opciones = DB::select('call GET_OPCIONES_PREGUNTA_RESP(?)', [$pregunta->ID_PREGUNTA]);
                    $pregunta->OPCIONES = $opciones;

                    foreach($opciones as $opcioneshijos){
                        $opciones_hijos = DB::select('call GET_OPCIONES_PHIJO_RESP(?)', [$opcioneshijos->ID_OPCION_RESPUESTA]);
                        $opcioneshijos->OPCIONES_HIJO = $opciones_hijos;
                    }

                    foreach($opciones as $preguntarealcion){
                        $pregunta = DB::select('call GET_PREGUNTAS_HIJO_RESP(?,?)', [$preguntarealcion->ID_PREGUNTA, $preguntarealcion->ID_OPCION_RESPUESTA]);
                         foreach($pregunta as $opcionespregunta){
                             $opciones = DB::select('call GET_OPCIONES_PREGUNTA_RESP(?)', [$opcionespregunta->ID_PREGUNTA]);
                             // opciones hijo
                             foreach($opciones as $opcioneshijo){
                                 $opciones_hijo = DB::select('call GET_OPCIONES_PHIJO_RESP(?)', [$opcioneshijo->ID_OPCION_RESPUESTA]);
                                 $opcioneshijo->OPCIONES_HIJO = $opciones_hijo;
                            }
                             // opciones hijo
                             $opcionespregunta->OPCIONES = $opciones;
                        }
                        
                        $preguntarealcion->PREGUNTA_RELACION = $pregunta; 
                    }
                }

                $agrupacion->PREGUNTAS = $preguntas;
            }

            $item->AGRUPACIONES = $agrupaciones;
        }

        return response()->json(
            $formulario
            , 200);
    }

    public function registrar_catalogo(Request $request){

        $formulario = DB::select('call PROC_INS_CATALOGOS(?)', [$request->getContent() ]);

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
                'p_result'=>$formulario[0]->P_RESULT,
                'p_fecha'=>$formulario[0]->P_FECHA,
                'mensaje'=> $formulario[0]->P_MSJ
            ], 200);
        }

    }

        public function get_catalogos(Request $request)
    {
        $tipodato = DB::select('call GET_CATALOGOS()');

        return response()->json(
            $tipodato
            , 200);
    }

       public function actualizarestado_catalogo(Request $request){
        $query = DB::select('call PROC_UPD_ESTCATALOGO(?,?)',
            [   $request->P_ID_CATALOGO,$request->P_ESTADO
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
