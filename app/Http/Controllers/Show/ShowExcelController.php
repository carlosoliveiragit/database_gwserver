<?php

namespace App\Http\Controllers\Show;

use App\Models\Files;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Routing\Controller;
use PhpOffice\PhpSpreadsheet\Settings;
use PhpOffice\PhpSpreadsheet\CachedObjectStorageFactory;
use Illuminate\Http\Request;

class ShowExcelController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
    }

    public function showExcel(Request $request, $id)
    {
        $file = Files::findOrFail($id);
        $filePath = $file->path . DIRECTORY_SEPARATOR . $file->file;

        if (!file_exists($filePath)) {
            return back()->with('error', 'O arquivo não existe no sistema de arquivos.');
        }

        $spreadsheet = IOFactory::load($filePath);
        $sheetNames = $spreadsheet->getSheetNames();
        $sheetsData = [];

        $rowsToLoad = (int) $request->query('rows', 5); // Padrão: 5 linhas

        // Nome da planilha selecionada, ou primeira se não houver
        $selectedSheet = $request->query('sheet', $sheetNames[0]);

        foreach ($sheetNames as $sheetIndex => $sheetName) {
            $worksheet = $spreadsheet->getSheet($sheetIndex);

            $freezePane = $worksheet->getFreezePane();
            $frozenColumn = 0;
            $frozenRow = 0;
            if (!empty($freezePane)) {
                list($frozenColumn, $frozenRow) = Coordinate::coordinateFromString($freezePane);
                $frozenColumn = Coordinate::columnIndexFromString($frozenColumn) - 1;
            }

            $sheetData = [];
            $maxRow = $worksheet->getHighestRow();  // Número total de linhas da planilha
            $maxCol = Coordinate::columnIndexFromString($worksheet->getHighestColumn());

            // Cabeçalho
            $headerRowCount = $frozenRow > 0 ? $frozenRow : 1;
            for ($rowIndex = 1; $rowIndex <= $headerRowCount; $rowIndex++) {
                $rowCells = [];
                for ($colIndex = 1; $colIndex <= $maxCol; $colIndex++) {
                    $cell = $worksheet->getCellByColumnAndRow($colIndex, $rowIndex);
                    $value = $cell->isFormula() ? $cell->getCalculatedValue() : $cell->getValue();

                    if (Date::isDateTime($cell)) {
                        $value = strpos($cell->getStyle()->getNumberFormat()->getFormatCode(), 'h') !== false
                            ? Date::excelToDateTimeObject($value)->format('H:i')
                            : Date::excelToDateTimeObject($value)->format('d/m/y');
                    }

                    $style = $worksheet->getStyleByColumnAndRow($colIndex, $rowIndex);
                    $fillColor = $style->getFill()->getStartColor()->getRGB() ?: 'FFFFFF';
                    $fontColor = $style->getFont()->getColor()->getRGB() ?: '000000';

                    $rowCells[] = [
                        'value' => $value,
                        'color' => $fillColor,
                        'fontColor' => $fontColor
                    ];
                }
                $sheetData['headers'][] = $rowCells;
            }

            // Dados (limitados ao número solicitado), começando pelas últimas linhas
            $filledRows = [];
            for ($rowIndex = $maxRow; $rowIndex > $headerRowCount; $rowIndex--) {
                $rowCells = [];
                $hasValue = false;

                for ($colIndex = 1; $colIndex <= $maxCol; $colIndex++) {
                    $cell = $worksheet->getCellByColumnAndRow($colIndex, $rowIndex);
                    $value = $cell->isFormula() ? $cell->getCalculatedValue() : $cell->getValue();

                    // Verificar se a célula tem valor
                    if (!empty($value)) {
                        $hasValue = true;
                    }

                    if (Date::isDateTime($cell)) {
                        $value = strpos($cell->getStyle()->getNumberFormat()->getFormatCode(), 'h') !== false
                            ? Date::excelToDateTimeObject($value)->format('H:i')
                            : Date::excelToDateTimeObject($value)->format('d/m/y');
                    }

                    $style = $worksheet->getStyleByColumnAndRow($colIndex, $rowIndex);
                    $fillColor = $style->getFill()->getStartColor()->getRGB() ?: 'FFFFFF';
                    $fontColor = $style->getFont()->getColor()->getRGB() ?: '000000';

                    $rowCells[] = [
                        'value' => $value,
                        'color' => $fillColor,
                        'fontColor' => $fontColor
                    ];
                }

                // Se a linha tiver ao menos uma célula com valor, adicione à variável $filledRows
                if ($hasValue) {
                    $filledRows[] = $rowCells;
                }

                // Se o número de linhas já carregadas for igual ao limite, pare
                if (count($filledRows) >= $rowsToLoad) {
                    break;
                }
            }

            // Reverte a ordem das linhas para exibir as últimas primeiro
            $sheetData['data'] = array_reverse($filledRows);
            $sheetData['frozenColumnIndex'] = $frozenColumn;
            $sheetData['frozenRowIndex'] = $frozenRow;
            $sheetData['maxRow'] = $maxRow;  // Adiciona o número total de linhas da planilha

            $sheetsData[$sheetName] = $sheetData;
        }

        return view('show.showexcel.view', [
            'sheetsData' => $sheetsData,
            'fileName' => $file->file,
            'selectedSheet' => $selectedSheet  // Passa o nome da planilha selecionada
        ]);
    }



}
