<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
@section('plugins.BsCustomFileInput', true)
@extends('adminlte::page')
@section('title', 'Dashboard GW | Pesquisa')
@section('content_header')
<div class="col-sm">
    <h4><i class="nav-icon fas fa-fw fa-search"></i> &nbsp;&nbsp;Pesquisa de Procedimentos</h4>
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
                <form action="pop_clients_files/{{ $_GET['id'] }}" method="POST">
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
                    @foreach ($Clients as $index => $client)
                        <option disabled="disabled" selected></option>
                        <option>{{ $client->client }}</option>
                    @endforeach
                </x-adminlte-select2>
            </div>
            <div class="col-sm-3">
                <x-adminlte-select2 name="systems_system" label="Sistema" data-placeholder="selecione o sistema...">
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
            <div class="col-sm-3">
                <x-adminlte-select2 name="sectors_sector" label="Setor" data-placeholder="selecione o setor...">
                    <x-slot name="prependSlot">
                        <div class="input-group-text text-primary">
                            <i class="fa-solid fa-user-gear"></i>
                        </div>
                    </x-slot>
                    <option disabled="disabled" selected></option>
                    <option value="MANUTENCAO">MANUTENÇÃO</option>
                    <option value="OPERACAO">OPERAÇÃO</option>
                    <option value="CCO">CCO</option>
                </x-adminlte-select2>
            </div>

        </div>
        <div class="row p-2">
            <div class="col-sm">
                <div class="input-group mb-3">
                    <button type="submit" class="btn btn-block bg-gradient-info"><i
                            class="fa-solid fa-magnifying-glass-plus"></i>&nbsp;&nbsp;&nbsp;&nbsp;Pesquisar</button>
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
                                        <th>Cliente</th>
                                        <th>Sistema</th>
                                        <th>Setor</th>
                                        <th>Procedimento</th>
                                        <th>Data</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Files as $key => $return_db)
                                        <tr class="border border-secondary">
                                            <td>
                                                {{ $return_db->id }}
                                            </td>
                                            <td title="{{ $return_db->clients_client }}">
                                                {{ Str::limit($return_db->clients_client, 30) }}
                                            </td>

                                            <td>
                                                {{ $return_db->systems_system }}
                                            </td>
                                            <td>
                                                {{ $return_db->sector }}
                                            </td>
                                            <td title="{{ $return_db->file }}">
                                                {{ Str::limit($return_db->file, 30) }}
                                            </td>
                                            <td>
                                                {{ $return_db->created_at->format('d/m/Y H:i:s') }}
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a class="btn btn-success btn-lg px-2 py-1"
                                                        href="{{ route('pop_clients_files.download', ['file' => $return_db->file]) }}"
                                                        title="Baixar Arquivo">
                                                        <i class="fa fa fa-fw fa-download"></i>
                                                    </a>
                                                </div>
                                                @if (pathinfo($return_db->file, PATHINFO_EXTENSION) === 'pdf')
                                                    <div class="btn-group">
                                                        <a class="btn btn-primary btn-lg px-2 py-1"
                                                            href="{{ route('showpdf.view', ['id' => $return_db->id]) }}"
                                                            title="Visualizar Arquivo">
                                                            <i class="fa fa fa-fw fa-eye"></i>
                                                        </a>
                                                    </div>
                                                @endif
                                                @can('is_admin')
                                                    <div class="btn-group">
                                                        <a type="submit" class="btn btn-danger btn-lg px-2 py-1"
                                                            href="pop_clients_files?id={{ $return_db->id }}&path={{ $return_db->path }}&file={{ $return_db->file }}"
                                                            title="Excluir Arquivo">
                                                            <i class="fa fa fa-fw fa-trash"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
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