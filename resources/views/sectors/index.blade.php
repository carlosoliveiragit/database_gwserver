@extends('adminlte::page')
@section('title', 'Dashboard | Sectors')
@section('content_header')
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
                <x-adminlte-card class="bg-warning" title=" Tem Certeza que Deseja Excluir o Setor?" theme="warning"
                    icon="fas fa-exclamation-triangle" removable>
                    <h5><strong>{{ $_GET['system'] }}</strong></h5>
                    <form action="sectors/{{ $_GET['id'] }}" method="POST">
                        @csrf
                        @method('DELETE')
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
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp;<i class="fa-solid fa-vector-square "></i>
                &nbsp;&nbsp;Adicionar Setor</h2>
        </div>
        <p></p>
        <form action="sectors" method="POST">
            @csrf
            <div class="class row p-2">
                <div class="col-sm">
                    <div class="form-group">
                        <input type="text" class="form-control" id="system" name="sector"
                            placeholder="Nome do Novo Setor" required>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="input-group mb-3">
                        <button type="submit" class="btn btn-block btn-primary">
                            <i class="fa-solid fa-plus"></i>&nbsp;&nbsp;<span
                                class="fa-solid fa-vector-square"></span>&nbsp;&nbsp;Cadastrar Setor
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>
    <div class="card card-default">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-vector-square"></i> &nbsp;&nbsp;Setores Cadastrados</h2>
        </div>
        <div class="row p-2">
            <div class="col-12 col-sm-12">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                    <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                        aria-labelledby="custom-tabs-one-home-tab">
                        <div class="row p-2">
                            <div class="col-md-12">
                                <table id="table1" class="table table-sm table-bordered table-hover"
                                    style="width: 100%;">
                                    <thead>
                                        <tr class="text-secondary">
                                            <th>Id</th>
                                            <th>Setor</th>
                                            @can('is_admin')
                                                <th>Ação</th>
                                            @endcan
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($Sectors as $sector)
                                            <tr class="border border-secondary">
                                                <td>
                                                    {{ $sector->id }}
                                                </td>
                                                <td>
                                                    {{ $sector->name }}
                                                </td>
                                                @can('is_admin')
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <a type="submit" class="btn btn-danger btn-sm"
                                                                href="sectors?id={{ $sector->id }}&system={{ $sector->name }}">
                                                                <i class="fa fa-lg fa-fw fa-trash"></i>
                                                            </a>
                                                            <a href="edit_sector/{{ $sector->id }}"
                                                                class="btn btn-info btn-sm">
                                                                <i class="fa fa-lg fa-fw fa-pen"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                @endcan
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

@section('css')
@stop
@section('js')
<script>
    $(document).ready(function () {
        $('#table1').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true, // Habilita a ordenação
            "order": [[0, "desc"]], // Define a primeira coluna (índice 0) em ordem decrescente
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

