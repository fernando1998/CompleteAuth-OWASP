@extends('layouts.extra')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Código de Validación</div>

                <div class="card-body">
                    <form action="{{route('confirmCodeAuth',['id'=>Auth::user()->id])}}" method="post">
                        @csrf
                        Double Autentification
                        <input class="form-control" type="text" name="code" required/>
                        <br>
                        <div align="center">
                            <a class="btn btn-primary" href="{{route('RetryCode',['id'=>Auth::user()->id])}}">Reenviar Código</a>
                        
                            <button class="btn btn-success" type="submit">
                                Enviar
                            </button>
                        </div>
                        
                    </form>
                </div>
               
                
            </div>
        </div>
    </div>
</div>
@endsection
