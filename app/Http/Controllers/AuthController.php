<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Nyholm\Psr7\Response;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function login(Request $request){

        $query = DB::select('call GET_USUARIO_LOGIN(?)', [$request->username]);
        if(!is_null($query[0]->P_RESULT) && Hash::check($request->password, $query[0]->P_RESULT)){
            $user = User::hydrate(DB::select('call GET_USUARIO(?)', [$query[0]->P_ID_USUARIO]));
            $token = $user[0]->createToken('CSATRESTAPI')->accessToken;

            return response()->json([
                'estado'=>true,
                'token'=> $token,
                'mensaje'=> 'Usuario Valido, Bienvenido al Sistema',
                'Usuario'=> $user[0]
            ], 200);
        }else
        {
            return response()->json([
                'estado'=>false,
                'mensaje'=> 'Usuario invalido, verifique sus credenciales.'
            ], 200);
        }

    }
}
