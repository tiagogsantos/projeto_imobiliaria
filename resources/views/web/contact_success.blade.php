@extends('web.master.master')

@section('content')

    <div class="container">

        <h2 class="text-center p-5 bg-white text-front">Seu contato foi enviado com sucesso!!!</h2>

        <a href="{{ url()->previous() }}" class="text-front text-center">Continue navegando</a>
        <br/>
    </div>

@endsection
