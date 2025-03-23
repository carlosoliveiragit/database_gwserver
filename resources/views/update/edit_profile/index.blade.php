@extends('adminlte::page')
@section('title', 'Dashboard GW | Edit Sectors')
@section('content_header')
    <div class="row p-2">
        <div class="col-sm">
            <h4><i class="fa-solid fa-address-card"></i> &nbsp;&nbsp;Atualização de Dados</h4>
        </div>
        <div class="col-sm">
            @if (session('success'))
                <x-adminlte-card title=" {{ session('success') }}" theme="success" icon="fas fa-lg fa-thumbs-up" removable>
                </x-adminlte-card>
            @endif
            @if (session('error'))
                <x-adminlte-card title=" {{ session('error') }}" theme="danger" icon="fas fa-lg fa-thumbs-down" removable>
                </x-adminlte-card>
            @endif
        </div>
    </div>
@stop

@section('content')

    <div class="card card-default">
        <div class="card-header">
            <h2 class="card-title">
                &nbsp;&nbsp;Editando o Perfil de Usuário: {{$Profiles->name}} </h2>
        </div>
        <p></p>
        <form action="{{ $Profiles->id }}" method="POST">
            @csrf
            @method('PUT') 
            <div class="class row p-2">
                <div class="col-sm">
                    <div class="form-group">
                        <input type="text" class="form-control" id="profile" name="name"
                            placeholder="Nome do Novo Perfil" value="{{$Profiles->name}}" required>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="input-group mb-3">
                        <button type="submit" class="btn btn-block btn-primary">
                            </i>&nbsp;&nbsp;<span
                                class="fa-solid fa-vector-square"></span>&nbsp;&nbsp;Atualizar
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>
    <hr>
@stop

@section('css')
@stop
@section('js')
    
@stop
