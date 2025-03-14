<?php
namespace App\Http\Controllers;

use App\Models\Files;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
            return redirect()->route('view_production_data.index')->with('error', 'O arquivo não existe no sistema de arquivos.');
        }

        $spreadsheet = IOFactory::load($filePath);
        $sheetNames = $spreadsheet->getSheetNames();
        $sheetsData = [];

        foreach ($sheetNames as $sheetIndex => $sheetName) {
            $worksheet = $spreadsheet->getSheet($sheetIndex);
            $sheetData = $worksheet->toArray(null, true, true, false);

            $headerRowCount = 3;
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
                'data' => $filteredData
            ];
        }

        return view('showexcel.view', [
            'sheetsData' => $sheetsData,
            'fileName' => $file->file
        ]);
    }
}