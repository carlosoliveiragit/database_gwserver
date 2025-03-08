@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Visualizar Arquivo PDF</h1>
        <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="{{ route('pop_clients_files.view', ['id' => $file->id]) }}" allowfullscreen></iframe>
        </div>
        <a href="{{ route('pop_clients_files.index') }}" class="btn btn-primary mt-3">Voltar</a>
    </div>
@endsection