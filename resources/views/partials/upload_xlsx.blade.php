@section('content')
@section('title', 'Dashboard GW | UP XLSX ' . strtoupper($type))
@section('content_header')
<div class="col-sm">
    <h4><i class="fa-solid fa-file-arrow-up"></i>
        @if ($type === 'production_data')
            UPLOAD - DADOS DE PRODUÇÃO
        @endif
        @if ($type === 'support_files')
            UPLOAD - ARQUIVOS DE APOIO
        @endif
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
<div class="card card-default">
    <div class="card-header">
        <h2 class="card-title"><i class="fa-solid fa-plus"></i> &nbsp;&nbsp;Adicionar Arquivo XLSX
        </h2><br>
    </div>
    <form id="fileUploadForm" action="{{ route('uploads.upload_xlsx.store', ['type' => $type]) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <input value="{{ $user = Auth::user()['name'] }}" name="users_name" type="text" hidden required>
        


        <input type="hidden" name="view_origem" value="{{ basename(request()->path()) }}">
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
            @if ($type === 'production_data')
                <div class="col-sm" hidden>
                    <x-adminlte-select2 name="sectors_sector" label="Setor" data-placeholder="selecione o setor...">
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-primary">
                                <i class="fa-solid fa-user-gear"></i>
                            </div>
                        </x-slot>
                        @foreach ($Sectors_filt as $sector)
                            <option selected>{{ $sector->name }}</option>
                        @endforeach
                    </x-adminlte-select2>
                </div>
            @endif
            @if ($type === 'support_files')
                <div class="col-sm">
                    <x-adminlte-select2 name="sectors_sector" label="Setor" data-placeholder="selecione o setor...">
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-primary">
                                <i class="fa-solid fa-user-gear"></i>
                            </div>
                        </x-slot>
                        <option disabled="disabled" selected></option>
                        @foreach ($Sectors_all as $sector)
                            <option disabled="disabled" selected></option>
                            <option>{{ $sector->name }}</option>
                        @endforeach
                    </x-adminlte-select2>
                </div>
            @endif
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
                    <p>* Administrador - use a botão <b>"Forçar Upload"</b> para novos arquivos não registrados na base de
                        dados<br>
                        <i class="fas fa-exclamation-triangle"></i>&nbsp;&nbsp;ESSA FUNÇÂO PODE GERAR DUPLICIDADE DE DADOS
                    </p>
                @endcan
                <p><b>* Arquivos Suportados:</b> .xlsx</p>
            </div>
        </div>
    </form>
</div>
@stop