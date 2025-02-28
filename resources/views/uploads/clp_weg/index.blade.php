<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
@section('plugins.BsCustomFileInput', true)
@extends('adminlte::page')
@section('title', 'Dashboard GW | CLP Weg')
@section('plugins.Select2', true)

@section('content_header')
    <div class="col-sm">
        <h4><i class="fa-solid fa-gears"></i> &nbsp;&nbsp;UPLOAD - CLP WEG</h4>
    </div>
        <div class="row p-2">
            <div class="col-sm">
                @if (session('success'))
                <div class="row p-3">
                    <div class="col-sm">
                        <x-adminlte-alert theme="success" title="Operação Finalizada" dismissable>
                            <ul>
                                <li>{{ session('success') }}</li>
                            </ul>
                        </x-adminlte-alert>
                    </div>
                </div>
                @endif
                @if (session('error'))
                <div class="row p-3">
                    <div class="col-sm">
                        <x-adminlte-alert theme="danger" title="Erro na Operação" dismissable>
                            <ul>
                                <li>{{ session('error') }}</li>
                            </ul>
                        </x-adminlte-alert>
                    </div>
                </div>
                @endif
            </div>
        </div>
@stop
@section('content')
    <div class="card card-default">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-plus"></i> &nbsp;&nbsp;<b>Adicionar Arquivo dos seguintes Modelos:</b> PLC300 / CLIC02 / PLC500</h2><br>
        </div>
        <form id="fileUploadForm" action="clp_weg" method="POST" enctype="multipart/form-data">
            @csrf
            <input value="{{ $user = Auth::user()['name'] }}" name="users_name" type="text" hidden required>
            <div class="row p-2">
                <div class="col-sm">
                    <x-adminlte-select2 name="clients_client" label="1º - Cliente" data-placeholder="selecione o cliente..." required>
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
                    <x-adminlte-select2 name="systems_system" label="2º - Sistema" data-placeholder="selecione o sistema..." required>
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
                    <x-adminlte-select2 name="model" label="3º - Modelo" data-placeholder="selecione o modelo..." required>
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-primary">
                                <i class="fas fa-solid fa-tag"></i>
                            </div>
                        </x-slot>
                        <option disabled="disabled" selected></option>
                        <option value="PLC300">PLC 300</option>
                        <option value="CLIC02">CLIC 02</option>
                        <option value="PLC500">PLC 500</option>
                    </x-adminlte-select2>
                </div>
                <div class="col-sm">
                    <x-adminlte-select2 name="type_Ident" label="4º - Identificação" data-placeholder="selecione a identificação..." required>
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-primary">
                                <i class="fas fa-solid fa-file"></i>
                            </div>
                        </x-slot>
                        <option disabled="disabled" selected></option>
                        <option value="CLP1">CLP 1</option>
                        <option value="CLP2">CLP 2</option>
                        <option value="CLP3">CLP 3</option>
                        <option value="CLP4">CLP 4</option>
                    </x-adminlte-select2>
                </div>
            </div>
            <div class="row p-2">
                <div class="col-sm">
                    {{-- With label and feedback disabled --}}
                    <x-adminlte-input-file type="file" accept=".bkp, .cli, .projectarchive" id="upload" name="upload"
                        label="5º - Upload file" placeholder="selecione o arquivo correspondente ao modelo..." enable-feedback required>
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-primary">
                                <i class="fas fa-solid fa-upload"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input-file>
                </div>
                <div class="col-sm">
                    <label>6º - Ação</label>
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
                    <p><b>* Arquivos Suportados:</b> <b>PLC 300</b> .bkp / <b>CLIC02</b> .cli / <b>PLC500</b> .projectarchive</p>
                </div>
            </div>
        </form>
    </div>
@stop



@section('css')
@stop
@section('js')

@stop
