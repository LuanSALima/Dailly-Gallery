@extends('...site.layout')


{{-- Definindo o título da página --}}
@section('title', 'Editar Perfil')

{{-- Definindo o conteudo da página --}}
@section('content')
<div class="h-100 py-5 row align-items-center justify-content-center">
    <div class="container w-50">
        <div class="text-center py-4">
            <h2>Alterar Dados da Conta</h2>
        </div>
        
        @if($errors->all()) {{-- Verifica se possui erros --}}
        <div class="alert alert-danger">
                @foreach($errors->all() as $error) {{-- Para cada erro encontrado --}}
                    <span>{{ $error }}</span>
                    <br>
                @endforeach
        </div>
        @endif

        <div id="mensagem">
            
        </div>

        <form id="formEditProfile" action="{{ route('account.edit.do') }}" method="POST">

            @method('PATCH')
            @csrf

            <div class="form-group">
                <label>Nome</label>
                <input class="form-control" type="text" name="name" placeholder="Nome" value="{{ (isset($old->name)) ? $old->name : $user->name }}">
            </div>

            <div class="form-group">
                <label>E-mail</label>
                <input class="form-control" type="text" name="email" placeholder="E-mail" value="{{ $user->email }}">
            </div>

            <button class="btn btn-secondary btn-block my-2 bg-cyan">Editar</button>
        </form>
    </div>
</div>
@endsection


{{-- Definindo os scripts da página --}}
@section('content-script')
<script>
            
    $(function(){
        $('form#formEditProfile').submit(function(event){

            event.preventDefault(); //Prevenindo o comportamento padrão (evento de submit)

            //Enviando um ajax
            $.ajax({
                url: "{{ route('account.edit.do') }}", //Rota que retornará JSON
                type: "POST",
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