<?php

namespace Src\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportController
{
    public function export()
    {
        $file = 'data.csv';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        if (($handle = fopen($file, 'r')) !== false) {
            $row = 1;
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $sheet->fromArray($data, null, 'A' . $row++);
            }
            fclose($handle);
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save('export.xlsx');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="export.xlsx"');
        $writer->save('php://output');
    }
}
