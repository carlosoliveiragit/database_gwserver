@extends('adminlte::page')
@section('title', 'Dashboard | Types')

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
                <x-adminlte-card class="bg-warning" title=" Tem Certeza que Deseja Excluir o Tipo?" theme="warning"
                    icon="fas fa-exclamation-triangle" removable>
                    <h5><strong>{{ $_GET['system'] }}</strong></h5>
                    <form action="types/{{ $_GET['id'] }}" method="POST">
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
            <h2 class="card-title"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp;<i class="fa-solid fa-text-height "></i>
                &nbsp;&nbsp;Adicionar Tipo</h2>
        </div>
        <p></p>
        <form action="types" method="POST">
            @csrf
            <div class="class row p-2">
                <div class="col-sm">
                    <div class="form-group">
                        <input type="text" class="form-control" id="system" name="type"
                            placeholder="Nome do Novo Tipo" required>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="input-group mb-3">
                        <button type="submit" class="btn btn-block btn-primary">
                            <i class="fa-solid fa-plus"></i>&nbsp;&nbsp;<span
                                class="fa-solid fa-text-height"></span>&nbsp;&nbsp;Cadastrar Tipo
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>
    <hr>
    <div class="card card-default">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-text-height"></i> &nbsp;&nbsp;Tipos Cadastrados</h2>
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
                                            <th>Tipo</th>
                                            @can('is_admin')
                                                <th>Ação</th>
                                            @endcan
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($Types as $type)
                                            <tr class="border border-secondary">
                                                <td>
                                                    {{ $type->id }}
                                                </td>
                                                <td>
                                                    {{ $type->name }}
                                                </td>
                                                @can('is_admin')
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <a type="submit" class="btn btn-danger btn-sm"
                                                                href="types?id={{ $type->id }}&system={{ $type->name }}">
                                                                <i class="fa fa-lg fa-fw fa-trash"></i>
                                                            </a>
                                                            <a href="edit_type/{{ $type->id }}"
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

