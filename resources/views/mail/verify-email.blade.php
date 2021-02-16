@component('mail::message')
<h1>Verificar Conta</h1>
<h3>Querido usuário {{ $user->name }}, este email foi cadastrado em nosso site e é necessário que você acesse o seguinte link para verificar seu email: <a href="{{ route('user.verify.email', ['token' => $token]) }}">{{ route('user.verify.email', ['token' => $token]) }}</a></h3>
@endcomponent