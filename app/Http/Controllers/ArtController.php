<?php

namespace App\Http\Controllers;

use App\Models\Art;
use Illuminate\Http\Request;

//Dependências adicionadas
use App\Models\User; //Model Usuario
use App\Models\ArtChange;
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
        $arts = Art::where('status', '=', 'accepted')->get();

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
                'title' => 'required|min:5|unique:App\Models\ArtChange,new_title|unique:App\Models\Art,title',
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
                return view('user.profile', [
                    'user' => Auth::guard('user')->user()
                ]);
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
        if(Auth::guard('user')->check())
        {
            if($art->author()->first()->id == Auth::guard('user')->user()->id)
            {
                //Retorna a View enviando a variavel $user junto
                return view('art.edit', [
                    'art' => $art
                ]);
            }
            else
            {
                return redirect()
                        ->route('user.profile', [
                            'user' => Auth::guard('user')->user()])
                        ->withErrors(['Está arte não é sua']);
            }
        }
        else
        {
            return redirect()->route('home');
        }
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
        //Variavel que receberá todas as regras e mensagens de validação
        $validator = Validator::make(
            $request->all(), //$request Possui todos os campos enviados por POST
            $rules = [
                'title' => 'required|min:5|max:30|unique:App\Models\ArtChange,new_title|unique:App\Models\Art,title,'.$art->id,
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
            if($art->status == 'accepted')
            {
                if(!ArtChange::where('art', '=', $art->id)->exists())
                {
                    $artEdit = new ArtChange();

                    $artEdit->art = $art->id;

                    $artEdit->new_title = $request->title;

                    if($request->file('art')){
                        $artEdit->new_image_path = $request->file('art')->store('art/'.$idAuthor);
                    }else{
                        $artEdit->new_image_path = $art->path;
                    }

                    $artEdit->save();

                    if($request->expectsJson()){
                        return response()->json(['success' => true]);
                    }else{
                        return view('user.profile', [
                            'user' => Auth::guard('user')->user()
                        ]);
                    }
                }
                else
                {
                    $error = "Já possui uma requisição para esta imagem <a href='".route('art.requestedit', ['artChange' => $art->artChange()->first()->id])."'>clique aqui para saber mais</a>.";

                    if ($request->expectsJson()) {
                        return response()->json(['success' => false,'message' => $error]);
                    } else {
                        return redirect()
                                ->back()
                                ->withErrors($error)
                                ->withInput();
                    }
                }
            }
            else
            {
                $art->title = $request->title;
                if($request->file('art')){
                    $art->path = $request->file('art')->store('art/'.$art->author);
                }
                $art->status = 'pendent';
                $art->save();

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Art  $art
     * @return \Illuminate\Http\Response
     */
    public function destroy(Art $art)
    {
        if(Auth::guard('user')->check())
        {
            if($art->author()->first()->id == Auth::guard('user')->user()->id)
            {
                $art->delete();

                return view('user.profile', [
                    'user' => Auth::guard('user')->user()
                ]);
            }
            else
            {
                return redirect()
                        ->back()
                        ->withErrors(['Está arte não é sua']);
            }
        }
        
    }

    public function showArtsRequestList()
    {
        $arts = null;
        $artChanges = null;

        if (Auth::guard('user')->check())
        {
            $loggedUser = Auth::guard('user')->user();

            $arts = $loggedUser->arts()->where('status', '!=', 'accepted')->get();
            $artChanges = $loggedUser->artChanges()->get();
        }
        else if (Auth::guard('admin')->check())
        {
            $arts = Art::where('status', '=', 'pendent')->get();
            $artChanges = ArtChange::where('status', '=', 'pendent')->get();
        }
        else
        {
            return redirect()->route('login')->withErrors(['É necessário estar logado']);
        }

        return view('art.request-list', [
            'arts' => $arts,
            'artChanges' => $artChanges
        ]);
    }

    public function showArtRequest(Art $art)
    {
        if(Auth::guard('user')->check())
        {
            if($art->author != Auth::guard('user')->user()->id){
                return redirect()->route('art.requestlist')->withErrors(['Esta arte não é sua']);
            }
        }

        return view('art.request', [
            'art' => $art
        ]);
    }

    public function artStatusChange(Request $request, Art $art)
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
                $art->status = 'accepted';
                $art->status_changed_by = Auth::guard('admin')->user()->id;
                $art->save();
            }
            else if($request->reject)
            {
                $art->status = 'rejected';
                $art->status_changed_by = Auth::guard('admin')->user()->id;
                $art->message_status = $request->reason;
                $art->save();
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
                return redirect()->route('home'); //Redireciona para a rota index
            }
        }
    }
}
