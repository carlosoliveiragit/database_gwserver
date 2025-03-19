@section('content')
@section('title', 'Dashboard GW | UP POP ' . strtoupper($sector))
@section('content_header')
<div class="col-sm">
    <h4><i class="fa-solid fa-file-arrow-up"></i>
        @if ($sector === 'manutencao')
            UPLOAD - PROCEDIMENTO OPERACIONAL PADRÃO -&nbsp;&nbsp;MANUTENÇÃO
        @endif
        @if ($sector === 'cco')
            UPLOAD - PROCEDIMENTO OPERACIONAL PADRÃO -&nbsp;&nbsp;CCO
        @endif
        @if ($sector === 'operacao')
            UPLOAD - PROCEDIMENTO OPERACIONAL PADRÃO -&nbsp;&nbsp;OPERAÇÃO
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
        <h2 class="card-title"><i class="fa-solid fa-plus"></i> &nbsp;&nbsp;Adicionar Arquivo PDF
        </h2><br>
    </div>
    <form id="fileUploadForm" action="{{ route('uploads.upload_pop.store', ['sector' => $sector]) }}" method="POST"
        enctype="multipart/form-data">
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
                        <option value="{{ $client->client }}">{{ $client->client }}</option>
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
                        <option value="{{ $system->system }}">{{ $system->system }}</option>
                    @endforeach
                </x-adminlte-select2>
            </div>
        </div>
        <div class="row p-2">
            <div class="col-sm">
                <x-adminlte-input-file accept=".pdf" multiple id="upload" name="upload[]" label="Upload file"
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
        </div>
        <div class="row p-2">
            <div class="col-sm">
                <p>* Preencha o formulário corretamente</p>
                <p><b>* Arquivos Suportados:</b> .pdf</p>
            </div>
        </div>
    </form>
</div>
@stop