<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
@section('plugins.BsCustomFileInput', true)
@extends('adminlte::page')
@section('title', 'Dashboard GW | UP PDF')
@section('plugins.Select2', true)

@section('content_header')
<div class="col-sm">
    <h4><i class="fa-solid fa-file-pdf"></i> &nbsp;&nbsp;UPLOAD - PDF</h4>
</div>
<div class="row p-2">
    <div class="col-sm">
        @if(session('success'))
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
        @if(session('error'))
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
        <h2 class="card-title"><i class="fa-solid fa-plus"></i> &nbsp;&nbsp;<b>Adicionar Arquivo</b></h2>

    </div>
    <form id="fileUploadForm" action="upload_pdf" method="POST" enctype="multipart/form-data">
        @csrf
        <input value="{{ $user = Auth::user()['name'] }}" name="users_name" type="text" hidden required>
        <div class="row p-2">
            <div class="col-sm">
                <x-adminlte-select2 name="clients_client" label="1º - Cliente" data-placeholder="Select Client..."
                    required>
                    <x-slot name="prependSlot">
                        <div class="input-group-text text-primary">
                            <i class="fas fa-solid fa-water"></i>
                        </div>
                    </x-slot>
                    @foreach($Clients as $client)
                        <option disabled="disabled" selected></option>
                        <option>{{ $client->name }}</option>
                    @endforeach
                </x-adminlte-select2>
            </div>
            <div class="col-sm">
                <x-adminlte-select2 name="systems_system" label="2º - Sistema" data-placeholder="Select System..."
                    required>
                    <x-slot name="prependSlot">
                        <div class="input-group-text text-primary">
                            <i class="fas fa-solid fa-sitemap"></i>
                        </div>
                    </x-slot>
                    @foreach($Systems as $system)
                        <option disabled="disabled" selected></option>
                        <option>{{ $system->name }}</option>
                    @endforeach
                </x-adminlte-select2>
            </div>
        </div>
        <div class="row p-2">
            <div class="col-sm">
                <x-adminlte-select2 name="sectors_sector" label="3º - Setor" data-placeholder="selecione o setor..." required>
                    <x-slot name="prependSlot">
                        <div class="input-group-text text-primary">
                            <i class="fas fa-solid fa-sitemap"></i>
                        </div>
                    </x-slot>
                    @foreach($Sectors as $sector)
                        <option disabled="disabled" selected></option>
                        <option>{{ $sector->name }}</option>
                    @endforeach
                </x-adminlte-select2>
            </div>
            <div class="col-sm">
                {{-- With label and feedback disabled --}}
                <x-adminlte-input-file type="file" accept=".pdf" id="upload" name="uploadPdf[]" label="4º - Upload file"
                    multiple placeholder="Choose a file..." enable-feedback required>
                    <x-slot name="prependSlot">
                        <div class="input-group-text text-primary">
                            <i class="fas fa-solid fa-upload"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input-file>
            </div>
        </div>

        <div class="row p-2">
            <div class="col-sm">
                <label>5º - Ação</label>
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
                <p><b>* Arquivos Suportados:</b> .cxob</p>
            </div>
        </div>
    </form>
</div>
@stop

@section('css')
@stop
@section('js')
@stop