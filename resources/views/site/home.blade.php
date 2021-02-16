@extends('...site.layout')


{{-- Definindo o título da página --}}
@section('title', 'Home')

{{-- Definindo o conteudo da página --}}
@section('content')
<div class="h-100 py-5 row align-items-center justify-content-center">
	<div class="container w-50">
		<div class="text-center py-4">
			<h2>Home</h2>
		</div>
		
		@if($errors->any()) {{-- Verifica se possui erros --}}
        <div class="alert alert-danger">
                @foreach($errors->all() as $error) {{-- Para cada erro encontrado --}}
                    <span>{{ $error }}</span>
                    <br>
                @endforeach
        </div>
        @endif

        @if (\Session::has('successMessage'))
        <div class="alert alert-success">
             <span>
                {!! \Session::get('successMessage') !!}
            </span>
        </div>
        @endif


	</div>
</div>
@endsection


{{-- Definindo os scripts da página --}}
@section('content-script')
<script>
   
</script>
@endsection