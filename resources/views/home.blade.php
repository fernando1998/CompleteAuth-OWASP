@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    Bienvenido: 
                    {{ Auth::user()->name }}
                     <hr>
                    Autentificación en 2 pasos: 
                    @if( Auth::user()->doubleAuth)
                        <div style="padding:20px;">
                            <span class="btn btn-success">Estado Activo</span>    
                        </div>
                        
                        <p>Inhabilitar doble autentificación:</p>
                        <form action="{{route('changeDoubleAuth',['id'=> Auth::user()->id,'code'=> 2])}}" method="post">
                                @csrf
                                <button class="btn btn-danger" type="submit"
                                onclick="confirm('¿Deseas deshabilitar la doble autentificación?')">
                                Inhabilitar
                            </button>
                        </form>
                    @else
                        <div>
                            <p style="color:red;">
                                No tienes habilitado esta opción 
                            </p>
                            
                            <form action="{{route('changeDoubleAuth',['id'=> Auth::user()->id,'code'=> 1])}}" method="post">
                                @csrf
                                <button class="btn btn-warning" type="submit"
                                onclick="confirm('¿Deseas habilitar la doble autentificación? **Se te remitira un código para tu ingreso')">
                                Habilitar
                            </button>
                            </form>
                            
                        </div>
                    @endif
                
                </div>
               
                
            </div>
        </div>
    </div>
</div>
@endsection
