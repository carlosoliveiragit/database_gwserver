<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">
@section('plugins.BsCustomFileInput', true)
@extends('adminlte::page')
@section('title', 'Dashboard GW | UP XLSX DP')
@section('plugins.Select2', true)

@section('content_header')
<div class="col-sm">
    <h4><i class="fa-solid fa-file-arrow-up"></i> &nbsp;&nbsp;UPLOAD - Dados de Produção</h4>
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
        <h2 class="card-title"><i class="fa-solid fa-plus"></i> &nbsp;&nbsp;Adicionar Arquivo XLSX
        </h2><br>
    </div>
    <form id="fileUploadForm" action="upload_xlsx_dp" method="POST" enctype="multipart/form-data">
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
                <x-adminlte-input-file accept=".xlsx" multiple id="upload" name="upload[]" label="Upload file"
                    placeholder="Choose files..." enable-feedback required>
                    <x-slot name="prependSlot">
                        <div class="input-group-text text-primary">
                            <i class="fas fa-solid fa-upload"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input-file>
            </div>
            <div class="col-sm" hidden>
                <x-adminlte-select2 name="type" label="Tipo" data-placeholder="Select Type..." required>
                    <x-slot name="prependSlot">
                        <div class="input-group-text text-primary">
                            <i class="fas fa-solid fa-file"></i>
                        </div>
                    </x-slot>
                    <option value="DADOS DE PRODUCAO" selected></option>
                </x-adminlte-select2>
            </div>
        </div>
        <div class="row p-2">
            <div class="col-sm">
                <label>Ação</label>
                <div class="input-group mb-3">
                    <button type="submit" class="btn btn-block btn-primary">
                        <span class="fas fa-plus"></span>&nbsp;&nbsp;Upload
                    </button>
                </div>
            </div>
            @can('is_admin')
                <div class="col-sm">
                    <label>Administrador</label>
                    <div class="input-group mb-3">
                        <button @cannot('is_admin') disabled @endcannot type="submit" name="force_upload" value="true"
                            class="btn btn-block btn-warning">
                            <span class="fas fa-exclamation-triangle"></span>&nbsp;&nbsp;Forçar Upload
                        </button>
                    </div>
                </div>
            @endcan
        </div>

        <div class="row p-2">
            <div class="col-sm">
                <p>* Preencha o formulário corretamente</p>
                <p>* Para upload de novos arquivos, entre em contato com um administrador do sistema</p>
                @can('is_admin')
                    <p>* Administrador - use a botão <b>"Forçar Upload"</b> para novos arquivos não registrados na base de dados<br>
                    <i class="fas fa-exclamation-triangle"></i>&nbsp;&nbsp;ESSA FUNÇÂO PODE GERAR DUPLICIDADE DE DADOS
                    </p>
                @endcan
                <p><b>* Arquivos Suportados:</b> .xlsx</p>
            </div>
        </div>
    </form>
</div>
@stop

@section('css')
@stop

@section('js')
@stop