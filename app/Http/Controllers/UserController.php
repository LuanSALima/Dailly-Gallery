<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Dependências adicionadas
use App\Models\User; //Model Usuario
use Illuminate\Support\Facades\Hash; //Métodos para gerar código hash
use Illuminate\Support\Facades\Auth; //Métodos de autenticação

class UserController extends Controller
{
    /*

        Comando utilizado para criar este controller: php artisan make:controller UserController

    */

    public function showRegisterForm()
    {
        return view('user.register');
    }
    
    public function register(Request $request)
    {
        //$request Possui todos os campos enviados por POST

        //Se nenhum campo está vazio
        if(!empty($request->name) && !empty($request->email) && !empty($request->password) && !empty($request->confirmPassword))
        {
            //Se o email não for válido
            if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)){
                return redirect()->back()->withInput()->withErrors(['O email informado não é valido']);
            }

            //Se o campo senha estiver diferente do confirmar senha
            if($request->password != $request->confirmPassword)
            {
                return redirect()->back()->withInput()->withErrors(['As senhas não coincidem']);
            }

            $user = new User(); //Instancia a Model User
            $user->name = $request->name;  //Adiciona Nome ao objeto            
            $user->email = $request->email; //Adiciona Email ao objeto
            $user->password = Hash::make($request->password); //Adiciona senha criptografada ao objeto
            $user->save(); //Grava no banco de dados

            return redirect()->route('user.login'); //Redireciona para a rota index

        }
        else
        {
            return redirect()->back()->withInput()->withErrors(['É necessário preencher todos os campos']);
        }
    }

    public function showLoginForm()
    {
        return view('user.login');
    }

    public function login(Request $request)
    {
        //Se não for um email valido
        if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)){
            return redirect()->back()->withInput()->withErrors(['O email informado não é válido']);
        }

        //Vetor associativo com os campos recebidos por request
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        //Com os dados recebidos do login, tenta autenticar
        if(Auth::attempt($credentials))
        {
            return redirect()->route('home');
        }
        else
        {
            //Caso não consiga autenticar, volta um caminho e envia uma mensagem de erro e devolve o input email
            return redirect()->back()->withInput()->withErrors(['Os dados informados não conferem']);
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('home');
    }

    public function profile($id)
    {
        //Busca o usuário através do id recebido pela URL
        $user = User::where('id', $id)->first();

        //Retorna a View enviando a variavel $user junto
        return view('user.profile', [
            'user' => $user
        ]);

    }

}
