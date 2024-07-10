@extends('adminlte::page')
@section('title', 'Dashboard GW | Files')
@section('css')
@stop
@section('content_header')
    <div class="row p-2">
        <div class="col-sm">
            <h2><i class="nav-icon fas fa-file "></i> &nbsp;&nbsp;Arquivos Cadastrados</h2>
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
                <x-adminlte-card class="bg-warning" title=" Tem Certeza que Deseja Excluir o Arquivo?" theme="warning"
                    icon="fas fa-exclamation-triangle" removable>
                    <h6>Dir:<strong>{{ $_GET['path'] }}</strong></h6><br>
                    <h6>File:<strong>{{ $_GET['file'] }}</strong></h6>
                    <form action="files/{{ $_GET['id'] }}" method="POST">
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
    <hr>
    <div class="card card-default">
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
                                            <th>Usuário</th>
                                            <th>Cliente</th>
                                            <th>Sistema</th>
                                            <th>Tipo</th>
                                            <th>Data</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($Files as $key => $return_db)
                                            <tr class="border border-secondary">
                                                <td><i class="fa-solid fa-hashtag text-primary"></i>
                                                    {{ $return_db->id }}
                                                </td>
                                                <td><i class="fa-solid fa-user text-primary"></i>
                                                    {{ $return_db->users_name }}
                                                </td>
                                                <td><i class="fa-solid fa-water text-primary"></i>
                                                    {{ $return_db->clients_client }}
                                                </td>
                                                <td><i class="fa-solid fa-sitemap text-primary"></i>
                                                    {{ $return_db->systems_system }}
                                                </td>
                                                <td><i class="fa-solid fa-file text-primary"></i>
                                                    {{ $return_db->type }}
                                                </td>
                                                <td><i class="fa-solid fa-calendar-plus text-primary"></i>
                                                    {{ $return_db->created_at }}
                                                </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <a type="submit"  class="btn btn-success btn-sm"
                                                                href="{{ $return_db->path }}{{ $return_db->file }}">
                                                                <i class="fa fa-lg fa-fw fa-download"></i>
                                                            </a>
                                                            @can('is_admin')
                                                            <a type="submit" class="btn btn-danger btn-sm"
                                                                href="files?id={{ $return_db->id }}&path={{ $return_db->path }}&file={{ $return_db->file }}">
                                                                <i class="fa fa-lg fa-fw fa-trash"></i>
                                                            </a>
                                                            @endcan
                                                        </div>
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
