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
		
		@if($errors->any()) {{-- Verifica se possui erros --}}
        <div class="alert alert-danger">
                @foreach($errors->all() as $error) {{-- Para cada erro encontrado --}}
                    <span>{{ $error }}</span>
                    <br>
                @endforeach
        </div>
        @endif

		<div id="mensagem">
            
        </div>

		<form id="formLogin" action="{{ route('login.do') }}" method="POST">

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


{{-- Definindo os scripts da página --}}
@section('content-script')
<script>
    
    $(function(){
        $('form#formLogin').submit(function(event){

            event.preventDefault(); //Prevenindo o comportamento padrão (evento de submit)

            var camposForm = new FormData($(this)[0]);
            camposForm.append("json", 1);

            //Enviando um ajax
            $.ajax({
                url: "{{ route('login.do') }}", //Rota que retornará JSON
                type: "POST",
                data: camposForm,
                dataType: 'json',
                contentType : false,
                processData : false,

                success: function(response){
                    if(response.success === true){
                        //Redirecionar

                        window.location.href = "{{ route('home') }}";
                    }else{
                        //Apresentar erro

                        $('#mensagem').addClass("alert alert-danger").html(response.message);
                    }
                },
                error: function(response)
                {
                    $('#mensagem').addClass("alert alert-danger").html("Ocorreu um erro ao enviar os dados. Tente mais tarde");
                }
            });
        });
    });
   
</script>
@endsection