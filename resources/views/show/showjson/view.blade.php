@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Visualizar Arquivo JSON</h1>
        <pre id="json-display" style="white-space: pre-wrap; word-wrap: break-word;"></pre>
        <a href="{{ route('pop_clients_files.index') }}" class="btn btn-primary mt-3">Voltar</a>
    </div>

    <script>
        fetch('{{ route('showjson.view', ['id' => $file->id]) }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('json-display').textContent = JSON.stringify(data, null, 2);
            });
    </script>
@endsection