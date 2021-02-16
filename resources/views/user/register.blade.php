@extends('...site.layout')


{{-- Definindo o título da página --}}
@section('title', 'Registrar-se')

{{-- Definindo o conteudo da página --}}
@section('content')

<div id="carregando" style=" display:none;position:fixed;z-index:1000;top:0;left:0;height:100%; width:100%;background: rgba( 255, 255, 255, .8 ) url('http://cdn.lowgif.com/full/b565ca96703fc1d5-.gif') 50% 50% no-repeat;"></div>

<div class="h-100 py-5 row align-items-center justify-content-center">
    <div class="container w-50">
        <div class="text-center py-4">
            <h2>Registrar-se</h2>
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

        <form id="formCad" action="{{ route('user.register.do') }}" method="POST">

            @csrf

            <div class="form-group">
                <input class="form-control" type="text" name="name" placeholder="Nome">
            </div>

            <div class="form-group">
                <input class="form-control" type="text" name="email" placeholder="E-mail">
            </div>

            <div class="form-group">
                <input class="form-control" type="password" name="password" placeholder="Senha">
            </div>
            
            <div class="form-group">
                <input class="form-control" type="password" name="confirmPassword" placeholder="Confirmar Senha">
            </div>

            <button class="btn btn-secondary btn-block my-2 bg-cyan">Registrar</button>
        </form>
    </div>
</div>
@endsection


{{-- Definindo os scripts da página --}}
@section('content-script')
<script>

    /*Apresenta o Gif de Carregamento*/
    $(document).ajaxStart(function(){
        $('#carregando').show();
    }).ajaxStop(function (){
        $('#carregando').hide();
    });
            
    $(function(){
        $('form#formCad').submit(function(event){

            event.preventDefault(); //Prevenindo o comportamento padrão (evento de submit)

            //Enviando um ajax
            $.ajax({
                url: "{{ route('user.register.do') }}", //Rota que retornará JSON
                type: "POST",
                data: $(this).serialize(),
                dataType: 'json',

                success: function(response){
                    if(response.success === true){
                        //Redirecionar

                        window.location.href = "{{ route('login') }}";
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