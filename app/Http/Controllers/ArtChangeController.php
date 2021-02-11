<?php

namespace App\Http\Controllers;

use App\Models\ArtChange;
use Illuminate\Http\Request;

//Dependências adicionadas
use App\Models\Art;
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

    public function artStatusChange(Request $request, ArtChange $artChange)
    {
        if(!Auth::guard('admin')->check()){
            if($request->expectsJson()){
                return response()->json(['success' => false,'message' => 'É necessário que seja um Administrador para alterar o status']);
            }else{
                return redirect()->back()->withErrors('É necessário que seja um Administrador para alterar o status');
            }
        }

        $rules = ['reason'];

        if($request->has('reject'))
        {
            $rules['reason'] = 'required|min:10|max:150';
        }

        //Variavel que receberá todas as regras e mensagens de validação
        $validator = Validator::make(
            $request->all(), //$request Possui todos os campos enviados por POST
            $rules,
            $messages = [
                'reason.required' => 'É necessário escrever o motivo pelo qual foi a arte foi rejeitada.',
                'reason.min' => 'O motivo deve possuir pelo menos :min caracteres.',
                'reason.max' => 'O motivo deve possuir no máximo :max caracteres.',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->messages()->messages();
            $mensagem = '';

            foreach ($errors as $error) {
                $mensagem = $mensagem.implode('<br>',$error).'<br>';
            }

            if ($request->expectsJson()) {
                return response()->json(['success' => false,'message' => $mensagem]);
            } else {
                return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
            }
        }
        else
        {
            if($request->aprove)
            {
                $art = $artChange->art()->first();
                
                $art->title = $artChange->new_title;
                $art->path = $artChange->new_image_path;
                $art->status = 'accepted';
                $art->status_changed_by = Auth::guard('admin')->user()->id;

                $art->save();

                $artChange->delete();
            }
            else if($request->reject)
            {
                $artChange->status = 'rejected';
                $artChange->status_changed_by = Auth::guard('admin')->user()->id;
                $artChange->message_status = $request->reason;
                $artChange->save();
            }
            else
            {
                if($request->expectsJson()){
                    return response()->json(['success' => false,'message' => 'Erro inesperado']);
                }else{
                    return redirect()->back()->withErrors('Erro inesperado');
                }
            }

            if($request->expectsJson()){
                return response()->json(['success' => true]);
            }else{
                return redirect()->route('art.requestlist'); //Redireciona para a rota index
            }
        }
    }

    public function update(Request $request, ArtChange $artChange)
    {
        if(!Auth::guard('user')->check())
        {
            if($request->expectsJson()){
                return response()->json(['success' => false,'message' => 'É necessário que seja um usuário logado para atualizar uma arte']);
            }else{
                return redirect()->back()->withErrors('É necessário que seja um usuário logado para atualizar uma arte');
            }
        }
        else
        {
            if(Auth::guard('user')->user()->id != $artChange->author()->id)
            {
                if($request->expectsJson()){
                    return response()->json(['success' => false,'message' => 'Não é possivel atualizar uma arte que não é sua']);
                }else{
                    return redirect()->back()->withErrors('Não é possivel atualizar uma arte que não é sua');
                }
            }
        }

        //Variavel que receberá todas as regras e mensagens de validação
        $validator = Validator::make(
            $request->all(), //$request Possui todos os campos enviados por POST
            $rules = [
                'title' => 'required|min:5|max:30|unique:App\Models\Art,title,'.$artChange->art()->first()->id.'|unique:App\Models\ArtChange,new_title,'.$artChange->id,
                'art' => 'image'
            ],
            $messages = [
                //Mensagens para erros com o 'Title'
                'title.required' => 'O título está vazio.',
                'title.unique' => 'O título já está em uso',
                'title.min' => 'O título é necessário pelo menos :min caracteres.',
                'title.max' => 'O título possui um máximo de :max caracteres.',
                //Mensagens para erros com o 'Art'
                'art.image' => 'O arquivo deve ser uma imagem',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->messages()->messages();
            $mensagem = '';

            foreach ($errors as $error) {
                $mensagem = $mensagem.implode('<br>',$error).'<br>';
            }

            if ($request->expectsJson()) {
                return response()->json(['success' => false,'message' => $mensagem]);
            } else {
                return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
            }
        }
        else
        {
            $artChange->new_title = $request->title;

            if($request->file('art')){
                $artChange->new_image_path = $request->file('art')->store('art/'.$idAuthor);
            }else{
                $artChange->new_image_path = $artChange->new_image_path;
            }

            $artChange->status = 'pendent';
            $artChange->save();

            if($request->expectsJson()){
                return response()->json(['success' => true]);
            }else{
                return view('user.profile', [
                    'user' => Auth::guard('user')->user()
                ]);
            }
        }
    }
}
