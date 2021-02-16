@component('mail::message')
<h1>Recuperar Senha</h1>
<h3>Querido usuário {{ $user->name }}, Foi enviado um pedido para recuperar sua conta em nosso site</h3>
<h3>Acesse o seguinte link para alterar sua senha: <a href="{{ route('recover.account', ['token' => $token]) }}">{{ route('recover.account', ['token' => $token]) }}</a></h3>
@endcomponent