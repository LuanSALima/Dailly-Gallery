<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Dependências adicionadas
use App\Models\User; //Model Usuario
use Illuminate\Support\Facades\Hash; //Métodos para gerar código hash
use Illuminate\Support\Facades\Auth; //Métodos de autenticação
use Illuminate\Http\Response; //Métodos para resposta em json
use Illuminate\Support\Facades\Validator; //Métodos para validar os dados

class UserController extends Controller
{

    public function showRegisterForm()
    {
        return view('user.register');
    }
    
    public function register(Request $request)
    {
        //Variavel que receberá todas as regras e mensagens de validação
        $validator = Validator::make(
            $request->all(), //$request Possui todos os campos enviados por POST
            $rules = [
                'name' => 'required|min:5|max:30|unique:App\Models\Admin,name|unique:App\Models\User,name',
                'email' => 'required|email|min:3|max:30|unique:App\Models\Admin,email|unique:App\Models\User,email',
                'password' => 'required|min:3|max:30',
                'confirmPassword' => 'required_with:password|same:password|min:3|max:30'
            ],
            $messages = [
                //Mensagens para erros com o 'Name'
                'name.required' => 'O nome está vazio.',
                'name.unique' => 'O nome já está em uso',
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
            $user = new User(); //Instancia a Model User
            $user->name = $request->name;  //Adiciona Nome ao objeto            
            $user->email = $request->email; //Adiciona Email ao objeto
            $user->password = Hash::make($request->password); //Adiciona senha criptografada ao objeto
            $user->save(); //Grava no banco de dados

            if($request->expectsJson()){
                return response()->json(['success' => true]);
            }else{
                return redirect()->route('home'); //Redireciona para a rota index
            }
        }
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
        //Variavel que receberá todas as regras e mensagens de validação
        $validator = Validator::make(
            $request->all(), //$request Possui todos os campos enviados por POST
            $rules = [
                'name' => 'required|min:5|max:30|unique:App\Models\Admin,name|unique:App\Models\User,name,'.Auth::guard("user")->user()->id,
                'email' => 'required|email|min:3|max:30|unique:App\Models\Admin,email|unique:App\Models\User,email,'.Auth::guard("user")->user()->id
            ],
            $messages = [
                //Mensagens para erros com o 'Name'
                'name.required' => 'O nome está vazio.',
                'name.unique' => 'O nome já está em uso',
                'name.min' => 'O nome é necessário pelo menos :min caracteres.',
                'name.max' => 'O nome possui um máximo de :max caracteres.',
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
            //Procura o usuário logado pelo id
            $user = User::where('id', Auth::guard('user')->user()->id)->first();

            //Altera os dados dele pelos campos do formulário
            $user->name = $request->name;
            $user->email = $request->email;

            //Salvar no banco de dados
            $user->save();

            if($request->expectsJson()){
                return response()->json(['success' => true]);
            }else{
                return redirect()->route('home'); //Redireciona para a rota index
            }
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
        //Variavel que receberá todas as regras e mensagens de validação
        $validator = Validator::make(
            $request->all(), //$request Possui todos os campos enviados por POST
            $rules = [
                'currentPassword' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (!\Hash::check($value, Auth::guard('user')->user()->password)) {
                            return $fail(__('Senha atual incorreta.'));
                        }
                    }
                ],
                'newPassword' => 'required|min:3|max:30',
                'confirmNewPassword' => 'required_with:newPassword|same:newPassword|min:3|max:30'
            ],
            $messages = [
                //Mensagem para erros com o 'CurrentPassword'
                'currentPassword.required' => 'A senha atual está vazia.',
                //Mensagens para erros com o 'NewPassword'
                'newPassword.required' => 'A nova senha está vazia.',
                'newPassword.min' => 'A nova senha deve possuir pelo menos :min caracteres.',
                'newPassword.max' => 'A nova senha deve possuir no máximo :max caracteres.',
                //Mensagens para erros com o 'ConfirmNewPassword'
                'confirmNewPassword.required_with' => 'É necessário confirmar a nova senha.',
                'confirmNewPassword.same' => 'As novas senhas não coincidem.',
                'confirmNewPassword.min' => 'O confirmar nova senha deve possuir pelo menos :min caracteres.',
                'confirmNewPassword.max' => 'O confirmar nova senha deve possuir no máximo :max caracteres.',
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
            $user = User::where('password', Auth::guard('user')->user()->password)->first();

            $user->password = Hash::make($request->newPassword);

            $user->save();

            if($request->expectsJson()){
                return response()->json(['success' => true]);
            }else{
                return redirect()->route('home'); //Redireciona para a rota index
            }
        }
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

    public function changeUserProfilePicture(Request $request)
    {
        $user = User::where('id', Auth::guard('user')->user()->id)->first();

        $validator = Validator::make(
            $request->all(),
            $rules = [
                'profile_image' => 'required|image'
            ],
            $messages = [
                'profile_image.required' => 'É necessário escolher uma imagem.',
                'profile_image.image' => 'O arquivo deve ser uma imagem'
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
        else
        {
            $user->profile_pic_path = $request->file('profile_image')->store('profile/'.$user->id);
            
            $user->save();

            return redirect()
                    ->back();
        }
    }

    public function changeUserProfileBackground(Request $request)
    {
        $user = User::where('id', Auth::guard('user')->user()->id)->first();

        $validator = Validator::make(
            $request->all(),
            $rules = [
                'profile_background' => 'required|image'
            ],
            $messages = [
                'profile_background.required' => 'É necessário escolher uma imagem.',
                'profile_background.image' => 'O arquivo deve ser uma imagem'
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
        else
        {
            $user->profile_bg_path = $request->file('profile_background')->store('profile/'.$user->id);
            
            $user->save();

            return redirect()
                    ->back();
        }
    }
}
