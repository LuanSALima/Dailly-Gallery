@extends('...site.layout')


{{-- Definindo o título da página --}}
@section('title', 'Artes Pendentes')

{{-- Definindo o conteudo da página --}}
@section('content')
<div class="h-100 py-5 row align-items-center justify-content-center">
    <div class="container w-50">
        <div class="text-center py-1">
            <h2>Artes Pendentes</h2>
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

        <table class="table table-bordered text-center">
            <tr class="thead-dark">
                <th colspan="6" class="align-middle">Novas Artes</th>
            </tr>
            <tr class="thead-dark">
                <th>ID</th>
                @if(Auth::guard('admin')->check())
                <th>Autor</th>
                @endif
                <th>Status</th>
                <th>Título</th>
                <th>Data</th>
                <th colspan="2">Imagem</th>
            </tr>

            @foreach($arts as $art)
            @if($art->status == 'rejected')
            <tr class="table-danger">
            @else
            <tr class="table-active">
            @endif
                <th class="align-middle">{{ $art->id }}</th>
                @if(Auth::guard('admin')->check())
                <td class="align-middle">
                    <a href="{{ route('user.profile', ['user' => $art->author()->first()->id]) }}">
                        {{ $art->author()->first()->name }}
                    </a>
                </td>
                @endif
                <td class="align-middle">{{ ($art->status == 'pendent') ? 'Pendente' : 'Rejeitado' }}</td>            
                <td class="align-middle">{{ $art->title }}</td>
                <td class="align-middle">{{ date('d/m/Y H:i', strtotime($art->created_at)) }}</td>
                <td class="align-middle">
                    <img src="http://localhost/Dailly-Gallery/public/storage/{{ $art->path }}" style="max-width: 100px;max-height: 100px">
                </td>
                <td class="align-middle">
                    <a href="{{ route('art.request', ['art' => $art->id]) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-clipboard-check" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                      <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                      <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                    </svg>
                    </a>
                </td>
            </tr>
            @endforeach
        </table>

        <table class="table table-bordered text-center">
            <tr class="thead-dark">
                <th colspan="6" class="align-middle">Editar Artes Existentes</th>
            </tr>
            <tr class="thead-dark">
                <th>ID</th>
                @if(Auth::guard('admin')->check())
                <th>Autor</th>
                @endif
                <th>Status</th>
                <th>Título</th>
                <th>Data</th>
                <th colspan="2">Imagem</th>
            </tr>
            @foreach($artChanges as $artChange)
            @if($artChange->status == 'rejected')
            <tr class="table-danger">
            @else
            <tr class="table-active">
            @endif
                <th class="align-middle">{{ $artChange->id }}</th>
                @if(Auth::guard('admin')->check())
                <td class="align-middle">
                    <a href="{{ route('user.profile', ['user' => $artChange->author()->id]) }}">
                        {{ $artChange->author()->name }}
                    </a>
                </td>
                @endif
                <td class="align-middle">{{ ($artChange->status == 'pendent') ? 'Pendente' : 'Rejeitado' }}</td>            
                <td class="align-middle">{{ $artChange->new_title }}</td>
                <td class="align-middle">{{ date('d/m/Y H:i', strtotime($artChange->created_at)) }}</td>
                <td class="align-middle">
                    <img src="http://localhost/Dailly-Gallery/public/storage/{{ $artChange->new_image_path }}" style="max-width: 100px;max-height: 100px">
                </td>
                <td class="align-middle">
                    <a href="{{ route('art.requestedit', ['artChange' => $artChange->id]) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-clipboard-check" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                      <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                      <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                    </svg>
                    </a>
                </td>
            </tr>
            @endforeach
        </table>
        
    </div>
</div>
@endsection

{{-- Definindo os scripts da página --}}
@section('content-script')
<script>

</script>
@endsection