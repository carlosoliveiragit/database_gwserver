@extends('adminlte::page')
@section('title', 'Dashboard GW | Visualizar Excel')
@section('content_header')
    <h4 class=""><i class="fa-solid fa-table"></i></i> &nbsp;&nbsp;{{ $fileName }}</h4>
@stop
@section('content')
    <div class="container">
        <table id="table1" class="table table-sm table-bordered table-hover">
        @foreach ($sheetData as $row)
                <tr>
                    @foreach ($row as $cell)
                        <td>{{ $cell }}</td>
                    @endforeach
                </tr>
            @endforeach
        </table>
    </div>
@endsection