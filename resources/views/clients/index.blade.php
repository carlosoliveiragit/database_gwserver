@extends('adminlte::page')
@section('title', 'Dashboard | Clients')
@section('content_header')
    <div class="row p-2">
        <div class="col-sm">
            <h2><i class="nav-icon fas fa-water "></i> &nbsp;&nbsp;Gestão de Clientes</h2>
        </div>
        <div class="col-sm">
            @if (session('success'))
                <x-adminlte-card title=" {{ session('success') }}" theme="success" icon="fas fa-lg fa-thumbs-up" removable>
                </x-adminlte-card>
            @endif
            @if (session('error'))
                <x-adminlte-card title=" {{ session('error') }}" theme="danger" icon="fas fa-lg fa-thumbs-down" removable>
                </x-adminlte-card>
            @endif
        </div>
        @if (isset($_GET['id']))
            <x-adminlte-card class="bg-warning" title=" Tem Certeza que Deseja Excluir o Cliente?" theme="warning"
                icon="fas fa-exclamation-triangle" removable>
                <h5><strong>{{ $_GET['client'] }}</strong></h5>
                <form action="clients/{{ $_GET['id'] }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fa fa-lg fa-fw fa-trash"></i>&nbsp;&nbsp;Deletar
                    </button>
                </form>
            </x-adminlte-card>
        @endif
    </div>
@stop

@section('content')

    <div class="card card-default">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp;<i class="fa-solid fa-water "></i>
                &nbsp;&nbsp;Adicionar Cliente</h2>
        </div>
        <p></p>
        <form action="clients" method="POST">
            @csrf
            <div class="class row p-2">
                <div class="col-sm">
                    <div class="form-group">
                        <input type="text" class="form-control" id="client" name="client"
                            placeholder="Nome do Novo Cliente" required>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="input-group mb-3">
                        <button type="submit" class="btn btn-block btn-primary">
                            <i class="fa-solid fa-plus"></i>&nbsp;&nbsp;<span
                                class="fas fa-water"></span>&nbsp;&nbsp;Cadastrar Cliente
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>
    <hr>
    <div class="card card-default">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-water"></i> &nbsp;&nbsp;Clientes Cadastrados</h2>
        </div>
        <div class="row p-2">
            <div class="col-12 col-sm-12">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                    <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                        aria-labelledby="custom-tabs-one-home-tab">
                        <div class="row p-2">
                            <div class="col-md-12">
                                <table id="table1" class="table table-bordered table-hover" style="width: 100%;">
                                    <thead>
                                        <tr class="text-secondary">
                                            <th>Id</th>
                                            <th>Cliente</th>
                                            @can('is_admin')
                                                <th>Ação</th>
                                            @endcan
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($Clients as $key => $return_db)
                                            <tr class="border border-secondary">
                                                <td><i class="fa-solid fa-hashtag text-primary"></i>
                                                    {{ $return_db->id }}
                                                </td>
                                                <td><i class="fa-solid fa-water text-primary"></i>
                                                    {{ $return_db->client }}
                                                </td>
                                                @can('is_admin')
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <a type="submit" class="btn btn-danger btn-sm"
                                                                href="clients?id={{ $return_db->id }}&client={{ $return_db->client }}">
                                                                <i class="fa fa-lg fa-fw fa-trash"></i>
                                                            </a>
                                                            <a href="edit_client/{{ $return_db->id }}" class="btn btn-info btn-sm">
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
        $('#table1').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "responsive": true
        });
    </script>
@stop
