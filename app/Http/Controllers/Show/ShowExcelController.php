<?php

namespace App\Http\Controllers\Show;

use App\Models\Files;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Routing\Controller;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ShowExcelController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
    }

    public function showExcel($id)
    {
        $file = Files::findOrFail($id);
        $filePath = $file->path . DIRECTORY_SEPARATOR . $file->file;

        if (!file_exists($filePath)) {
            return back()->with('error', 'O arquivo não existe no sistema de arquivos.');
        }

        $spreadsheet = IOFactory::load($filePath);
        $sheetNames = $spreadsheet->getSheetNames();

        $rowsToLoad = request()->query('rows', 5);
        $selectedSheet = request()->query('sheet', $sheetNames[0]); // Padrão: primeira planilha
        $sheetsData = [];

        foreach ($sheetNames as $sheetIndex => $sheetName) {
            /** @var Worksheet $worksheet */
            $worksheet = $spreadsheet->getSheet($sheetIndex);
            $sheetData = $worksheet->toArray(null, true, true, false);

            $headerRowCount = 5;
            $headers = array_slice($sheetData, 0, $headerRowCount);
            $dataRows = array_slice($sheetData, $headerRowCount);

            // Remove linhas totalmente vazias
            $filledRows = array_filter($dataRows, function ($row) {
                return array_filter($row);
            });

            // Identifica colunas não vazias
            $columnsToKeep = [];
            foreach ($headers as $headerRow) {
                foreach ($headerRow as $colIndex => $headerCell) {
                    if (!empty(trim($headerCell))) {
                        $columnsToKeep[] = $colIndex;
                    }
                }
            }
            $columnsToKeep = array_unique($columnsToKeep);

            $filteredHeaders = array_map(function ($headerRow) use ($columnsToKeep) {
                return array_intersect_key($headerRow, array_flip($columnsToKeep));
            }, $headers);

            // Apenas para a planilha selecionada, carrega as últimas linhas
            if ($sheetName === $selectedSheet) {
                $totalRows = count($filledRows);
                $startIndex = max($totalRows - $rowsToLoad, 0);
                $lastRows = array_slice($filledRows, $startIndex, $rowsToLoad);

                $filteredData = array_map(function ($row) use ($columnsToKeep) {
                    return array_intersect_key($row, array_flip($columnsToKeep));
                }, $lastRows);

                // Removido a lógica de congelamento (freeze)
                $sheetsData[$sheetName] = [
                    'headers' => $filteredHeaders,
                    'data' => $filteredData,
                    'maxRow' => $totalRows,
                ];
            } else {
                // Outras planilhas não carregam dados
                $sheetsData[$sheetName] = [
                    'headers' => $filteredHeaders,
                    'data' => [],
                    'maxRow' => count($filledRows),
                ];
            }
        }
        //dd($sheetsData);
        return view('show.showexcel.view', [
            'sheetsData' => $sheetsData,
            'fileName' => $file->file,
            'selectedSheet' => $selectedSheet
        ]);
    }
}
