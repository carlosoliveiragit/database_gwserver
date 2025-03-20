@extends('adminlte::page')
@section('title', 'Dashboard GW | Visualizar Excel')

@section('content_header')
<h4><i class="fa-solid fa-table"></i> &nbsp;&nbsp;Visualizar Excel - Arquivo: {{ $fileName }}</h4>
@stop

@section('content')
    @php
        $currentRows = request()->query('rows', 5);
        $nextRows = $currentRows + 5;
        $firstSheet = reset($sheetsData);
        $maxRow = $firstSheet['maxRow'];
    @endphp

    <div class="row p-2">
        <div class="col-sm p-2">
            <h5 for="sheet">Selecionar Planilha:</h5>
        </div>
        <div class="col-sm-6 p-2">
            <form method="GET" action="">
                <div class="form-group">
                    <select name="sheet" id="sheet" class="form-control" onchange="this.form.submit()">
                        @foreach ($sheetsData as $sheetName => $sheet)
                            <option value="{{ $sheetName }}" {{ $selectedSheet == $sheetName ? 'selected' : '' }}>
                                {{ $sheetName }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="rows" value="{{ $currentRows }}">
                </div>
            </form>
        </div>
        <div class="col-sm p-2">
            <a class="btn btn-block btn-primary"
                href="{{ request()->fullUrlWithQuery(['rows' => $nextRows, 'sheet' => $selectedSheet]) }}">
                Carregar mais linhas ({{ $nextRows }})
            </a>
        </div>
        <div class="col-sm p-2">
            <a class="btn btn-block btn-danger"
                href="{{ request()->fullUrlWithQuery(['rows' => $maxRow, 'sheet' => $selectedSheet]) }}">
                Carregar todas as linhas
            </a>
        </div>
    </div>



    @php
        $sheet = $sheetsData[$selectedSheet];
        $headers = $sheet['headers'];
        $dataRows = $sheet['data'];
    @endphp

    <h6>Planilha: {{ $selectedSheet }}</h6>
    <div class="sheet-container card card-default">
        <div class="table-responsive">
            <table class="table table-bordered table-sm excel-table">
                <thead>
                    @foreach ($headers as $headerIndex => $headerRow)
                        <tr>
                            @foreach ($headerRow as $colIndex => $headerCell)
                                <th class="truncate" title="{{ $headerCell }}">
                                    {{ $headerCell }}
                                </th>
                            @endforeach
                        </tr>
                    @endforeach
                </thead>
                <tbody>
                    @forelse ($dataRows as $row)
                        <tr>
                            @foreach ($row as $colIndex => $cell)
                                <td class="truncate" title="{{ $cell }}">
                                    {{ $cell }}
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%">Nenhum dado encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
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
            max-height: 600px;
            overflow-y: auto;
            overflow-x: auto;
        }

        .excel-table {
            min-width: 1000px;
        }

        th,
        td {
            vertical-align: middle;
            text-align: center;
            min-width: 120px;
        }

        th[style*="sticky"],
        td[style*="sticky"] {
            background-clip: padding-box;
        }
    </style>
@endsection