@extends('adminlte::page')
@section('title', 'Dashboard GW | Edit Users')
@section('content_header')
    <div class="row">
        <div class="col-sm">
            <h4 class=""><i class="nav-icon fas fa-user "></i> &nbsp;&nbsp;Atualização de Dados</h4>
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
            <h2 class="card-title"><i class="fa-solid fa-user"></i> &nbsp;&nbsp;Editando Usuário: {{ $Users->name }}</h2>
        </div>
        <form action="{{ $Users->id }}" method="post">
            @csrf
            @method('PUT') 
            <div class="row p-2">
                <div class="col-sm">
                    <label>Nome</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-user text-primary"></i>
                            </span>
                        </div>
                        <input type="text" name="name" value="{{ $Users->name }}" class="form-control" placeholder="Nome do novo Usuário"
                            required>
                    </div>
                </div>
                <div class="col-sm">
                    <label>Tema</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-moon text-primary"></i></span>
                        </div>
                        <select class="form-control" name="admin_lte_dark_mode">
                            <option value="0">Dark Mode OFF</option>
                            <option value="1">Dark Mode ON</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row p-2">
                <div class="col-sm">
                    <label>Senha</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-lock text-primary"></i></span>
                        </div>
                        <input type="password" name="password" class="form-control" placeholder="Senha Minimo 8 Caracteres">
                    </div>
                </div>
                <div class="col-sm">
                    <label>Perfil</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-screwdriver-wrench text-primary"></i></span>
                        </div>
                        <select class="form-control" name="profile">
                            <option value="user">Usuário</option>
                            <option value="administrator">Administrador</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row p-2">
                <div class="col-sm">
                    <label>E-mail</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-envelope text-primary"></i>
                            </span>
                        </div>
                        <input type="email" name="email" value="{{ $Users->email }}" class="form-control" placeholder="E-mail" required>
                    </div>
                </div>
                <div class="col-sm">
                    <label>Ação</label>
                    <div class="input-group mb-3">
                        <button type="submit" class="btn btn-block btn-primary">
                            <span class="fas fa-user-plus"></span>&nbsp;&nbsp;Atualizar
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

@section('js')
    
@stop
