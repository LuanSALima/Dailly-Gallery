@extends('...site.layout')


{{-- Definindo o título da página --}}
@section('title', 'Alterar Senha')

{{-- Definindo o conteudo da página --}}
@section('content')
<div class="h-100 py-5 row align-items-center justify-content-center">
    <div class="container w-50">
        <div class="text-center py-4">
            <h2>Alterar Senha</h2>
        </div>
        
        @if($errors->all()) {{-- Verifica se possui erros --}}
        <div class="alert alert-danger">
                @foreach($errors->all() as $error) {{-- Para cada erro encontrado --}}
                    <span>{{ $error }}</span>
                @endforeach
        </div>
        @endif

        <div id="mensagem">
            
        </div>

        <form id="formEditPassword" action="{{ route('account.password.edit') }}" method="POST">

            @method('PATCH')
            @csrf

            <div class="form-group">
                <input class="form-control" type="password" name="currentPassword" placeholder="Senha Atual">
            </div>

            <div class="form-group">
                <input class="form-control" type="password" name="newPassword" placeholder="Nova Senha">
            </div>
            
            <div class="form-group">
                <input class="form-control" type="password" name="confirmNewPassword" placeholder="Confirmar Nova Senha">
            </div>

            <button class="btn btn-secondary btn-block my-2 bg-cyan">Alterar</button>
        </form>
    </div>
</div>
@endsection


{{-- Definindo os scripts da página --}}
@section('content-script')
<script>
   
    $(function(){
        $('form#formEditPassword').submit(function(event){

            event.preventDefault(); //Prevenindo o comportamento padrão (evento de submit)

            //Enviando um ajax
            $.ajax({
                url: "{{ route('account.async.password') }}", //Rota que retornará JSON
                type: "PATCH",
                data: $(this).serialize(),
                dataType: 'json',

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