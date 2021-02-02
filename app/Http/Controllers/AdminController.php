<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

//Dependências adicionadas
use Illuminate\Support\Facades\Hash; //Métodos para gerar código hash
use Illuminate\Support\Facades\Auth; //Métodos de autenticação
use Illuminate\Http\Response; //Métodos para resposta em json

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
        //$request Possui todos os campos enviados por POST

        //Se nenhum campo está vazio
        if(!empty($request->name) && !empty($request->email) && !empty($request->password) && !empty($request->confirmPassword))
        {
            //Se o email não for válido
            if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)){
                if($request->json){
                    return response()->json(['success' => false,'message' => 'O email informado não é valido']);
                }else{
                    return redirect()->back()->withInput()->withErrors(['O email informado não é valido']);
                }
            }

            //Se o campo senha estiver diferente do confirmar senha
            if($request->password != $request->confirmPassword)
            {
                if($request->json){
                    return response()->json(['success' => false,'message' => 'As senhas não coincidem']);
                }else{
                    return redirect()->back()->withInput()->withErrors(['As senhas não coincidem']);
                }
            }

            $admin = new Admin(); //Instancia a Model User
            $admin->name = $request->name;  //Adiciona Nome ao objeto            
            $admin->email = $request->email; //Adiciona Email ao objeto
            $admin->password = Hash::make($request->password); //Adiciona senha criptografada ao objeto
            $admin->save(); //Grava no banco de dados

            if($request->json){
                return response()->json(['success' => true]);
            }else{
                return redirect()->route('home'); //Redireciona para a rota index
            }
        }
        else
        {
            if($request->json){
                return response()->json(['success' => false,'message' => 'É necessário preencher todos os campos']);
            }else{
                return redirect()->back()->withInput()->withErrors(['É necessário preencher todos os campos']);
            }
        }
    }
}
