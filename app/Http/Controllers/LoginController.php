<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Dependências adicionadas
use Illuminate\Support\Facades\Auth; //Métodos de autenticação
use Illuminate\Http\Response; //Métodos para resposta em json
use Illuminate\Support\Facades\Hash; //Métodos para gerar código hash
use Illuminate\Support\Facades\Validator; //Métodos para validar os dados

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:admin')->except('logout');
        $this->middleware('guest:user')->except('logout');
    }

    public function showLoginForm()
    {
        return view('site.login'); //Criar View
    }

    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            $rules = [
                'email' => 'required|email|min:3|max:30',
                'password' => 'required|min:3|max:30'
            ],
            $messages = [
                'required' => 'O :attribute está vazio.',
                'email' => 'O :attribute é inválido.',
                'min' => 'O :attribute é necessário pelo menos :min caracteres.',
                'max' => 'O :attribute possui um máximo de :max caracteres.',

                'password.required' => 'A senha está vazia.',
                'password.min' => 'A senha deve possuir pelo menos :min caracteres.',
                'password.max' => 'A senha deve possuir no máximo :max caracteres.'
            ]
        );

        if ($validator->fails()) {
            
            $errors = $validator->messages()->messages();
            $mensagem = '';

            foreach ($errors as $error){
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

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if(Auth::guard('admin')->attempt($credentials) || Auth::guard('user')->attempt($credentials))
        {
            if ($request->expectsJson()) {
                return response()->json(['success' => true]);
            } else {
                return redirect()->route('home');
            }
        }
        else
        {
            $errorMessage = 'Os dados informados não conferem';
            if ($request->expectsJson()) {
                return response()->json(['success' => false,'message' => $errorMessage]);
            } else {
                return redirect()->back()->withErrors([$errorMessage]);
            }
        }
    }

    public function logout()
    {
        if(Auth::guard('admin')->check())
            Auth::guard('admin')->logout();

        if(Auth::guard('user')->check())
            Auth::guard('user')->logout();

        return redirect()->route('home');
    }
}
