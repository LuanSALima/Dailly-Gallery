<?php

namespace App\Http\Controllers;

use App\Models\Art;
use Illuminate\Http\Request;

//Dependências adicionadas
use App\Models\User; //Model Usuario
use Illuminate\Support\Facades\Auth; //Métodos de autenticação

class ArtController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Verifica se possui um usuário autenticado
        if(Auth::check())
        {
            return view('art.register');
        }
        else
        {
            return redirect()->route('user.login')->withErrors(['É necessário estar logado para cadastrar uma arte']);
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
        //Verifica se os campos foram preenchidos
        if(!empty($request->title) && !empty($request->art))
        {
            $art = new Art(); //Cria um objeto da model Art

            $idAuthor = Auth::user()->id; //Guarda o valor do ID do usuário autenticado

            $art->author = $idAuthor; //Campo author recebe o id do usuário autenticado
            $art->title = $request->title; //Campo titulo recebe o título escrito no formulário
            $art->path = $request->file('art')->store('art/'.$idAuthor); //Campo caminho recebe o caminho retornado do método de gravar arquivo no storage

            $art->save(); //Cadastra no banco de dados o author, título e caminho
        }
        else
        {
            return redirect()->back()->withInput()->withErrors(['É necessário preencher todos os campos']);
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
        //
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
