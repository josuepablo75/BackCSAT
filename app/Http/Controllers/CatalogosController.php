<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogosController extends Controller
{
     public function get_catalogo(Request $request, $id){
        $catalogo = DB::select('call GET_CATALOGO(?)',[$id]);
        foreach ($catalogo as $item) {

            $items = DB::select('call GET_ITEMS_CATALOGO(?)', [$item->TABLA]);
            $item->ITEMS = $items;

            $Catalogohijo =  DB::select('call GET_CATALOGO_HIJO(?)',[$item->ID_CATALOGO]);

            foreach ( $Catalogohijo as $hijo){
                 //$items = DB::select('call GET_ITEMS_CATALOGO(?)', [$hijo->TABLA]);
                 //$hijo->ITEMS = $items;

                $catalogohijo = DB::select('call GET_CATALOGO_HIJO(?)',[$hijo->ID_CATALOGO]);
                //foreach($catalogohijo as $hijosecundario){
                //    $itemshijo = DB::select('call GET_ITEMS_CATALOGO(?)', [$hijosecundario->TABLA]);
                //    $hijosecundario->ITEMS = $itemshijo; 
                //}
                $hijo->CATALOGO_HIJO = $catalogohijo;


            }

            $item->CATALOGO_HIJO = $Catalogohijo;
        }

        return response()->json(
            $catalogo
            , 200);
    }


      public function get_catalogo_data(Request $request, $tabla, $id){
        $catalogo = DB::select('call GET_CATALOGO_HIJO_DATOS(?,?)',[$tabla,$id]);
        return response()->json(
            $catalogo
            , 200);
    }
}
