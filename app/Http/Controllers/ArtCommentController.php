<?php

namespace App\Http\Controllers;

use App\Models\ArtComment;
use Illuminate\Http\Request;

//Dependências adicionadas
use App\Models\Art; //Model Usuario
use Illuminate\Support\Facades\Auth; //Métodos de autenticação
use Illuminate\Http\Response; //Métodos para resposta em json

class ArtCommentController extends Controller
{
    public function comment(Request $request, Art $art_id)
    {
        $loggedUser = Auth::user();//Guarda o atual usuário logado

        //Se houver um usuário logado
        if(!empty($loggedUser))
        {
            if(!empty($request->text))
            {
                $artComment = new ArtComment();

                $artComment->art = $art_id->id;
                $artComment->user = $loggedUser->id;

                $artComment->text = $request->text;

                $artComment->save();

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
                if(isset($request->json))
                {
                    return response()->json(['success' => false,'message' => 'É necessário preencher o comentário']);
                }
                else
                {
                    return redirect()->back()->withErrors(['É necessário preencher o comentário']);
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

    public function destroy(Request $request, ArtComment $art_comment)
    {
        //Se o usuário logado deu um like na art
        $art_comment->delete();

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
