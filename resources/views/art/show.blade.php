@extends('...site.layout')


{{-- Definindo o título da página --}}
@section('title', 'Detalhes da Artes')

{{-- Definindo o conteudo da página --}}
@section('content')
<div class="container">
    <div class="text-center py-4">
        <h2>Detalhes da Arte</h2>
    </div>

    <div id="mensagem">
        
    </div>

    @if(!empty($art))
    <div class="row justify-content-center">
        <div class="card m-2 text-center">
            <div class="card-body">
                <img src="http://localhost/Dailly-Gallery/public/storage/{{ $art->path }}">
            </div>
            <div class="card-footer">
                <p class="font-weight-bold">{{ $art->title }}</p>
                <span>{{ $art->author()->first()->name }}</span>
                <span>{{ date('d/m/Y H:i', strtotime($art->created_at)) }}</span>
                <div class="row flex justify-content-center align-items-center">
                    <span name="num-likes" class="mr-2">
                        {{ $art->likes->count() }}
                    </span>
                        
                    <form name="like" action="{{ route('art.like', ['art_id' => $art->id]) }}" method="POST">

                        @csrf

                        {{-- Verifica se o usuário logado ja deu um like nesta arte --}}
                        @if(Auth::check() && !empty(Auth::user()->likes->where('art', $art->id)->first()))
                            <button name="button" class="btn btn-danger">
                        @else
                            <button name="button" class="btn btn-success">
                        @endif
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-hand-thumbs-up" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M6.956 1.745C7.021.81 7.908.087 8.864.325l.261.066c.463.116.874.456 1.012.965.22.816.533 2.511.062 4.51a9.84 9.84 0 0 1 .443-.051c.713-.065 1.669-.072 2.516.21.518.173.994.681 1.2 1.273.184.532.16 1.162-.234 1.733.058.119.103.242.138.363.077.27.113.567.113.856 0 .289-.036.586-.113.856-.039.135-.09.273-.16.404.169.387.107.819-.003 1.148a3.163 3.163 0 0 1-.488.901c.054.152.076.312.076.465 0 .305-.089.625-.253.912C13.1 15.522 12.437 16 11.5 16v-1c.563 0 .901-.272 1.066-.56a.865.865 0 0 0 .121-.416c0-.12-.035-.165-.04-.17l-.354-.354.353-.354c.202-.201.407-.511.505-.804.104-.312.043-.441-.005-.488l-.353-.354.353-.354c.043-.042.105-.14.154-.315.048-.167.075-.37.075-.581 0-.211-.027-.414-.075-.581-.05-.174-.111-.273-.154-.315L12.793 9l.353-.354c.353-.352.373-.713.267-1.02-.122-.35-.396-.593-.571-.652-.653-.217-1.447-.224-2.11-.164a8.907 8.907 0 0 0-1.094.171l-.014.003-.003.001a.5.5 0 0 1-.595-.643 8.34 8.34 0 0 0 .145-4.726c-.03-.111-.128-.215-.288-.255l-.262-.065c-.306-.077-.642.156-.667.518-.075 1.082-.239 2.15-.482 2.85-.174.502-.603 1.268-1.238 1.977-.637.712-1.519 1.41-2.614 1.708-.394.108-.62.396-.62.65v4.002c0 .26.22.515.553.55 1.293.137 1.936.53 2.491.868l.04.025c.27.164.495.296.776.393.277.095.63.163 1.14.163h3.5v1H8c-.605 0-1.07-.081-1.466-.218a4.82 4.82 0 0 1-.97-.484l-.048-.03c-.504-.307-.999-.609-2.068-.722C2.682 14.464 2 13.846 2 13V9c0-.85.685-1.432 1.357-1.615.849-.232 1.574-.787 2.132-1.41.56-.627.914-1.28 1.039-1.639.199-.575.356-1.539.428-2.59z"/>
                            </svg>
                        </button>
                    </form>

                    <div class="p-4"></div>

                    <span name="num-favorites" class="p-2">
                        {{ $art->favorites->count() }}
                    </span>

                    <form name="favorite" action="{{ route('art.favorite', ['art_id' => $art->id]) }}" method="POST">
                        @csrf

                        {{-- Verifica se o usuário logado ja deu um like nesta arte --}}
                        @if(Auth::check() && !empty(Auth::user()->favorites->where('art', $art->id)->first()))
                            <button name="button" class="btn btn-danger">
                        @else
                            <button name="button" class="btn btn-success">
                        @endif
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-star-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-5 w-50">
        <div id="mensagem-comentario"></div>

        @if(Auth::check() && empty( $comment = (Auth::user()->comments->where('art', $art->id)->first())))
        <form name="comment" action="{{ route('art.comment.store', ['art_id' => $art->id]) }}" method="POST">
            @csrf
            <textarea class="form-control border border-secondary" name="text" placeholder="Deixe seu comentário ..."></textarea>
            <button name="button" class="btn-secondary btn-block my-2">Comentar</button>
        </form>
        @else
        <form name="comment" action="{{ route('art.comment.destroy', ['art_comment' => $comment->id]) }}" method="POST">
            @csrf
            @method('delete')
            <input name="delete" class="form-control border border-secondary" placeholder="{{ $comment->text }}" readonly></input>
            <button name="button" class="btn-danger btn-block my-2">Remover</button>
        </form>
        @endif

        @foreach($art->comments as $comment)
            <hr class="border border-secondary">
            <div class="media p-2">
                <div class="media-body">
                    <h5 class="mt-0">{{ $comment->user()->first()->name }} <span>| {{ date('d/m/Y H:i', strtotime($comment->created_at)) }}</span></h5>
                    <p>{{ $comment->text }}</p>
                </div>
            </div>
        @endforeach
    </div>
    
    @else
        <div class="alert alert-danger">
            <span>Arte não encontrada</span>
        </div>
    @endif
</div>
@endsection

{{-- Definindo os scripts da página --}}
@section('content-script')
<script>

    $(function(){
        $('form').submit(function(event){

            event.preventDefault(); //Prevenindo o comportamento padrão (evento de submit)

            var camposForm = new FormData($(this)[0]);
            camposForm.append("json", 1);

            //Botão do formulário
            var formButton = $(this).find('[name=button]');

            //Valor boleano para verificar se é o formulário de comentário que está invocando está função
            var isComment = false;

            //Se o formulário possui o name de like, favorito ou comentário
            if($(this).attr('name') == 'like'){
                var num = $(this).parent().find('[name=num-likes]');
            }
            if($(this).attr('name') == 'favorite'){
                var num = $(this).parent().find('[name=num-favorites]');
            }
            if($(this).attr('name') == 'comment'){
                isComment = true;
                var isDelete = false;
                //Se o formulário de comentário possui um input com name delete
                if(!(this.delete === undefined))
                {
                    isDelete = true;
                }
            }

            //Enviando um ajax
            $.ajax({
                url: $(this).attr('action'), //Rota que retornará JSON
                type: $(this).attr('method'),
                contentType : false,
                processData : false,
                data: camposForm, //Dados enviados
                dataType: 'json',

                success: function(response){
                    if(response.success === true){

                        //Se não for o formulário de adicionar comentário
                        if(!isComment){
                            //Se estiver verde, remove o verde e coloca vermelho e vice-versa
                            if(formButton.hasClass('btn-success')){
                               formButton.removeClass('btn-success').addClass('btn-danger');
                               num.html( parseInt(num.html())+1 );
                            }else{
                                formButton.removeClass('btn-danger').addClass('btn-success');
                                num.html( parseInt(num.html())-1 );
                            }
                        }else{
                            //Se for o formulario de deletar comentário
                            if(!isDelete){
                                $('#mensagem-comentario').addClass("alert alert-success").html('Comentário enviado!');
                                formButton.addClass('btn-success').prop('disabled', true);
                            }else{
                                $('#mensagem-comentario').addClass("alert alert-success").html('Comentário deletado com sucesso!');
                                formButton.removeClass('btn-danger').addClass('btn-success').prop('disabled', true);
                            } 
                        }
                    }else{
                        //Apresentar erro
                        if(!isComment){
                            $('#mensagem').addClass("alert alert-danger").html(response.message);
                        }else{
                            $('#mensagem-comentario').addClass("alert alert-danger").html(response.message);
                        }
                    }
                },
                error: function(response)
                {
                    if(!isComment){
                        $('#mensagem').addClass("alert alert-danger").html("Ocorreu um erro ao enviar os dados. Tente mais tarde");
                    }else{
                        $('#mensagem-comentario').addClass("alert alert-danger").html("Ocorreu um erro ao enviar os dados. Tente mais tarde");
                    }
                }
            });
        });
    });

</script>
@endsection