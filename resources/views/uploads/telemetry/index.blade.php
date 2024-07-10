<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
@section('plugins.BsCustomFileInput', true)
@extends('adminlte::page')
@section('title', 'Dashboard GW | Telemetria')
@section('plugins.Select2', true)

@section('content_header')
    <div class="row p-2">
        <div class="col-sm">
            <h2><i class="fas fa-solid fa-upload "></i> &nbsp;&nbsp;Upload Telemetria</h2>
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
            @if (isset($_GET['id']))
                <x-adminlte-card class="bg-warning" title=" Tem Certeza que Deseja Excluir o Arquivo?" theme="warning"
                    icon="fas fa-exclamation-triangle" removable>
                    <h5><strong>{{ $_GET['system'] }}</strong></h5>
                    <form action="systems/{{ $_GET['id'] }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fa fa-lg fa-fw fa-trash"></i>&nbsp;&nbsp;Deletar
                        </button>
                    </form>
                </x-adminlte-card>
            @endif
        </div>
    </div>
@stop
@section('content')
    <div class="card card-default">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-plus"></i> &nbsp;&nbsp;Adicionar Arquivo Json ScadaBR / NodeRed
            </h2><br>
        </div>
        <form id="fileUploadForm" action="telemetry" method="POST" enctype="multipart/form-data">
            @csrf
            <input value="{{ $user = Auth::user()['name'] }}" name="users_name" type="text" hidden required>
            <div class="row p-2">
                <div class="col-sm">
                    <x-adminlte-select2 name="clients_client" label="Cliente" data-placeholder="Select Client..." required>
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-primary">
                                <i class="fas fa-solid fa-water"></i>
                            </div>
                        </x-slot>
                        @foreach ($Clients as $index => $client)
                            <option disabled="disabled" selected></option>
                            <option>{{ $client->client }}</option>
                        @endforeach
                    </x-adminlte-select2>
                </div>
                <div class="col-sm">
                    <x-adminlte-select2 name="systems_system" label="Sistema" data-placeholder="Select System..." required>
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-primary">
                                <i class="fas fa-solid fa-sitemap"></i>
                            </div>
                        </x-slot>
                        @foreach ($Systems as $index => $system)
                            <option disabled="disabled" selected></option>
                            <option>{{ $system->system }}</option>
                        @endforeach
                    </x-adminlte-select2>
                </div>
            </div>
            <div class="row p-2">
                <div class="col-sm">
                    {{-- With label and feedback disabled --}}
                    <x-adminlte-input-file accept=".json" type="file" id="upload" name="upload" label="Upload file"
                        placeholder="Choose a file..." enable-feedback required>
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-primary">
                                <i class="fas fa-solid fa-upload"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input-file>
                </div>
                <div class="col-sm">
                    <x-adminlte-select2 name="type" label="Tipo" data-placeholder="Select Type..." required>
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-primary">
                                <i class="fas fa-solid fa-file"></i>
                            </div>
                        </x-slot>
                        <option disabled="disabled" selected></option>
                        <option value="SCADABR">ScadaBR</option>
                        <option value="NODERED">NodeRed</option>
                    </x-adminlte-select2>
                </div>
            </div>
            <div class="row p-2">
                <div class="col-sm">
                    {{-- Dinamic Change --}}
                    <x-adminlte-progress id="pbDinamic"  theme="lighblue" animated with-label />
                    {{-- Update the previous progress bar every 2 seconds, incrementing by 10% each step --}}
                    
                    <label>Ação</label>
                    <div class="input-group mb-3">
                        <button type="submit" class="btn btn-block btn-primary">
                            <span class="fas fa-plus"></span>&nbsp;&nbsp;Upload
                        </button>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row p-2">
                <div class="col-sm">
                    <p>* Preencha o formulário corretamente</p>
                    <p><b>* Arquivos Suportados:</b> <b>ScadaBR</b> .json / <b>NodeRed</b> .json</p>
                </div>
            </div>
        </form>
    </div>
@stop



@section('css')
@stop
@section('js')

@stop
