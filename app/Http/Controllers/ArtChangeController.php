<?php

namespace App\Http\Controllers;

use App\Models\ArtChange;
use Illuminate\Http\Request;

//Dependências adicionadas
use Illuminate\Support\Facades\Auth; //Métodos de autenticação
use Illuminate\Http\Response; //Métodos para resposta em json
use Illuminate\Support\Facades\Validator; //Métodos para validar os dados

class ArtChangeController extends Controller
{
    public function showArtEditRequest(ArtChange $artChange)
    {
        if(Auth::guard('user')->check())
        {
            if($artChange->author()->id != Auth::guard('user')->user()->id){
                return redirect()->route('art.requestlist')->withErrors(['Esta arte não é sua']);
            }
        }

        return view('art.request-edit', [
            'artChange' => $artChange
        ]);
    }
}
