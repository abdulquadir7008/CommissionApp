<?php
require '../compose_xl/autoload.php'; // Make sure you have the necessary dependencies installed

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

// Load data from the existing Excel file
$inputFileName = 'output/ForecastCommission20240606.xlsx';
$spreadsheet = IOFactory::load($inputFileName);
$worksheet = $spreadsheet->getActiveSheet();

// Create an associative array to store the data
$data = [];
foreach ($worksheet->getRowIterator() as $row) {
    $rowData = [];
    foreach ($row->getCellIterator() as $cell) {
        $rowData[] = $cell->getValue();
    }
    $data[] = $rowData;
}

// Create a pivot table (group by EVENT_TITLE and EVENT_DATE, sum commission)
$pivotData = [];
foreach ($data as $row) {
    $eventTitle = $row[20]; // Assuming EVENT_TITLE is in column T (index 20)
    $eventDate = $row[21]; // Assuming EVENT_DATE is in column U (index 21)
    $commission = $row[17]; // Assuming commission is in column R (index 17)

if ($eventTitle === 'EVENT_TITLE' && $eventDate === 'EVENT_DATE') {
        continue;
    }	
 $commission = (int)$commission;

if (!isset($pivotData[$eventTitle][$eventDate])) {
    $pivotData[$eventTitle][$eventDate] = 0;
}
$pivotData[$eventTitle][$eventDate] += $commission;
}

// Create a new spreadsheet for the pivot table
$pivotSpreadsheet = new Spreadsheet();
$pivotSheet = $pivotSpreadsheet->getActiveSheet();

// Write pivot table data to the sheet
$rowNumber = 1;
foreach ($pivotData as $eventTitle => $dates) {
    foreach ($dates as $eventDate => $sumCommission) {
        $pivotSheet->setCellValue('A' . $rowNumber, $eventTitle);
        $pivotSheet->setCellValue('B' . $rowNumber, $eventDate);
        $pivotSheet->setCellValue('C' . $rowNumber, $sumCommission);
        $rowNumber++;
    }
}

// Save the pivot table to a new Excel file
$pivotFileName = 'output/pivot_table.xlsx';
$writer = IOFactory::createWriter($pivotSpreadsheet, 'Xlsx');
$writer->save($pivotFileName);

echo "Pivot table saved as $pivotFileName";
?>
