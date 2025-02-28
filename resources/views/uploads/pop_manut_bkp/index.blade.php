<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
@section('plugins.BsCustomFileInput', true)
@extends('adminlte::page')
@section('title', 'Dashboard GW | Imagens')
@section('plugins.Select2', true)

@section('content_header')
<div class="col-sm">
    <h4><i class="fa-solid fa-file-arrow-up"></i> &nbsp;&nbsp;UPLOAD - PROCEDIMENTO OPERACIONAL PADRÃO - MANUTENÇÃO</h4>
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
        </div>
    </div>
@stop
@section('content')
    <div class="card card-default">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-plus"></i> &nbsp;&nbsp;Adicionar Arquivo PDF
            </h2><br>
        </div>
        <form id="fileUploadForm" action="pop_manut_bkp" method="POST" enctype="multipart/form-data">
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
                    <x-adminlte-input-file accept=".pdf" multiple id="upload" name="upload[]" label="Upload file"
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
                        <option value="POP" selected></option>
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
                    <p><b>* Arquivos Suportados:</b> .pdf</p>
                </div>
            </div>
        </form>
    </div>
@stop



@section('css')
@stop
@section('js')

@stop
