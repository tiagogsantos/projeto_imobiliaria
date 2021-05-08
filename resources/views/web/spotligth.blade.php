@extends('web.master.master')

@section('content')

    <div class="container">

        <h2 class="text-center p-5 bg-white text-front">Seja bem-vindo ao nosso empreendimento de destaque</h2>

        <a href="{{ route('web.filter') }}" class="btn btn-front btn-block text-center btn-small">Confira nossos
            imóveis</a>
        <br/>
    </div>

@endsection
