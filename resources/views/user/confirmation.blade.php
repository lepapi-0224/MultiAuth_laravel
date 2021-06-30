@extends('user.layout')

@section('content')

    <div class="jumbotron text-center">
        <div class="row">

            <div class="col-lg-12 margin-tb">

                <div class="pull-left">
                    <h1>Confirmez votre numero de telephone</h1>
                </div>

                <div class="pull-left">
                    <p> Saisissez le code recu par sms dans le champ tout en dessous dans l'ordre exact puis valide</p>
                </div>
            </div>
        </div>

        <form action="" method="POST">
            @csrf
            <div class="row">

                <div class="col-xs-12 col-sm-12 col-md-12 align-center">
                    <div class="form-group">
                        <label>Code:</label>
                        <input type="text" name="code" class="form-control" required/>
                    </div>
                </div>            
                
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                     <button type="submit" class="btn btn-primary">Envoyez</button>
                </div>
            </div>
        </form>

    </div>
        

@endsection