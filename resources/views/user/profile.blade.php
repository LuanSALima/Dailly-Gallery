@extends('...site.layout')


{{-- Definindo o título da página --}}
@section('title', 'Perfil')

{{-- Definindo o conteudo da página --}}
@section('content')
<div class="h-100 py-5 row align-items-center justify-content-center">
    <div class="container">
        
        @if(!empty($user)) {{-- Verifica se existe um usuário retornado do controller --}}
            <div class="text-center py-4">
                <h2>{{ $user->name }}</h2>
            </div>

            @if(!$user->arts->isEmpty()) {{-- Verifica o usuário possui artes cadastradas --}}
            <div class="row">
                @foreach($user->arts as $art)
                    <div class="card m-2 text-center">
                        <div class="card-body">
                            <img src="http://localhost/Dailly-Gallery/public/storage/{{ $art->path }}">
                        </div>
                        <div class="card-footer">
                            <p class="font-weight-bold">{{ $art->title }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            @else
                <h3 class="text-danger text-center">Não possui artes cadastradas</h3>
            @endif
        @else
            <div class="alert alert-danger">
                <span>Usuário não encontrado</span>
            </div>
        @endif

    </div>
</div>
@endsection