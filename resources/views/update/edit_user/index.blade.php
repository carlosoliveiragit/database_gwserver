<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">

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
                    <input type="text" name="name" value="{{ $Users->name }}" class="form-control"
                        placeholder="Nome do novo Usuário" required>
                </div>
            </div>
            <div class="col-sm">
                <label>Senha</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-lock text-primary"></i></span>
                    </div>
                    <input type="password" name="password" class="form-control" placeholder="Senha Mínimo 8 Caracteres">
                </div>
            </div>
        </div>
        <div class="row p-2">
            <div class="col-sm">
                <x-adminlte-select2 name="profile_xid" label="Perfil de Usuário" data-placeholder="selecione o perfil...">
                    <x-slot name="prependSlot">
                        <div class="input-group-text text-primary">
                            <i class="fas fa-solid fa-sitemap"></i>
                        </div>
                    </x-slot>
                    @foreach ($Profiles as $profile)
                        <option value="{{ $profile->xid }}" {{ $profile->xid == $Users->profile_xid ? 'selected' : '' }}>
                            {{ $profile->name }}
                        </option>
                    @endforeach
                </x-adminlte-select2>
            </div>
            <div class="col-sm">
                <label>E-mail</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-envelope text-primary"></i>
                        </span>
                    </div>
                    <input type="email" name="email" value="{{ $Users->email }}" class="form-control"
                        placeholder="E-mail" required>
                </div>
            </div>
        </div>
        <div class="row p-2">
            <div class="col-sm">
                <x-adminlte-select2 name="sector_xid" label="Setor" data-placeholder="selecione o setor...">
                    <x-slot name="prependSlot">
                        <div class="input-group-text text-primary">
                            <i class="fas fa-solid fa-sitemap"></i>
                        </div>
                    </x-slot>
                    @foreach ($Sectors as $sector)
                        <option value="{{ $sector->xid }}" {{ $sector->xid == $Users->sector_xid ? 'selected' : '' }}>
                            {{ $sector->name }}
                        </option>
                    @endforeach
                </x-adminlte-select2>
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
@stop
