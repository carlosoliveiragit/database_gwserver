@extends('adminlte::page')
@section('title', 'Dashboard GW | Visualizar Excel')
@section('content_header')
    <h4 class=""><i class="fa-solid fa-table"></i> &nbsp;&nbsp;Visualizar Excel</h4>
@stop
@section('content')
    <h5>Arquivo: {{ $fileName }}</h5>
    @foreach ($sheetsData as $sheetName => $sheet)
        <h6>Planilha: {{ $sheetName }}</h6>
        <p>Linhas congeladas: {{ $sheet['frozenRowIndex'] }}</p>
        <p>Colunas congeladas: {{ $sheet['frozenColumnIndex'] }}</p>
        <div class="sheet-container card card-default">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        @foreach ($sheet['headers'] as $headerRow)
                            <tr>
                                @foreach ($headerRow as $headerCell)
                                    <th title="{{ $headerCell }}" class="truncate">
                                        {{ $headerCell }}
                                    </th>
                                @endforeach
                            </tr>
                        @endforeach
                    </thead>
                    <tbody>
                        @foreach ($sheet['data'] as $row)
                            <tr>
                                @foreach ($row as $cell)
                                    <td title="{{ $cell }}" class="truncate">
                                        {{ $cell }}
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
            /* Define um tamanho fixo para evitar reflows */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sheet-container {
            margin-top: 20px;
            /* Espaçamento entre planilhas */
            padding: 20px;
            /* Adiciona padding ao contêiner */
            border-top: 2px solid #ddd;
            /* Linha divisória opcional */
            max-height: 500px;
            /* Altura máxima para o contêiner */
            overflow-y: auto;
            /* Adiciona rolagem vertical */
        }
    </style>
@endsection