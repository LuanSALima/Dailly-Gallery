<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Dailly Gallery - @yield('title', 'Site') </title>

        <link rel="stylesheet" type="text/css" href="{{ url(mix('site/bootstrap.css')) }}">
    </head>
    <body>
        
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <a class="navbar-brand" href="{{ route('home') }}">Dailly Gallery</a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".collapse">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-between">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link {{ (Route::current()->getName() === 'art.index') ? 'active' : '' }}" href="{{ route('art.index') }}">Listar Artes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (Route::current()->getName() === 'art.create') ? 'active' : '' }}" href="{{ route('art.create') }}">Cadastrar Arte</a>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        
                        @guest {{-- Caso não esteja logado --}}

                        <li class="nav-item">
                            <a class="nav-link {{ (Route::current()->getName() === 'user.login') ? 'active' : '' }}" href="{{ route('user.login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (Route::current()->getName() === 'user.register') ? 'active' : '' }}" href="{{ route('user.register') }}">Registrar-se</a>
                        </li>

                        @else {{-- Caso esteja logado --}}

                        <li class="nav-item dropdown mx-auto">
                            <a class="nav-link dropdown-toggle" href="#"data-toggle="dropdown" >
                              {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="{{ route('user.profile', ['user' => Auth::user()->id]) }}">Perfil</a>
                              <a class="dropdown-item" href="{{ route('account.edit') }}">Editar Conta</a>
                              <a class="dropdown-item" href="{{ route('account.password') }}">Alterar Senha</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#"data-toggle="dropdown">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-bell" fill="currentColor">
                                    <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2z"/>
                                    <path fill-rule="evenodd" d="M8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
                                </svg>
                                <span class="badge badge-danger">5</span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="">Notificação 1</a>
                                <a class="dropdown-item" href="">Notificação 2</a>
                                <a class="dropdown-item" href="">Notificação 3</a>
                                <a class="dropdown-item" href="">Notificação 4</a>
                                <a class="dropdown-item" href="">Notificação 5</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('user.logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                        
                        @endguest
                    </ul>
                </div>
            </nav>
        </header>

        {{-- Resgatando o conteudo do Site --}}
        @yield('content')

        <script type="text/javascript" src="{{ url(mix('site/jquery.js')) }}"></script>
        <script type="text/javascript" src="{{ url(mix('site/bootstrap.js')) }}"></script>

        {{-- Resgatando os scripts do conteudo, para ficar abaixo do jquery --}}
        @yield('content-script')
    </body>
</html>
