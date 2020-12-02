<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Dependências adicionadas
use App\Models\User; //Model Usuario
use Illuminate\Support\Facades\Hash; //Métodos para gerar código hash
use Illuminate\Support\Facades\Auth; //Métodos de autenticação
use Illuminate\Http\Response; //Métodos para resposta em json

class UserController extends Controller
{

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
            return redirect()->back()->withErrors(['É necessário preencher todos os campos']);
        }
    }

    public function asyncRegister(Request $request)
    {
        //Se nenhum campo está vazio
        if(!empty($request->name) && !empty($request->email) && !empty($request->password) && !empty($request->confirmPassword))
        {
            //Se o email não for válido
            if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)){
                return response()->json(['success' => false,'message' => 'O email informado não é valido']);
            }

            //Se o campo senha estiver diferente do confirmar senha
            if($request->password != $request->confirmPassword)
            {
                return response()->json(['success' => false,'message' => 'As senhas não coincidem']);
            }

            $user = new User(); //Instancia a Model User
            $user->name = $request->name;  //Adiciona Nome ao objeto            
            $user->email = $request->email; //Adiciona Email ao objeto
            $user->password = Hash::make($request->password); //Adiciona senha criptografada ao objeto
            $user->save(); //Grava no banco de dados

            return response()->json(['success' => true]);
        }
        else
        {
            return response()->json(['success' => false,'message' => 'É necessário preencher todos os campos']);
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
            return redirect()->back()->withErrors(['O email informado não é válido']);
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
            return redirect()->back()->withErrors(['Os dados informados não conferem']);
        }
    }

    public function asyncLogin(Request $request)
    {
        //Se não for um email valido
        if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)){
            return response()->json(['success' => false,'message' => 'O email informado não é válido']);
        }

        //Vetor associativo com os campos recebidos por request
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        //Com os dados recebidos do login, tenta autenticar
        if(Auth::attempt($credentials))
        {
            return response()->json(['success' => true]);
        }
        else
        {
            return response()->json(['success' => false,'message' => 'Os dados informados não conferem']);
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

    public function showEditAccountForm()
    {
        if(Auth::check())
        {
            //Busca o usuário através do id do usuário autenticado
            $user = User::where('id', Auth::user()->id)->first();

            //Retorna a View enviando a variavel $user junto
            return view('user.edit-account', [
                'user' => $user
            ]);
        }
        else
        {
            return redirect()->route('user.login')->withErrors(['É necessário estar logado para editar seu perfil']);
        }
    }

    public function editAccount(Request $request)
    {
        //Se nenhum campo está vazio
        if(!empty($request->name) && !empty($request->email))
        {
            //Se não for um email valido
            if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)){
                return redirect()->back()->withErrors(['O email informado não é válido']);
            }

            //Procura o usuário logado pelo id
            $user = User::where('id', Auth::user()->id)->first();

            //Altera os dados dele pelos campos do formulário
            $user->name = $request->name;
            $user->email = $request->email;

            //Salvar no banco de dados
            $user->save();

            return redirect()->route('home');
        }
        else
        {
            return redirect()->back()->withErrors(['É necessário preencher todos os campos']);
        }
    }

    public function asyncEditAccount(Request $request)
    {
        //Se nenhum campo está vazio
        if(!empty($request->name) && !empty($request->email))
        {
            //Se não for um email valido
            if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)){
                return response()->json(['success' => false, 'message' => 'O email informado não é válido']);
            }

            //Procura o usuário logado pelo id
            $user = User::where('id', Auth::user()->id)->first();

            //Altera os dados dele pelos campos do formulário
            $user->name = $request->name;
            $user->email = $request->email;

            //Salvar no banco de dados
            $user->save();

            return response()->json(['success' => 'true']);
        }
        else
        {
            return response()->json(['success' => false,'message' => 'É necessário preencher todos os campos']);
        }
    }

    public function showEditPasswordForm()
    {
        if(Auth::check())
        {
            return view('user.edit-password');
        }
        else
        {
            return redirect()->route('user.login')->withErrors(['É necessário estar logado para alterar sua senha']);
        }
    }

    public function editPassword(Request $request)
    {
        //Se nenhum campo está vazio
        if(!empty($request->currentPassword) && !empty($request->newPassword) && !empty($request->confirmNewPassword))
        {
            //Checa se a senha escrita no formulário é igual a senha do usuário logado
            if(Hash::check($request->currentPassword, Auth::user()->password))
            {
                //Se a nova senha e o confirmar nova senha são iguais
                if($request->newPassword == $request->confirmNewPassword)
                {
                    $user = User::where('password', Auth::user()->password)->first();

                    $user->password = Hash::make($request->newPassword);

                    $user->save();

                    return redirect()->route('home');
                }
                else
                {
                    return redirect()->back()->withErrors(['As novas senhas não coincidem']);
                }
            }
            else
            {
                return redirect()->back()->withErrors(['Senha Atual incorreta']);
            }
        }
        else
        {
            return redirect()->back()->withErrors(['É necessário preencher todos os campos']);
        }
    }

    public function asyncEditPassword(Request $request)
    {
        //Se nenhum campo está vazio
        if(!empty($request->currentPassword) && !empty($request->newPassword) && !empty($request->confirmNewPassword))
        {
            //Checa se a senha escrita no formulário é igual a senha do usuário logado
            if(Hash::check($request->currentPassword, Auth::user()->password))
            {
                //Se a nova senha e o confirmar nova senha são iguais
                if($request->newPassword == $request->confirmNewPassword)
                {
                    //Usuário logado
                    $user = User::where('password', Auth::user()->password)->first();

                    $user->password = Hash::make($request->newPassword);

                    $user->save();

                    return response()->json(['success' => true]); 
                }
                else
                {
                    return response()->json(['success' => false,'message' => 'As novas senhas não coincidem']);
                }
            }
            else
            {
                return response()->json(['success' => false,'message' => 'Senha Atual incorreta']);
            }
        }
        else
        {
            return response()->json(['success' => false,'message' => 'É necessário preencher todos os campos']);
        }
    }
}
