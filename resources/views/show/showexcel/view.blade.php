@extends('adminlte::page')
@section('title', 'Dashboard GW | Visualizar Excel')

@section('content_header')
<h4 class=""><i class="fa-solid fa-table"></i> &nbsp;&nbsp;Visualizar Excel</h4>
@stop

@section('content')


    {{-- Botões "Carregar mais linhas" e "Carregar todas as linhas" no topo --}}
    @php
        $currentRows = request()->query('rows', 5);
        $nextRows = $currentRows + 5;
    @endphp
    <div class="row p-2">
        <div class="col-sm-4 p-2">{{-- Botão "Carregar mais linhas" --}}
            <h5>Arquivo: {{ $fileName }}</h5>
        </div>
        <div class="col-sm-4 p-2">{{-- Botão "Carregar mais linhas" --}}
            <a class="btn btn-block btn-primary"
                href="{{ request()->fullUrlWithQuery(['rows' => $nextRows, 'sheet' => $selectedSheet]) }}">
                Carregar mais linhas ({{ $nextRows }})
            </a>
        </div>
        <div class="col-sm-4 p-2"> {{-- Botão "Carregar todas as linhas" --}}
            @php
                // A primeira planilha é usada para determinar o número máximo de linhas
                $firstSheet = reset($sheetsData);
                $maxRow = $firstSheet['maxRow'];  // Total de linhas no primeiro sheet
            @endphp
            <a class="btn btn-block btn-danger"
                href="{{ request()->fullUrlWithQuery(['rows' => $maxRow, 'sheet' => $selectedSheet]) }}">
                Carregar todas as linhas
            </a>
        </div>
    </div>

    {{-- Exibição das planilhas --}}
    @foreach ($sheetsData as $sheetName => $sheet)
        <h6>Planilha: {{ $sheetName }}</h6>
        <div class="sheet-container card card-default">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        @foreach ($sheet['headers'] as $headerRow)
                            <tr class="row-height">
                                @foreach ($headerRow as $headerCell)
                                    <th title="{{ $headerCell['value'] }}" class="truncate"
                                        style="background-color: #{{ $headerCell['color'] }}; color: #{{ $headerCell['fontColor'] }}">
                                        {{ $headerCell['value'] }}
                                    </th>
                                @endforeach
                            </tr>
                        @endforeach
                    </thead>
                    <tbody>
                        @foreach ($sheet['data'] as $row)
                            <tr class="row-height">
                                @foreach ($row as $cell)
                                    <td title="{{ $cell['value'] }}" class="truncate"
                                        style="background-color: #{{ $cell['color'] }}; color: #{{ $cell['fontColor'] }}">
                                        {{ $cell['value'] }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
@endsection

@section('css')
    <style>
        .truncate {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sheet-container {
            margin-top: 20px;
            padding: 20px;
            border-top: 2px solid #ddd;
            max-height: 500px;
            overflow-y: auto;
        }

        .row-height {
            height: 15px;
        }
    </style>
@endsection