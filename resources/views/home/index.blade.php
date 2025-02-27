@extends('adminlte::page')
@section('title', 'Dashboard GW | Pagina Inicial')
@section('content_header')
    <h4 class=""><i class="nav-icon fas fa-house "></i> &nbsp;&nbsp;Pagina Inicial</h4>
@stop
@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3> {{ $files_arq }} <sup style="font-size: 15px"> Arquivos</sup> </h3>
                    <p>Arquivos Cadastrados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-solid fa-cloud-arrow-down"></i>
                </div>
                
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $files_proc }} <sup style="font-size: 15px"> Procedimentos </sup></h3>
                    <p>Procedimentos Cadastrados</p>
                </div>
                <div class="icon">
                    <i class="far fa-solid fa-chalkboard-user"></i>
                </div>
                
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $clients }} <sup style="font-size: 15px"> Clientes </sup></h3>
                    <p>Clientes Cadastrados</p>
                </div>
                <div class="icon">
                    <i class="far fa-solid fa-water"></i>
                </div>
                
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $users }} <sup style="font-size: 15px"> Usuários </sup></h3>
                    <p>Usuários Cadastrados</p>
                </div>
                <div class="icon">
                    <i class="far fa-user"></i>
                </div>
                
            </div>
        </div>
    </div>

@stop
@section('css')
@stop
@section('js')
    <script>
        console.log('Hi!');
    </script>
@stop
