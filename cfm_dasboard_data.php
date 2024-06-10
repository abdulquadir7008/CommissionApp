<?php
ob_start();
include 'includes/dbcon.php';
require 'compose_xl/autoload.php'; // Include the PhpSpreadsheet autoloader

// Your existing code for fetching data goes here change

// Create a new PhpSpreadsheet instance
$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$event_data_check = mysqli_query( $conn, "select * from cmf_event WHERE STATUS='Completed' Order by ID DESC" );
$qr_list = mysqli_fetch_array( $event_data_check );
$event_date = $qr_list['EVENT_DATE'];
$event_id =$qr_list['ID'];

if(!empty($_REQUEST['de_request']) && !empty($_REQUEST['event_request'])){
	$deeland = $_REQUEST['de_request'];
	$eventland = $_REQUEST['event_request'];
	$search = "WHERE cm_events_confirmed_delegates.de='$deeland' AND cm_events_confirmed_delegates.event_id='$eventland'";
}
else if(!empty($_REQUEST['de_request'])){
	$deeland = $_REQUEST['de_request'];
	$search = "WHERE cm_events_confirmed_delegates.de='$deeland'";
}
else if(!empty($_REQUEST['event_request'])){
	$eventland = $_REQUEST['event_request'];
	$search = "WHERE cm_events_confirmed_delegates.event_id='$eventland'";
}
else{
	$search = '';
}
// Add header row
$headerRow = ['EVENT','FIRSTNAME','Hub-FIRSTNAME','LASTNAME','HUB-LASTNAME','DID','HUB-DID', 'EMAIL','HUB-EMAIL', 'DE','HUB-DE','JOB TITLE','HUB-JOB TITLE','ORGANISATION','HUB-ORGANISATION','STREET ADDRESS','HUB-STREET ADDRESS',  'CITY','HUB-CITY', 'COUNTRY','HUB-COUNTRY', 'POSTAL CODE','HUB-POSTAL CODE', 'MOBILE NUMBER','HUB-MOBILE NUMBER','MISMATCH COLUMN'];
$sheet->fromArray([$headerRow], null, 'A1');

// Add data rows
$rowNumber = 2;
//$sql2 = "SELECT * FROM cmf_hubspot INNER JOIN cm_events_confirmed_delegates";
$sql2 = "SELECT cmf_hubspot.firstname As Hbfirstname,
cmf_hubspot.lastname As Hblastname,
cmf_hubspot.did As Hbdid,
cmf_hubspot.email As Hbemail,
cmf_hubspot.de As Hbde,
cmf_hubspot.job_title As Hbjob_title,
cmf_hubspot.company_name As Hbcompany_name,
cmf_hubspot.event_id As Hbevent_id,
cmf_hubspot.stret_address As Hbstret_address,
cmf_hubspot.city As Hbcity,
cmf_hubspot.country As Hbcountry,
cmf_hubspot.postalcode As Hbpostalcode,
cmf_hubspot.mobilenumber As Hbmobilenumber,
cm_events_confirmed_delegates.delegate_first_name As QRfirstname,
cm_events_confirmed_delegates.delegate_last_name As QRlastname,
cm_events_confirmed_delegates.phone_number As QRdid,
cm_events_confirmed_delegates.email As QRemail,
cm_events_confirmed_delegates.de As QRde,
cm_events_confirmed_delegates.job_title As QRjob_title,
cm_events_confirmed_delegates.company_name As QRorg,
cm_events_confirmed_delegates.event_id As QRevent_id,
cm_events_confirmed_delegates.street_address As QRstreet,
cm_events_confirmed_delegates.city As QRcity,
cm_events_confirmed_delegates.country_region As QRcountry,
cm_events_confirmed_delegates.postal_code As QRpostal_code,
cm_events_confirmed_delegates.mobile_number As QRmobile,
cm_events_confirmed_delegates.id As QRid

FROM cm_events_confirmed_delegates
LEFT JOIN cmf_hubspot ON cm_events_confirmed_delegates.event_id = cmf_hubspot.event_id AND cm_events_confirmed_delegates.email = cmf_hubspot.email
INNER JOIN cmf_de_commission ON cm_events_confirmed_delegates.id = cmf_de_commission.attend_id
$search";
//FROM cmf_hubspot INNER JOIN cm_events_confirmed_delegates ON cmf_hubspot.email = cm_events_confirmed_delegates.email $search";
$result2 = $conn->query($sql2);
foreach ($result2 as $row2) {
$event_data_check = mysqli_query( $conn, "select * from cmf_event WHERE ID='".$row2['QRevent_id']."'" );
$qr_list = mysqli_fetch_array( $event_data_check );
$sqlMatchstatus = mysqli_query( $conn, "select * from cmf_de_commission WHERE attend_id='".$row2['QRid']."'" );
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
