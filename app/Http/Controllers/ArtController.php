<?php

namespace App\Http\Controllers;

use App\Models\Art;
use Illuminate\Http\Request;

//Dependências adicionadas
use App\Models\User; //Model Usuario
use Illuminate\Support\Facades\Auth; //Métodos de autenticação
use Illuminate\Http\Response; //Métodos para resposta em json
use Illuminate\Support\Facades\Validator; //Métodos para validar os dados

class ArtController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $arts = Art::all();

        return view('art.list', [
            'arts' => $arts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Verifica se possui um usuário autenticado
        if(Auth::guard('user')->check())
        {
            return view('art.register');
        }
        else
        {
            return redirect()->route('login')->withErrors(['É necessário estar logado para cadastrar uma arte']);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Variavel que receberá todas as regras e mensagens de validação
        $validator = Validator::make(
            $request->all(), //$request Possui todos os campos enviados por POST
            $rules = [
                'title' => 'required|min:5|max:30|unique:App\Models\Art,title',
                'art' => 'required|image'
            ],
            $messages = [
                //Mensagens para erros com o 'Title'
                'title.required' => 'O título está vazio.',
                'title.unique' => 'O título já está em uso',
                'title.min' => 'O título é necessário pelo menos :min caracteres.',
                'title.max' => 'O título possui um máximo de :max caracteres.',
                //Mensagens para erros com o 'Art'
                'art.required' => 'É necessário escolher uma imagem.',
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
            $art = new Art(); //Cria um objeto da model Art

            $idAuthor = Auth::guard('user')->user()->id; //Guarda o valor do ID do usuário autenticado

            $art->author = $idAuthor; //Campo author recebe o id do usuário autenticado
            $art->title = $request->title; //Campo titulo recebe o título escrito no formulário
            $art->path = $request->file('art')->store('art/'.$idAuthor); //Campo caminho recebe o caminho retornado do método de gravar arquivo no storage

            $art->save(); //Cadastra no banco de dados o author, título e caminho

            if($request->expectsJson()){
                return response()->json(['success' => true]);
            }else{
                return redirect()->route('home'); //Redireciona para a rota index
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Art  $art
     * @return \Illuminate\Http\Response
     */
    public function show(Art $art)
    {
        return view('art.show', [
            'art' => $art
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Art  $art
     * @return \Illuminate\Http\Response
     */
    public function edit(Art $art)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Art  $art
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Art $art)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Art  $art
     * @return \Illuminate\Http\Response
     */
    public function destroy(Art $art)
    {
        //
    }
}
