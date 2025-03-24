<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestData;
use Illuminate\Support\Facades\Log;

class TestDataController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Verificando se a requisição está vazia
            if ($request->all() === []) {
                return response("API GWScada / Laravel\nDados vazios recebidos corretamente\nTeste finalizado", 200)
                    ->header('Content-Type', 'text/plain');
            }

            // Log para ver os dados recebidos
            Log::info('Dados recebidos: ', $request->all());


            // Criação de uma nova entrada no banco de dados
            $data = TestData::create([
                'device' => $request->device,
                'temp' => $request->temp,
                'hum' => $request->hum,
                'state' => $request->state,
            ]);

            $dados = $request->all();
            $dadosFormatados = "";

            foreach ($dados as $chave => $valor) {
                $dadosFormatados .= "$chave : $valor\n";
            }

            return response("API GWScada / Laravel\nDados preenchidos recebidos corretamente\n\n" .
                $dadosFormatados . "\nTeste finalizado", 200)
                ->header('Content-Type', 'text/plain');

        } catch (\Exception $e) {
            // Logando o erro completo para depuração
            Log::error('Erro ao processar a requisição: ' . $e->getMessage());
            Log::error('Detalhes do erro: ' . $e->getTraceAsString());

            // Respondendo com uma mensagem amigável de erro
            return response("Ocorreu um erro ao processar a requisição.", 500);
        }
    }
}
