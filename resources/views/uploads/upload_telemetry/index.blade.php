<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
@section('plugins.BsCustomFileInput', true)
@extends('adminlte::page')
@section('title', 'Dashboard GW | UP TELEMETRIA')
@section('plugins.Select2', true)

@section('content_header')
<div class="col-sm">
    <h2><i class="fa-solid fa-scroll"></i> &nbsp;&nbsp;UPLOAD - TELEMETRIA</h2>
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
            <h2 class="card-title"><i class="fa-solid fa-plus"></i> &nbsp;&nbsp;Adicionar Arquivo JSON ScadaBR / NodeRed</h2>
        </div>

        <form id="fileUploadForm" action="upload_telemetry" method="POST" enctype="multipart/form-data">
            @csrf
            <input value="{{ Auth::user()->name }}" name="users_name" type="hidden" required>

            <div class="row p-2">
                <div class="col-sm">
                    <x-adminlte-select2 name="clients_client" label="Cliente" data-placeholder="Selecione o Cliente..." required>
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-primary">
                                <i class="fas fa-solid fa-water"></i>
                            </div>
                        </x-slot>
                        @foreach ($Clients as $client)
                            <option>{{ $client->client }}</option>
                        @endforeach
                    </x-adminlte-select2>
                </div>
                <div class="col-sm">
                    <x-adminlte-select2 name="systems_system" label="Sistema" data-placeholder="Selecione o Sistema..." required>
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-primary">
                                <i class="fas fa-solid fa-sitemap"></i>
                            </div>
                        </x-slot>
                        @foreach ($Systems as $system)
                            <option>{{ $system->system }}</option>
                        @endforeach
                    </x-adminlte-select2>
                </div>
            </div>
            <div class="row p-2">
                 <!-- Seletor para escolher Upload ou Colar JSON -->
                <div class="col-sm">
                    <label>Selecione o método de envio:</label>
                    <select id="uploadMethod" class="form-control">
                        <option value="file">Upload de Arquivo</option>
                        <option value="text">Colar JSON</option>
                    </select>
                </div>
                    <div class="col-sm">
                        <x-adminlte-select2 name="type" label="Tipo" data-placeholder="Selecione o Tipo..." required>
                            <x-slot name="prependSlot">
                                <div class="input-group-text text-primary">
                                    <i class="fas fa-solid fa-file"></i>
                                </div>
                            </x-slot>
                            <option value="SCADABR">ScadaBR</option>
                            <option value="NODERED">NodeRed</option>
                        </x-adminlte-select2>
                    </div>
            </div>
            <!-- Upload de Arquivo -->
            <div class="row p-2" id="fileUploadContainer">
                <div class="col-sm">
                    <x-adminlte-input-file accept=".json" type="file" id="upload" name="upload" label="Upload file" required
                        placeholder="Escolha um arquivo..." enable-feedback>
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-primary">
                                <i class="fas fa-solid fa-upload"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input-file>
                </div>
            </div>
            <!-- Colar JSON -->
            <div class="row p-2" id="textUploadContainer" style="display: none;">
                <div class="col-sm">
                    <label for="jsonText">Cole o JSON:</label>
                    <textarea class="form-control" name="json_text" id="jsonText" rows="6" placeholder="Cole seu JSON aqui..."></textarea>
                </div>
            </div>
            <div class="row p-2">
                <div class="col-sm">
                    <x-adminlte-progress id="pbDinamic" theme="lightblue" animated with-label />

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

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const uploadMethod = document.getElementById("uploadMethod");
        const fileUploadContainer = document.getElementById("fileUploadContainer");
        const textUploadContainer = document.getElementById("textUploadContainer");

        uploadMethod.addEventListener("change", function() {
            if (this.value === "file") {
                fileUploadContainer.style.display = "block";
                textUploadContainer.style.display = "none";
            } else {
                fileUploadContainer.style.display = "none";
                textUploadContainer.style.display = "block";
            }
        });
    });
</script>
@stop
