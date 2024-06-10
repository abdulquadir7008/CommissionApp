<?php
ob_start();
include 'includes/dbcon.php';
require 'compose_xl/autoload.php'; // Include the PhpSpreadsheet autoloader

// Your existing code for fetching data goes here change

// Create a new PhpSpreadsheet instance
$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$event_data_check = mysqli_query( $conn, "select * from qr_event WHERE STATUS='Completed' Order by ID DESC" );
$qr_list = mysqli_fetch_array( $event_data_check );
$event_date = $qr_list['EVENT_DATE'];
$event_id =$qr_list['ID'];

if(!empty($_REQUEST['de_request']) && !empty($_REQUEST['event_request'])){
	$deeland = $_REQUEST['de_request'];
	$eventland = $_REQUEST['event_request'];
	$search = "WHERE qr_attendance_history.de='$deeland' AND qr_attendance_history.event_id='$eventland'";
}
else if(!empty($_REQUEST['de_request'])){
	$deeland = $_REQUEST['de_request'];
	$search = "WHERE qr_attendance_history.de='$deeland'";
}
else if(!empty($_REQUEST['event_request'])){
	$eventland = $_REQUEST['event_request'];
	$search = "WHERE qr_attendance_history.event_id='$eventland'";
}
else{
	$search = '';
}
// Add header row
$headerRow = ['EVENT','FIRSTNAME','Hub-FIRSTNAME','LASTNAME','HUB-LASTNAME','DID','HUB-DID', 'EMAIL','HUB-EMAIL', 'DE','HUB-DE','JOB TITLE','HUB-JOB TITLE','ORGANISATION','HUB-ORGANISATION','STREET ADDRESS','HUB-STREET ADDRESS',  'CITY','HUB-CITY', 'COUNTRY','HUB-COUNTRY', 'POSTAL CODE','HUB-POSTAL CODE', 'MOBILE NUMBER','HUB-MOBILE NUMBER','MISMATCH COLUMN'];
$sheet->fromArray([$headerRow], null, 'A1');

// Add data rows
$rowNumber = 2;
//$sql2 = "SELECT * FROM cm_hubspot INNER JOIN qr_attendance_history";
$sql2 = "SELECT cm_hubspot.firstname As Hbfirstname,
cm_hubspot.lastname As Hblastname,
cm_hubspot.did As Hbdid,
cm_hubspot.email As Hbemail,
cm_hubspot.de As Hbde,
cm_hubspot.job_title As Hbjob_title,
cm_hubspot.company_name As Hbcompany_name,
cm_hubspot.event_id As Hbevent_id,
cm_hubspot.stret_address As Hbstret_address,
cm_hubspot.city As Hbcity,
cm_hubspot.country As Hbcountry,
cm_hubspot.postalcode As Hbpostalcode,
cm_hubspot.mobilenumber As Hbmobilenumber,
qr_attendance_history.firstname As QRfirstname,
qr_attendance_history.lastname As QRlastname,
qr_attendance_history.did As QRdid,
qr_attendance_history.email As QRemail,
qr_attendance_history.de As QRde,
qr_attendance_history.job_title As QRjob_title,
qr_attendance_history.org As QRorg,
qr_attendance_history.event_id As QRevent_id,
qr_attendance_history.street As QRstreet,
qr_attendance_history.city As QRcity,
qr_attendance_history.country As QRcountry,
qr_attendance_history.postal_code As QRpostal_code,
qr_attendance_history.mobile As QRmobile,
qr_attendance_history.id As QRid

FROM qr_attendance_history
LEFT JOIN cm_hubspot ON qr_attendance_history.event_id = cm_hubspot.event_id AND qr_attendance_history.email = cm_hubspot.email
INNER JOIN cm_de_commission ON qr_attendance_history.id = cm_de_commission.attend_id
$search";
//FROM cm_hubspot INNER JOIN qr_attendance_history ON cm_hubspot.email = qr_attendance_history.email $search";
$result2 = $conn->query($sql2);
foreach ($result2 as $row2) {
$event_data_check = mysqli_query( $conn, "select * from qr_event WHERE ID='".$row2['QRevent_id']."'" );
$qr_list = mysqli_fetch_array( $event_data_check );
$sqlMatchstatus = mysqli_query( $conn, "select * from cm_de_commission WHERE attend_id='".$row2['QRid']."'" );
$listMtstatuc = mysqli_fetch_array( $sqlMatchstatus );	
    $rowData = [
		$qr_list['EVENT_TITLE'],
		$row2['QRfirstname'],
		$row2['Hbfirstname'],
        $row2['QRlastname'],
		$row2['Hblastname'],
		$row2['QRdid'],
        $row2['Hbdid'],
		$row2['QRemail'],
		$row2['Hbemail'],
		$row2['QRde'],
        $row2['Hbde'],
		$row2['QRjob_title'],
        $row2['Hbjob_title'],
        $row2['QRorg'],
		$row2['Hbcompany_name'],
        $row2['QRstreet'],
		$row2['Hbstret_address'],
        $row2['QRcity'],
		$row2['Hbcity'],
        $row2['QRcountry'],
		$row2['Hbcountry'],
        $row2['QRpostal_code'],
		$row2['Hbpostalcode'],
        $row2['QRmobile'],
		$row2['Hbmobilenumber'],
		$listMtstatuc['valid']
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
