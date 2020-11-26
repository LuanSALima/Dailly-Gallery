@extends('...site.layout')


{{-- Definindo o título da página --}}
@section('title', 'Login')

{{-- Definindo o conteudo da página --}}
@section('content')
<div class="h-100 py-5 row align-items-center justify-content-center">
	<div class="container w-50">
		<div class="text-center py-4">
			<h2>Login</h2>
		</div>
		
		@if($errors->all()) <!--Verifica se possui erros-->
		<div class="alert alert-danger">
				@foreach($errors->all() as $error) <!--Para cada erro encontrado-->
					<span>{{ $error }}</span>
				@endforeach
		</div>
		@endif

		<form action="{{ route('user.login.do') }}" method="POST">

			@csrf

			<div class="form-group">
				<input class="form-control" type="text" name="email" placeholder="E-mail">
			</div>

			<div class="form-group">
				<input class="form-control" type="password" name="password" placeholder="Senha">
			</div>
			
			<div class="form-group text-center">
				<a href="#">Esqueceu a senha?</a>
			</div>

			<button class="btn btn-secondary btn-block my-2 bg-cyan">Login</button>
		</form>
	</div>
</div>
@endsection