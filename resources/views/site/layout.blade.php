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
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.profile', ['user' => Auth::user()->id]) }}">{{ Auth::user()->name }}</a>
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
    </body>
</html>
