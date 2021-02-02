<?php

namespace App\Http\Controllers;

use App\Models\ArtLike;
use Illuminate\Http\Request;

//Dependências adicionadas
use App\Models\Art; //Model Art
use Illuminate\Support\Facades\Auth; //Métodos de autenticação
use Illuminate\Http\Response; //Métodos para resposta em json

class ArtLikeController extends Controller
{
    public function rate(Request $request, Art $art_id)
    {
        $loggedUser = Auth::user();//Guarda o atual usuário logado

        //Se houver um usuário logado
        if(!empty($loggedUser))
        {
            //Se o usuário logado deu um like na art
            if($userLike = $loggedUser->likes->where('art', $art_id->id)->first())
            {
                $userLike->delete();

                if(isset($request->json))
                {
                    return response()->json(['success' => true]);
                }
                else
                {
                    return redirect()->back(); 
                }
            }
            else
            {
                $artLike = new ArtLike();

                $artLike->art = $art_id->id;
                $artLike->user = $loggedUser->id;

                $artLike->save();

                if(isset($request->json))
                {
                    return response()->json(['success' => true]);
                }
                else
                {
                    return redirect()->back();
                }
            }
        }
        else
        {
            if(isset($request->json))
            {
                return response()->json(['success' => false,'message' => 'É necessário estar logado para avaliar']);
            }
            else
            {
                return redirect()->back()->withErrors(['É necessário estar logado para avaliar']);
            }
        }
    }
}
