
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
@extends('adminlte::page')
@section('title', 'Dashboard | Users')
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
                @if ($_GET['id'] != ($user = Auth::user()['id']))
                    <x-adminlte-card class="bg-warning" title=" Tem Certeza que Deseja Excluir o Usuário?" theme="warning"
                        icon="fas fa-exclamation-triangle" removable>
                        <h5><strong>{{ $_GET['name'] }}</strong></h5>
                        <form action="users/{{ $_GET['id'] }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fa fa-lg fa-fw fa-trash"></i>&nbsp;&nbsp;Deletar
                            </button>
                        </form>
                    </x-adminlte-card>
                @else
                    <x-adminlte-card class="bg-warning" title="Você não pode Deletar seu Usuário" theme="warning"
                        icon="fas fa-exclamation-triangle" removable>
                    </x-adminlte-card>
                @endif
            @endif
        </div>
    </div>
@stop

@section('content')
    <div class="card card-default">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-user-plus"></i> &nbsp;&nbsp;Adicionar Usuário</h2>
        </div>
        <form action="users" method="post">
            @csrf
            <div class="row p-2">
                <div class="col-sm">
                    <label>Nome</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-user text-primary"></i>
                            </span>
                        </div>
                        <input type="text" name="name" class="form-control" placeholder="Nome do novo Usuário"
                            required>
                    </div>
                </div>
                <div class="col-sm">
                    <label>Tema</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-moon text-primary"></i></span>
                        </div>
                        <select class="form-control" name="admin_lte_dark_mode">
                            <option value="0">Dark Mode OFF</option>
                            <option value="1">Dark Mode ON</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row p-2">
                <div class="col-sm">
                    <label>Senha</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-lock text-primary"></i></span>
                        </div>
                        <input type="password" name="password" class="form-control" placeholder="Senha Minimo 8 Caracteres"
                            minlength="8" maxlength="20" size="20" required>
                    </div>
                </div>
                <div class="col-sm">
                    <x-adminlte-select2 name="profiles_profile" label="Setor" data-placeholder="selecione o setor...">
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-primary">
                                <i class="fas fa-solid fa-sitemap"></i>
                            </div>
                        </x-slot>
                        @foreach ($Profiles as $profile)
                            <option disabled="disabled" selected></option>
                            <option>{{ $profile->name }}</option>
                        @endforeach
                    </x-adminlte-select2>
                </div>
            </div>
            <div class="row p-2">
                <div class="col-sm">
                    <label>E-mail</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-envelope text-primary"></i>
                            </span>
                        </div>
                        <input type="email" name="email" class="form-control" placeholder="E-mail" required autocomplete="off">
                    </div>
                </div>
                <div class="col-sm">
                    <x-adminlte-select2 name="sectors_sector" label="Setor" data-placeholder="selecione o setor...">
                        <x-slot name="prependSlot">
                            <div class="input-group-text text-primary">
                                <i class="fas fa-solid fa-sitemap"></i>
                            </div>
                        </x-slot>
                        @foreach ($Sectors as $sector)
                            <option disabled="disabled" selected></option>
                            <option>{{ $sector->name }}</option>
                        @endforeach
                    </x-adminlte-select2>
                </div>
            </div>
            <div class="row p-2">
                <div class="col-sm">
                    <label>Ação</label>
                    <div class="input-group mb-3">
                        <button type="submit" class="btn btn-block btn-primary">
                            <span class="fas fa-user-plus"></span>&nbsp;&nbsp;Cadastrar Usuário
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="card card-default">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-users"></i> &nbsp;&nbsp;Usuários Cadastrados</h2>
        </div>
        <div class="row p-2">
            <div class="col-12 col-sm-12">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                    <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                        aria-labelledby="custom-tabs-one-home-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <table id="table1" class="table table-sm table-bordered table-hover"
                                    style="width: 100%;">
                                    <thead>
                                        <tr class="text-secondary">
                                            <th>Id</th>
                                            <th>Nome</th>
                                            <th>Email</th>
                                            <th>Setor</th>
                                            <th>Profile</th>
                                            <th>Dark Mode</th>
                                            @can('is_admin')
                                                <th>Ação</th>
                                            @endcan
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($Users as $user)
                                            <tr class="border border-secondary">
                                                <td>
                                                    {{ $user->id }}
                                                </td>
                                                <td>
                                                    {{ $user->name }}
                                                </td>
                                                <td>
                                                    {{ $user->email }}
                                                </td>
                                                <td>
                                                    {{ $user->sector->name }}
                                                </td>
                                                <td>
                                                    {{ $user->profile->name }}
                                                </td>
                                                <th>
                                                    {{ $user->admin_lte_dark_mode }}
                                                </th>
                                                @can('is_admin')
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <a type="submit" class="btn btn-primary btn-lg px-2 py-3"
                                                                href="users?id={{ $user->id }}&name={{ $user->name }}">
                                                                <i class="fa fa-lg fa-fw fa-trash"></i>
                                                            </a>
                                                            <a class="btn btn-danger btn-lg px-2 py-3"
                                                                href="edit_user/{{ $user->id }}">
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

