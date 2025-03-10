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
    $filePath = storage_path("app/" . $file->path . $file->file);

    if (!file_exists($filePath)) {
        return redirect()->route('view_production_data.index')->with('error', 'O arquivo não existe no sistema de arquivos.');
    }

    $spreadsheet = IOFactory::load($filePath);
    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

    // Filtra as últimas 5 linhas preenchidas
    $filledRows = array_filter($sheetData, function ($row) {
        return array_filter($row);
    });
    $last5Rows = array_slice($filledRows, -5);

    return view('showexcel.view', [
        'sheetData' => $last5Rows,
        'fileName' => $file->file
    ]);
}
}
