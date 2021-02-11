<?php

namespace App\Http\Controllers;

use App\Models\UserFollow;
use Illuminate\Http\Request;

//Dependências adicionadas
use App\Models\User; //Model Usuario
use Illuminate\Support\Facades\Auth; //Métodos de autenticação
use Illuminate\Http\Response; //Métodos para resposta em json

class UserFollowController extends Controller
{
    public function follow(Request $request, User $user)
    {   
        if(Auth::guard('user')->check())
        {
            $userLogged = Auth::guard('user')->user();
        
            if(($userFollow = $userLogged->usersFollowing()->where('user_followed', $user->id))->exists())
            {
                //Está Seguindo
                $userFollow->first()->delete();

                if($request->expectsJson()){
                    return response()->json(['success' => true]);
                }else{
                    return redirect()->back();
                }
            }
            else
            {
                //Não está seguindo
                $userFollow = new UserFollow();

                $userFollow->user_following = $userLogged->id;
                $userFollow->user_followed = $user->id;

                $userFollow->save();

                if($request->expectsJson()){
                    return response()->json(['success' => true]);
                }else{
                    return redirect()->back();
                }
            }
        }
        else
        {
            if($request->expectsJson()){
                return response()->json(['success' => false, 'message' => 'É necessário estar logado para seguir um usuário']);
            }else{
                return redirect()->back()->withErrors(['É necessário estar logado para seguir um usuário']);
            }
        }
    }
}
