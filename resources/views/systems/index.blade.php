@extends('adminlte::page')
@section('title', 'Dashboard | Systems')

@section('content_header')
    <div class="row p-2">
        <div class="col-sm">
            <h2><i class="nav-icon fas fa-sitemap "></i> &nbsp;&nbsp;Gestão de Sistemas</h2>
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
            @if (isset($_GET['id']))
                <x-adminlte-card class="bg-warning" title=" Tem Certeza que Deseja Excluir o Sistema?" theme="warning"
                    icon="fas fa-exclamation-triangle" removable>
                    <h5><strong>{{ $_GET['system'] }}</strong></h5>
                    <form action="systems/{{ $_GET['id'] }}" method="POST">
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
            <h2 class="card-title"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp;<i class="fa-solid fa-sitemap "></i>
                &nbsp;&nbsp;Adicionar Sistema</h2>
        </div>
        <p></p>
        <form action="systems" method="POST">
            @csrf
            <div class="class row p-2">
                <div class="col-sm">
                    <div class="form-group">
                        <input type="text" class="form-control" id="system" name="system"
                            placeholder="Nome do Novo Sistema" required>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="input-group mb-3">
                        <button type="submit" class="btn btn-block btn-primary">
                            <i class="fa-solid fa-plus"></i>&nbsp;&nbsp;<span
                                class="fas fa-sitemap"></span>&nbsp;&nbsp;Cadastrar Sistema
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>
    <hr>
    <div class="card card-default">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-sitemap"></i> &nbsp;&nbsp;Sistemas Cadastrados</h2>
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
                                            <th>Sistema</th>
                                            @can('is_admin')
                                                <th>Ação</th>
                                            @endcan
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($Systems as $key => $return_db)
                                            <tr class="border border-secondary">
                                                <td><i class="fa-solid fa-hashtag text-primary"></i>
                                                    {{ $return_db->id }}
                                                </td>
                                                <td><i class="fa-solid fa-sitemap text-primary"></i>
                                                    {{ $return_db->system }}
                                                </td>
                                                @can('is_admin')
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <a type="submit" class="btn btn-danger btn-sm"
                                                                href="systems?id={{ $return_db->id }}&system={{ $return_db->system }}">
                                                                <i class="fa fa-lg fa-fw fa-trash"></i>
                                                            </a>
                                                            <a href="edit_system/{{ $return_db->id }}"  class="btn btn-info btn-sm">
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
