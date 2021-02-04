@extends('...site.layout')


{{-- Definindo o título da página --}}
@section('title', 'Editar Arte')

{{-- Definindo o conteudo da página --}}
@section('content')
<div class="h-100 py-5 row align-items-center justify-content-center">
    <div class="container w-50">
        <div class="text-center py-1">
            <h2>Editar Arte</h2>
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

        <div class="row justify-content-center">
            <img class="p-4" src="http://localhost/Dailly-Gallery/public/storage/{{ $art->path }}">
        </div>

        <form id="formArt" action="{{ route('art.edit', ['art' => $art->id]) }}" method="POST" enctype="multipart/form-data">

            @method('PATCH')
            @csrf

            <div class="form-group">
                <input class="form-control" type="text" name="title" placeholder="Título" value="{{ $art->title }}">
            </div>

            <div class="form-group">
                <input class="form-control" type="file" name="art">
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
        $('form#formArt').submit(function(event){

            event.preventDefault(); //Prevenindo o comportamento padrão (evento de submit)

            var camposForm = new FormData($(this)[0]); //Utilizando a classe FormData para armazenar todo os dados do formulário (Para conseguir enviar a imagem por AJAX)

            //Enviando um ajax
            $.ajax({
                url: "{{ route('art.update', ['art' => $art->id]) }}", //Rota que retornará JSON
                type: "POST",
                contentType : false,
                processData : false,
                data: camposForm, //Dados enviados
                dataType: 'json',

                success: function(response){
                    if(response.success === true){
                        //Redirecionar

                        window.location.href = "{{ route('user.profile', ['user' => Auth::guard('user')->user()->id]) }}";
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