<?php

namespace App\Http\Controllers\Show;

use App\Models\Files;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Routing\Controller;

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
        $sheetsData = [];

        foreach ($sheetNames as $sheetIndex => $sheetName) {
            $worksheet = $spreadsheet->getSheet($sheetIndex);

            // Verifica se há linhas ou colunas congeladas
            $freezePane = $worksheet->getFreezePane();
            if ($freezePane) {
                list($frozenColumn, $frozenRow) = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::coordinateFromString($freezePane);
                $frozenColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($frozenColumn);
                $frozenRowIndex = (int)$frozenRow;
            } else {
                $frozenColumnIndex = 0;
                $frozenRowIndex = 0;
            }

            $sheetData = $worksheet->toArray(null, true, true, false);

            // Usa o número de linhas congeladas como cabeçalhos
            $headerRowCount = $frozenRowIndex;
            $headers = array_slice($sheetData, 0, $headerRowCount);

            $filledRows = array_filter(array_slice($sheetData, $headerRowCount), function ($row) {
                return array_filter($row);
            });
            $last5Rows = array_slice($filledRows, -5);

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

            $filteredData = array_map(function ($row) use ($columnsToKeep) {
                return array_intersect_key($row, array_flip($columnsToKeep));
            }, $last5Rows);

            $sheetsData[$sheetName] = [
                'headers' => $filteredHeaders,
                'data' => $filteredData,
                'frozenColumnIndex' => $frozenColumnIndex,
                'frozenRowIndex' => $frozenRowIndex
            ];
        }

        return view('show.showexcel.view', [
            'sheetsData' => $sheetsData,
            'fileName' => $file->file
        ]);
    }
}