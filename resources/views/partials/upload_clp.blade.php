@section('content')
@section('title', 'Dashboard GW | UP CLP ' . strtoupper($model))
@section('content_header')
<div class="col-sm">
    <h4><i class="fa-solid fa-file-arrow-up"></i> &nbsp;&nbsp;UPLOAD - CLP - {{ strtoupper($model) }}</h4>
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
        <h2 class="card-title"><i class="fa-solid fa-plus"></i>
            @if ($model === 'abb')
                    <b>Adicionar Arquivo dos seguintes Modelos:&nbsp;&nbsp;</b>PM5032-T-ETH / PM5052-T-ETH
                </h2><br>
            @endif
        @if ($model === 'altus')
            <b>Adicionar Arquivo dos seguintes Modelos:&nbsp;&nbsp;</b>XP315 / XP325 / XP340</h2><br>
        @endif
        @if ($model === 'weg')
            <b>Adicionar Arquivo dos seguintes Modelos:&nbsp;&nbsp;</b>CLIC02 / PLC300 / PLC500</h2><br>
        @endif
    </div>
    <form id="fileUploadForm" action="{{ route('uploads.upload_clp.store', ['model' => $model]) }}" method="POST"
        enctype="multipart/form-data">
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
                    @foreach ($Clients as $client)
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
                    @foreach ($Systems as $system)
                        <option disabled="disabled" selected></option>
                        <option>{{ $system->name }}</option>
                    @endforeach
                </x-adminlte-select2>
            </div>
        </div>
        <div class="row p-2">
            <div class="col-sm">
                <x-adminlte-select2 name="models_model" label="3º - Modelo" data-placeholder="Select Model..." required>
                    <x-slot name="prependSlot">
                        <div class="input-group-text text-primary">
                            <i class="fas fa-solid fa-tag"></i>
                        </div>
                    </x-slot>
                    @if ($model === 'abb')
                        <option disabled="disabled" selected></option>
                        <option value="PM5052-T-ETH">PM5052-T-ETH</option>
                        <option value="PM5032-T-ETH">PM5032-T-ETH</option>
                    @endif
                    @if ($model === 'altus')
                        <option disabled="disabled" selected></option>
                        <option value="XP315">XP 315</option>
                        <option value="XP325">XP 325</option>
                        <option value="XP340">XP 340</option>
                    @endif
                    @if ($model === 'weg')
                        <option disabled="disabled" selected></option>
                        <option value="CLIC02">CLIC 02</option>
                        <option value="PLC300">PLC 300</option>
                        <option value="PLC500">PLC 500</option>
                    @endif
                </x-adminlte-select2>
            </div>
            <div class="col-sm">
                <x-adminlte-select2 name="models_ident" label="4º - Identificação" data-placeholder="Select Type..." required>
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

        <div class="row p-2">
            <div class="col-sm">
                {{-- With label and feedback disabled --}}
                <x-adminlte-input-file type="file" accept=".cli,.bkp,.projectarchive" id="upload" name="upload"
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
        <div class="row p-2">
            <div class="col-sm">
                <p>* Preencha o formulário corretamente</p>
                @if ($model === 'abb' || $model === 'altus')
                    <p><b>* Arquivos Suportados:&nbsp;&nbsp;</b>.projectarchive</p>
                @endif
                @if ($model === 'weg')
                    <p><b>* Arquivos Suportados:&nbsp;&nbsp;</b>.cli .bkp .projectarchive</p>
                @endif

            </div>
        </div>
    </form>
</div>
@stop