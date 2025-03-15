<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
@section('plugins.BsCustomFileInput', true)
@extends('adminlte::page')
@section('title', 'Dashboard GW | CLP ABB')
@section('plugins.Select2', true)

@section('content_header')
<div class="col-sm">
    <h4><i class="fa-solid fa-gears"></i> &nbsp;&nbsp;UPLOAD - CLP ABB</h4>
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
        <h2 class="card-title"><i class="fa-solid fa-plus"></i> &nbsp;&nbsp;<b>Adicionar Arquivo dos seguintes Modelos:
            </b>PM5032-T-ETH / PM5052-T-ETH</h2><br>
    </div>
    <form id="fileUploadForm" action="clp_abb" method="POST" enctype="multipart/form-data">
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
                    @foreach ($Clients as $index => $client)
                        <option disabled="disabled" selected></option>
                        <option>{{ $client->client }}</option>
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
                    @foreach ($Systems as $index => $system)
                        <option disabled="disabled" selected></option>
                        <option>{{ $system->system }}</option>
                    @endforeach
                </x-adminlte-select2>
            </div>
        </div>
        <div class="row p-2">
            <div class="col-sm">
                <x-adminlte-select2 name="model" label="3º - Modelo" data-placeholder="Select Model..." required>
                    <x-slot name="prependSlot">
                        <div class="input-group-text text-primary">
                            <i class="fas fa-solid fa-tag"></i>
                        </div>
                    </x-slot>
                    <option disabled="disabled" selected></option>
                    <option value="PM5052-T-ETH">PM5052-T-ETH</option>
                    <option value="PM5032-T-ETH">PM5032-T-ETH</option>

                </x-adminlte-select2>
            </div>
            <div class="col-sm">
                <x-adminlte-select2 name="type" label="4º - Identificação" data-placeholder="Select Type..." required>
                    <x-slot name="prependSlot">
                        <div class="input-group-text text-primary">
                            <i class="fas fa-solid fa-file"></i>
                        </div>
                    </x-slot>
                    <option disabled="disabled" selected></option>
                    <option value="CLP1">CLP 1</option>
                    <option value="CLP2">CLP 2</option>
                    <option value="CLP3">CLP 3</option>
                    <option value="CLP3">CLP 4</option>
                </x-adminlte-select2>
            </div>
        </div>
        <div class="row-p2">
            <div class="col">
                <div class="progress">
                    <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0"
                        aria-valuemin="0" aria-valuemax="100">0%</div>
                </div>
            </div>
        </div>
        <div class="row p-2">
            <div class="col-sm">
                {{-- With label and feedback disabled --}}
                <x-adminlte-input-file type="file" accept=".projectarchive" id="upload" name="upload"
                    label="5º - Escolha o Arquivo" placeholder="Choose a file..." enable-feedback required>
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
                <p><b>* Arquivos Suportados:</b> .projectarchive</p>
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
    document.getElementById('fileUploadForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Impede o envio padrão do formulário

        var form = event.target;
        var formData = new FormData(form);
        var xhr = new XMLHttpRequest();

        xhr.upload.addEventListener('progress', function (event) {
            if (event.lengthComputable) {
                var percentComplete = (event.loaded / event.total) * 100;
                var progressBar = document.getElementById('progressBar');
                progressBar.style.width = percentComplete + '%';
                progressBar.setAttribute('aria-valuenow', percentComplete);
                progressBar.textContent = Math.round(percentComplete) + '%';
            }
        });

        xhr.addEventListener('load', function () {
            form.submit(); // Envia o formulário após o upload ser concluído
        });

        xhr.open('POST', form.action, true);
        xhr.send(formData);
    });
</script>
@stop