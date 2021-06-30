@extends('user.layout')

@section('content')

    <div class="jumbotron text-center">
        <h1>Bienvenue sur l'assistant virtuel de Netnoh</h1>
        <h2>Que souhaitez vous faire:</h2>
        
        <h3><b>1. Me connecter à Netnoh</b> </h3>
            <p>Pour vous connecter à la première microfinance digitale, cliquez <b><a href="http://www.netnoh.com"> Ici </a></b></p>
        <h3><b>2. Lier mon compte Netnoh à Telegram</b></h3>
            <p>Pour lier mon compte Netnoh a l'assistance virtuelle, cliquez <b><a href="{{ url('/choice') }}"> Ici </a></b></p>
    </div>

@endsection
