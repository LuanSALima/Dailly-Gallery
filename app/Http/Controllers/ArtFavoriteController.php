<?php

namespace App\Http\Controllers;

use App\Models\ArtFavorite;
use Illuminate\Http\Request;

//Dependências adicionadas
use App\Models\Art; //Model Usuario
use Illuminate\Support\Facades\Auth; //Métodos de autenticação
use Illuminate\Http\Response; //Métodos para resposta em json

class ArtFavoriteController extends Controller
{
    public function favorite(Request $request, Art $art_id)
    {
        $loggedUser = Auth::user();//Guarda o atual usuário logado

        //Se houver um usuário logado
        if(!empty($loggedUser))
        {
            //Se o usuário logado deu um like na art
            if($userLike = $loggedUser->favorites->where('art', $art_id->id)->first())
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
                $artFavorite = new ArtFavorite();

                $artFavorite->art = $art_id->id;
                $artFavorite->user = $loggedUser->id;

                $artFavorite->save();

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
                return response()->json(['success' => false,'message' => 'É necessário estar logado para favoritar']);
            }
            else
            {
                return redirect()->back()->withErrors(['É necessário estar logado para favoritar']);
            }
        }
    }
}
