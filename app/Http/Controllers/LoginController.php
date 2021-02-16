<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Dependências adicionadas
use App\Models\User; //Model Usuario
use App\Models\Admin; //Model Admin
use App\Mail\RecoverAccount; //Email para recuperar conta
use Illuminate\Support\Facades\Auth; //Métodos de autenticação
use Illuminate\Http\Response; //Métodos para resposta em json
use Illuminate\Support\Facades\Hash; //Métodos para gerar código hash
use Illuminate\Support\Facades\Validator; //Métodos para validar os dados
use Illuminate\Support\Str; //Métodos para gerar string para o token
use Illuminate\Support\Facades\Mail; //Métodos para enviar email

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

    public function forgotPassword()
    {
        return view('site.forgot-password');
    }

    public function recoverPassword(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            $rules = [
                'email' => 'required|email|min:3|max:30',
            ],
            $messages = [
                'required' => 'O :attribute está vazio.',
                'email' => 'O :attribute é inválido.',
                'min' => 'O :attribute é necessário pelo menos :min caracteres.',
                'max' => 'O :attribute possui um máximo de :max caracteres.',
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
            if(($user = User::where('email', $request->email))->exists())
            {
                $user = $user->first();

                $pwdToken = Str::random(60);

                while(User::where('password_token', $pwdToken)->exists()){
                    $pwdToken = Str::random(60);
                }

                Mail::to($user->email)->send(new RecoverAccount($user, $pwdToken));

                $user->password_token = $pwdToken;

                $user->save();

                if ($request->expectsJson()) {
                    return response()->json(['success' => true, 'message' => 'O E-mail foi enviado com sucesso!']);
                } else {
                    return redirect()->back()->with('successMessage', 'O E-mail foi enviado com sucesso!');
                }
            }
            else
            {
                $error = "Este e-mail não foi cadastrado em nosso site";

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
    }

    public function showRecoverAccount($token)
    {
        if ($user = User::firstWhere('password_token', $token))
        {
            return view('site.recover-account', [
                'token' => $token
            ]);   
        } else {
            return view('site.forgot-password')->withErrors('Token inválido. Acesse novamente o link enviado para o seu email');
        }
    }

    public function recoverAccount(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            $rules = [
                'password' => 'required|min:3|max:30',
                'confirmPassword' => 'required_with:password|same:password|min:3|max:30'
            ],
            $messages = [
                'password.required' => 'A senha está vazia.',
                'password.min' => 'A senha deve possuir pelo menos :min caracteres.',
                'password.max' => 'A senha deve possuir no máximo :max caracteres.',
                
                'confirmPassword.required_with' => 'É necessário confirmar a senha.',
                'confirmPassword.same' => 'As senhas não coincidem.',
                'confirmPassword.min' => 'O confirmar senha deve possuir pelo menos :min caracteres.',
                'confirmPassword.max' => 'O confirmar senha deve possuir no máximo :max caracteres.',
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
            if ($user = User::firstWhere('password_token', $request->token))
            {
                
                $user->password = Hash::make($request->password);
                $user->save();

                if ($request->expectsJson()) {
                    return response()->json(['success' => true]);
                } else {
                    return redirect()->route('login');
                }

            } else {

                $error = "Token inválido";

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
    }
}
