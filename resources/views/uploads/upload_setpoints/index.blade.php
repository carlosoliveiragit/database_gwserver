<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
@section('plugins.BsCustomFileInput', true)
@extends('adminlte::page')
@section('title', 'Dashboard GW | UP SETPOINTS')
@section('plugins.Select2', true)

@section('content_header')
<div class="col-sm">
    <h4><i class="fa-solid fa-image"></i> &nbsp;&nbsp;UPLOAD - SETPOINTS</h4>
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
        <h2 class="card-title"><i class="fa-solid fa-plus"></i> &nbsp;&nbsp;Adicionar Arquivos de Imagem
        </h2><br>
    </div>
    <form id="fileUploadForm" action="upload_setpoints" method="POST" enctype="multipart/form-data">
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
                    @foreach ($Clients as $client)
                        <option disabled="disabled" selected></option>
                        <option>{{ $client->name }}</option>
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
                    @foreach ($Systems as $system)
                        <option disabled="disabled" selected></option>
                        <option>{{ $system->name }}</option>
                    @endforeach
                </x-adminlte-select2>
            </div>
        </div>
        <div class="row p-2">
            <div class="col-sm">
                <label>Selecione o tipo de arquivo:</label>
                <select id="fileType" class="form-control">
                    <option value="image">Imagem (JPG, PNG)</option>
                    <option value="pdf">Documento PDF</option>
                </select>
            </div>
            <div class="col-sm" id="fileUploadContainer">
                <x-adminlte-input-file accept=".jpg,.png" type="file" id="upload" name="upload[]" label="Upload Imagem"
                    multiple placeholder="Escolha um arquivo..." enable-feedback>
                    <x-slot name="prependSlot">
                        <div class="input-group-text text-primary">
                            <i class="fas fa-solid fa-upload"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input-file>
            </div>
            <div class="col-sm" id="pdfUploadContainer" style="display: none;">
                <x-adminlte-input-file accept=".pdf" type="file" id="uploadPdf" name="uploadPdf[]" label="Upload PDF" multiple
                    placeholder="Escolha um arquivo..." enable-feedback>
                    <x-slot name="prependSlot">
                        <div class="input-group-text text-primary">
                            <i class="fas fa-solid fa-file-pdf"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input-file>
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
        </div>
        <hr>
        <div class="row p-2">
            <div class="col-sm">
                <p>* Preencha o formulário corretamente</p>
                <p><b>* Arquivos Suportados:</b> .png .jpg</p>
            </div>
        </div>
    </form>
</div>
@stop



@section('css')
<style>
    .progress {
        border-radius: 10px;
        /* Arredonda os cantos do contêiner da barra de progresso */
    }

    .progress-bar {
        border-radius: 10px;
        /* Arredonda os cantos da barra de progresso */
    }
</style>
@stop
@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const fileType = document.getElementById("fileType");
        const fileUploadContainer = document.getElementById("fileUploadContainer");
        const pdfUploadContainer = document.getElementById("pdfUploadContainer");

        fileType.addEventListener("change", function () {
            if (this.value === "image") {
                fileUploadContainer.style.display = "block";
                pdfUploadContainer.style.display = "none";
            } else {
                fileUploadContainer.style.display = "none";
                pdfUploadContainer.style.display = "block";
            }
        });
    });
</script>

@stop