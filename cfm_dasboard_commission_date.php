<?php
ob_start();
include 'includes/dbcon.php';
require 'compose_xl/autoload.php'; // Include the PhpSpreadsheet autoloader
// Your existing code for fetching data goes here

// Create a new PhpSpreadsheet instance
$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

if(isset($_REQUEST['report_filter'])){
	$start_date = $_REQUEST['start_date'];
	
	$end_date = $_REQUEST['end_date'];
}

// Add header row
$headerRow = ['DE','COMMISSION','DE STATUS','BADGE NAME','JOB TITLE','JOB CATEGORY', 'EVENT TITLE','EVENT DATE','EVENT ID','ATTENDACE ID','LAVEL'];
$sheet->fromArray([$headerRow], null, 'A1');
$rowNumber = 2;
//$sql2 = "SELECT * FROM cm_hubspot INNER JOIN cm_events_confirmed_delegates";
$sql2 = "SELECT b.de, a.com_req_level commission, a.de_status, b.badge_name, b.company_name, b.job_title, b.job_category, c.EVENT_TITLE, c.EVENT_DATE, a.ev_id, a.attend_id, a.level
FROM cmf_de_commission a,
cm_events_confirmed_delegates b,
cmf_event c
WHERE com_req_level > 0
AND a.attend_id = b.id
AND c.ID = b.event_id
AND b.event_id = a.ev_id
AND c.EVENT_DATE BETWEEN '$start_date' AND '$end_date' ORDER BY b.de, c.EVENT_DATE, b.company_name";
//FROM cm_hubspot INNER JOIN cm_events_confirmed_delegates ON cm_hubspot.email = cm_events_confirmed_delegates.email $search";
$result2 = $conn->query($sql2);
foreach ($result2 as $row2) {

	
    $rowData = [
		$row2['de'],
		$row2['commission'],
		$row2['de_status'],
		$row2['badge_name'],
		$row2['job_title'],
		$row2['job_category'],
		$row2['EVENT_TITLE'],
		$row2['EVENT_DATE'],
		$row2['ev_id'],
		$row2['attend_id'],
		$row2['level'],
		
		
        // Add other columns as needed
        // ...
    ];


    $sheet->fromArray([$rowData], null, 'A' . $rowNumber);
	
    $rowNumber++;
}

// Save the Excel file
$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$filename = 'exported_data'.$event_date.'.xlsx';
$writer->save($filename);

// Output the file to the browser for download
ob_clean();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;
header('Location:/'.$filename);
ob_end_flush();
?>
