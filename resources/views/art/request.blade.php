@extends('...site.layout')


{{-- Definindo o título da página --}}
@section('title', 'Alterar Requisição Arte')

{{-- Definindo o conteudo da página --}}
@section('content')
<div class="h-100 py-5 row align-items-center justify-content-center">
    <div class="container w-50">
        <div class="text-center py-1">
            <h2></h2>
        </div>
        
        @if($errors->all()) {{-- Verifica se possui erros --}}
        <div class="alert alert-danger">
                @foreach($errors->all() as $error) {{-- Para cada erro encontrado --}}
                    <span>{{ $error }}</span>
                    <br>
                @endforeach
        </div>
        @endif

        @if(Auth::guard('user')->check())

            <div class="row justify-content-center">
                <h4>{{ $art->title }}</h4>
            </div>
            <div class="row justify-content-center">
                <img class="p-4" src="http://localhost/Dailly-Gallery/public/storage/{{ $art->path }}">
            </div>
            <div class="row justify-content-center">
                <span class="text-bold mr-2">Status:</span>
                <span>{{ ($art->status == 'pendent') ? 'Pendente' : 'Rejeitado' }}</span>
            </div>
            @if($art->status == 'rejected')
            <div class="row justify-content-center">
                <span class="text-bold mr-2 text-danger">Motivo:</span>
                <span class="text-danger">{{ $art->message_status }}</span>
            </div>
            @endif

            <div class="text-center" id="mensagem">
                
            </div>

            <form action="{{ route('art.update', ['art' => $art->id]) }}" method="PATCH" enctype="multipart/form-data">

                @method('PATCH')
                @csrf

                <div class="form-group">
                    <label>Título</label>
                    <input class="form-control" type="text" name="title" value="{{ $art->title }}">
                </div>
                <div class="form-group">
                    <label>Imagem</label>
                    <input class="form-control" type="file" name="image">
                </div>
                <div class="form-group">
                    <input class="form-control btn btn-secondary btn-block my-2 bg-cyan" type="submit" name="edit" value="Alterar">
                </div>
            </form>
        @endif

        @if(Auth::guard('admin')->check())
        <table class="table table-striped table-bordered">
            <tr class="thead-dark text-center">
                <th colspan="3">
                    <h4>Dados Arte</h4>
                </th>
            </tr>
            <tr>
                <th class="align-middle">ID</th>
                <td>{{ $art->id }}</td>
            </tr>
            <tr>
                <th class="align-middle">Título</th>
                <td>{{ $art->title }}</td>
            </tr>
            <tr>
                <th class="align-middle">Imagem</th>
                <td>
                    <img class="p-4" src="http://localhost/Dailly-Gallery/public/storage/{{ $art->path }}">
                </td>
            </tr>
            <tr>
                <th class="align-middle">Autor</th>
                <td>
                    <a href="{{ route('user.profile', ['user' => $art->author()->first()->id]) }}">{{ $art->author()->first()->name }}</a>
                </td>
            </tr>
            <tr>
                <th class="align-middle">Data de Criação</th>
                <td>
                    {{ date('d/m/Y H:i', strtotime($art->created_at)) }}
                </td>
            </tr>
        </table>

        <div id="mensagem">
                
        </div>

        <table class="table table-borderless">
            
            <tr>
                <td>
                    <form action="{{ route('art.status.change', ['art' => $art->id]) }}" method="POST">
                    @method('PATCH')
                    @csrf
                    <input style="display: none;" type="text" name="aprove" value="aproved">
                    <input class="btn btn-success" type="submit" value="Aprovar">
                    </form>
                </td>
            </tr>
            
            <tr>
                <form action="{{ route('art.status.change', ['art' => $art->id]) }}" method="POST">
                @method('PATCH')
                @csrf
                <td>
                    <input  style="display: none;" type="text" name="reject" value="reproved">
                    <input class="btn btn-danger" type="submit"value="Reprovar">
                </td>
                <td>
                    <div class="form-group">
                        <label>Motivo:</label>
                        <textarea class="form-control" name="reason" placeholder="Escreva o motivo..."></textarea>
                    </div>
                </td>
                </form>
            </tr>
            
        </table>
        @endif
    </div>
</div>
@endsection

{{-- Definindo os scripts da página --}}
@section('content-script')
<script>
            
    $(function(){
        $('form').submit(function(event){

            event.preventDefault(); //Prevenindo o comportamento padrão (evento de submit)

            var camposForm = new FormData($(this)[0]);
            
            //Enviando um ajax
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                contentType : false,
                processData : false,
                data: camposForm, //Dados enviados
                dataType: 'json',

                success: function(response){
                    if(response.success === true){
                        //Redirecionar

                        window.location.href = "{{ route('art.requestlist') }}";
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