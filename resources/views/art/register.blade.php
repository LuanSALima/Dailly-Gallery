@extends('...site.layout')


{{-- Definindo o título da página --}}
@section('title', 'Cadastrar Arte')

{{-- Definindo o conteudo da página --}}
@section('content')
<div class="h-100 py-5 row align-items-center justify-content-center">
    <div class="container w-50">
        <div class="text-center py-4">
            <h2>Cadastrar Arte</h2>
        </div>
        
        @if($errors->all()) <!--Verifica se possui erros-->
        <div class="alert alert-danger">
                @foreach($errors->all() as $error) <!--Para cada erro encontrado-->
                    <span>{{ $error }}</span>
                @endforeach
        </div>
        @endif

        <form action="{{ route('art.store') }}" method="POST" enctype="multipart/form-data">

            @csrf

            <div class="form-group">
                <input class="form-control" type="text" name="title" placeholder="Título">
            </div>

            <div class="form-group">
                <input class="form-control" type="file" name="art">
            </div>            

            <button class="btn btn-secondary btn-block my-2 bg-cyan">Cadastrar</button>
        </form>
    </div>
</div>
@endsection