@extends('user.layout')

@section('content')

    <div class="jumbotron text-center">
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

        <h1>Veuillez suivre ces deux (02) instructions</h1>
            <p>Pour lier votre compte netnoh a telegram, vous devez au preable telecharger et installer l'application telegram votre mobile ou PC, puis cree un compte</p><br>
        <h3> <b>Premiere etape</b></h3>
        <p> Envoyez Votre numero de telephone <b><a href="http://t.me/Netnoh_bot" target="_blank">Ici</a></b> puis revenez sur cette interface </p><br>
            
        <h3><b>Deuxieme etape</b></h3>
        <p>Cliquez <a href="{{ route('activity') }}"><b>Ici</b></a> pour lier votre compte</p><br>

        <h3><b>Troisieme etape</b></h3>
        <p>Veuillez Confirmer votre numero de telephone <b><a href="{{ url('/confirmation') }}">Ici</a></b>
    </div>

@endsection