<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

//Dependências adicionadas
use App\Models\Art; //Model Art
use Illuminate\Support\Facades\Hash; //Métodos para gerar código hash
use Illuminate\Support\Facades\Auth; //Métodos de autenticação
use Illuminate\Http\Response; //Métodos para resposta em json
use Illuminate\Support\Facades\Validator; //Métodos para validar os dados

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except('logout');
    }

    public function showRegisterForm()
    {
        return view('admin.register');
    }
    
    public function register(Request $request)
    {
        //Variavel que receberá todas as regras e mensagens de validação
        $validator = Validator::make(
            $request->all(), //$request Possui todos os campos enviados por POST
            $rules = [
                'name' => 'required|min:5|max:30',
                'email' => 'required|email|min:3|max:30|unique:App\Models\Admin,email|unique:App\Models\User,email',
                'password' => 'required|min:3|max:30',
                'confirmPassword' => 'required_with:password|same:password|min:3|max:30'
            ],
            $messages = [
                //Mensagens para erros com o 'Name'
                'name.required' => 'O nome está vazio.',
                'name.min' => 'O nome é necessário pelo menos :min caracteres.',
                'name.max' => 'O nome possui um máximo de :max caracteres.',
                //Mensagens para erros com o 'Password'
                'password.required' => 'A senha está vazia.',
                'password.min' => 'A senha deve possuir pelo menos :min caracteres.',
                'password.max' => 'A senha deve possuir no máximo :max caracteres.',
                //Mensagens para erros com o 'ConfirmPassword'
                'confirmPassword.required_with' => 'É necessário confirmar a senha.',
                'confirmPassword.same' => 'As senhas não coincidem.',
                'confirmPassword.min' => 'O confirmar senha deve possuir pelo menos :min caracteres.',
                'confirmPassword.max' => 'O confirmar senha deve possuir no máximo :max caracteres.',
                //Mensagens para os demais erros
                'required' => 'O :attribute está vazio.',
                'email' => 'O :attribute é inválido.',
                'unique' => 'O email já está em uso',
                'min' => 'O :attribute é necessário pelo menos :min caracteres.',
                'max' => 'O :attribute possui um máximo de :max caracteres.',
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
            $admin = new Admin(); //Instancia a Model Admin
            $admin->name = $request->name;  //Adiciona Nome ao objeto            
            $admin->email = $request->email; //Adiciona Email ao objeto
            $admin->password = Hash::make($request->password); //Adiciona senha criptografada ao objeto
            $admin->save(); //Grava no banco de dados

            if($request->expectsJson()){
                return response()->json(['success' => true]);
            }else{
                return redirect()->route('home'); //Redireciona para a rota index
            }
        }
    }

    public function artRequestList()
    {
        $arts = Art::where('status', '=', 'pendent')->get();

        return view('admin.art-requestlist', [
            'arts' => $arts
        ]);
    }

    public function artRequest(Art $art)
    {
        return view('admin.art-request', [
            'art' => $art
        ]);
    }

    public function artRequestChange(Request $request, Art $art)
    {
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
