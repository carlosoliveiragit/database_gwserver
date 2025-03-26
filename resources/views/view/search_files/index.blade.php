<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
@section('plugins.BsCustomFileInput', true)
@extends('adminlte::page')
@section('title', 'Dashboard GW | Pesquisa')
@section('content_header')
<div class="col-sm">
    <h4><i class="nav-icon fas fa-fw fa-search"></i> &nbsp;&nbsp;Pesquisa de Arquivos</h4>
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
        @if (isset($_GET['id']))
            <x-adminlte-card class="bg-warning" title=" Tem Certeza que Deseja Excluir o Arquivo?" theme="warning"
                icon="fas fa-exclamation-triangle" removable>
                <h6>Dir:<strong>{{ $_GET['path'] }}</strong></h6><br>
                <h6>File:<strong>{{ $_GET['file'] }}</strong></h6>
                <form action="search_files/{{ $_GET['id'] }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input value="{{ $_GET['path'] }}" name="path" type="text" hidden required>
                    <input value="{{ $_GET['file'] }}" name="file" type="text" hidden required>
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fa fa-lg fa-fw fa-trash"></i>&nbsp;&nbsp;Deletar
                    </button>
                </form>
            </x-adminlte-card>
        @endif
    </div>
</div>
@stop
@section('content')

<div class="card card-default">
    <form class="form-group" action="" method="GET">
        <div class="row p-2">
            <div class="col-sm-6">
                <x-adminlte-select2 name="clients_client" label="Cliente" data-placeholder="selecione o cliente...">
                    <x-slot name="prependSlot">
                        <div class="input-group-text text-primary">
                            <i class="fas fa-solid fa-water"></i>
                        </div>
                    </x-slot>
                    <option disabled selected></option>
                    @foreach ($Clients as $client)
                        <option value="{{ $client->xid }}" {{ request('clients_client') == $client->xid ? 'selected' : '' }}>
                            {{ $client->name }}
                        </option>
                    @endforeach
                </x-adminlte-select2>
            </div>
            <div class="col-sm-6">
                <x-adminlte-select2 name="systems_system" label="Sistema" data-placeholder="selecione o sistema...">
                    <x-slot name="prependSlot">
                        <div class="input-group-text text-primary">
                            <i class="fas fa-solid fa-sitemap"></i>
                        </div>
                    </x-slot>
                    <option disabled selected></option>
                    @foreach ($Systems as $system)
                        <option value="{{ $system->xid }}" {{ request('systems_system') == $system->xid ? 'selected' : '' }}>
                            {{ $system->name }}
                        </option>
                    @endforeach
                </x-adminlte-select2>
            </div>
        </div>

        <div class="row p-2">
            <div class="col-sm-6">
                <x-adminlte-select2 name="types_type" label="Tipo" data-placeholder="selecione o Tipo...">
                    <x-slot name="prependSlot">
                        <div class="input-group-text text-primary">
                            <i class="fas fa-solid fa-sitemap"></i>
                        </div>
                    </x-slot>
                    <option disabled selected></option>
                    @foreach ($Types as $type)
                        <option value="{{ $type->xid }}" {{ request('types_type') == $type->xid ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </x-adminlte-select2>
            </div>
            <div class="col-sm-6">
                <x-adminlte-select2 name="sectors_sector" label="Setor" data-placeholder="selecione o setor...">
                    <x-slot name="prependSlot">
                        <div class="input-group-text text-primary">
                            <i class="fas fa-solid fa-sitemap"></i>
                        </div>
                    </x-slot>
                    <option disabled selected></option>
                    @foreach($Sectors as $sector)
                        <option value="{{ $sector->xid }}" {{ request('sectors_sector') == $sector->xid ? 'selected' : '' }}>
                            {{ $sector->name }}
                        </option>
                    @endforeach
                </x-adminlte-select2>
            </div>
        </div>
        <div class="row p-2">
            <div class="col-sm">
                <div class="input-group mb-3">
                    <button type="submit" class="btn btn-block bg-gradient-info">
                        <i class="fa-solid fa-magnifying-glass-plus"></i>&nbsp;&nbsp;&nbsp;&nbsp;Pesquisar
                    </button>
                </div>
            </div>
            <div class="col-sm">
                <div class="input-group mb-3">
                    <a href="{{ route('search_files.index') }}" class="btn btn-block bg-gradient-secondary">
                        <i class="fas fa-undo"></i>&nbsp;&nbsp;&nbsp;&nbsp;Limpar Seleção
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="card card-default">
    <div class="row p-1">
        <div class="col-12">
            <div class="tab-content" id="custom-tabs-one-tabContent">
                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                    aria-labelledby="custom-tabs-one-home-tab">
                    <div class="row">
                        <div class="col-md-12">
                            <table id="table1" class="table table-sm table-bordered table-hover" style="width: 100%;">
                                <thead>
                                    <tr class="text-secondary">
                                        <th>Id</th>
                                        <th>Usuário</th>
                                        <th>Cliente</th>
                                        <th>Arquivo</th>
                                        <th>Sistema</th>
                                        <th>Setor</th>
                                        <th>Tipo</th>
                                        <th>Data</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($Files as $file)
                                        <tr class="border border-secondary">
                                            <td>{{ $file->id }}</td>
                                            <td title="{{ $file->user->name ?? 'N/A' }}">
                                                {{ Str::limit($file->user->name ?? 'N/A', 30) }}
                                            </td>
                                            <td title="{{ $file->client->name ?? 'N/A' }}">
                                                {{ Str::limit($file->client->name ?? 'N/A', 20) }}
                                            </td>
                                            <td title="{{ $file->file }}">
                                                {{ Str::limit($file->file, 20) }}
                                            </td>
                                            <td>{{ $file->system->name ?? 'N/A' }}</td>
                                            <td title="{{ $file->sector->name ?? 'N/A' }}">
                                                {{ Str::limit($file->sector->name ?? 'N/A', 30) }}
                                            </td>
                                            <td title="{{ $file->type->name ?? 'N/A' }}">
                                                {{ Str::limit($file->type->name ?? 'N/A', 10) }}
                                            </td>
                                            <td>{{ $file->updated_at->format('d/m/Y - H:i:s') }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a class="btn btn-success btn-lg px-2 py-1"
                                                        href="{{ route('search_files.download', ['file' => $file->file]) }}"
                                                        title="Baixar Arquivo">
                                                        <i class="fa fa-fw fa-download"></i>
                                                    </a>
                                                </div>
                                                @if (pathinfo($file->file, PATHINFO_EXTENSION) === 'pdf')
                                                    <div class="btn-group">
                                                        <a class="btn btn-primary btn-lg px-2 py-1"
                                                            href="{{ route('showpdf.view', ['id' => $file->id]) }}"
                                                            title="Visualizar Arquivo">
                                                            <i class="fa fa fa-fw fa-eye"></i>
                                                        </a>
                                                    </div>
                                                @elseif (pathinfo($file->file, PATHINFO_EXTENSION) === 'json')
                                                    <div class="btn-group">
                                                        <a class="btn btn-primary btn-lg px-2 py-1"
                                                            href="{{ route('showjson.view', ['id' => $file->id]) }}"
                                                            title="Visualizar Arquivo">
                                                            <i class="fa fa fa-fw fa-eye"></i>
                                                        </a>
                                                    </div>
                                                @endif
                                                @if (pathinfo($file->file, PATHINFO_EXTENSION) === 'xlsx' || pathinfo($file->file, PATHINFO_EXTENSION) === 'xls')
                                                    <div class="btn-group">
                                                        <a class="btn btn-primary btn-lg px-2 py-1"
                                                            href="{{ route('showexcel.view', ['id' => $file->id]) }}"
                                                            title="Visualizar Arquivo">
                                                            <i class="fa fa fa-fw fa-eye"></i>
                                                        </a>
                                                    </div>
                                                @endif
                                                @can('is_admin')
                                                    <div class="btn-group">
                                                        <a type="submit" class="btn btn-danger btn-lg px-2 py-1"
                                                            href="search_files?id={{ $file->id }}&path={{ $file->path }}&file={{ $file->file }}"
                                                            title="Excluir Arquivo">
                                                            <i class="fa fa fa-fw fa-trash"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted">Nenhum arquivo encontrado.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@stop
@section('js')

<script>
    $(document).ready(function () {
        $('#table1').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true, // Habilita a ordenação
            "order": [
                [0, "desc"]
            ], // Define a primeira coluna (índice 0) em ordem decrescente
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "Nada encontrado",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "Nenhum registro disponível",
                "infoFiltered": "(filtrado de _MAX_ registros no total)",
                "search": "Buscar:",
                "paginate": {
                    "first": "Primeiro",
                    "last": "Último",
                    "next": "Próximo",
                    "previous": "Anterior"
                }
            }
        });
    });
</script>

@stop