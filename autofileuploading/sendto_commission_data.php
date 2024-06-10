<?php
include '../includes/dbcon.php';
require '../compose_xl/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Your existing code to create spreadsheet and add data

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$headerRow = ['FILE NAME','EVENT ID','DE', 'ID','FIRST NAME','LAST NAME','JOB TITLE','JOB CATEGORY','WISH LIST', 'COMPANY NAME','PHONE NUMBER','MOBILE NUMBER','EMAIL','ALTERNATIVE EMAIL','STREET ADDRESS','CITY','POSTAL CODE','COMMISSION','COM REQ LEVEL','MISMATCH','EVENT TITLE','EVENT DATE'];
$sheet->fromArray([$headerRow], null, 'A1');
$rowNumber = 2;

$sqlExcelOutput = "SELECT distinct qra.filename, qra.event_id, qra.de, qra.id, qra.delegate_first_name, qra.delegate_last_name, qra.job_title, qra.job_category, qra.wish_list, qra.company_name, qra.phone_number, qra.mobile_number, qra.email, qra.alternative_email, qra.street_address, qra.city, qra.postal_code, wl.commission, cdc.com_req_level, cdc.valid AS Mismatch, ce.EVENT_TITLE, ce.EVENT_DATE FROM cm_events_confirmed_delegates qra, cm_job_title jd, cm_wislist wl, cmf_de_commission cdc, cmf_event ce where qra.event_id = cdc.ev_id and qra.id = cdc.attend_id and ce.id = qra.event_id and qra.job_category = jd.title and jd.level_id = wl.level GROUP BY `qra`.`id` ORDER BY qra.event_id;";

$result2 = $conn->query($sqlExcelOutput);
foreach ($result2 as $row2) {
    $rowData = [
        $row2['filename'],
        $row2['event_id'],
        $row2['de'],
        $row2['id'],
        $row2['delegate_first_name'],
        $row2['delegate_last_name'],
        $row2['job_title'],
        $row2['job_category'],
        $row2['wish_list'],
        $row2['company_name'],
        $row2['phone_number'],
        $row2['mobile_number'],
        $row2['email'],
        $row2['alternative_email'],
        $row2['street_address'],
        $row2['city'],
        $row2['postal_code'],
        $row2['commission'],
        $row2['com_req_level'],
        $row2['Mismatch'],
        $row2['EVENT_TITLE'],
        $row2['EVENT_DATE'],
    ];

    $sheet->fromArray([$rowData], null, 'A' . $rowNumber);
    $rowNumber++;
}

// Define a named range for the data
$spreadsheet->addNamedRange(
    new \PhpOffice\PhpSpreadsheet\NamedRange('DataRange', $sheet, 'A1:T' . ($rowNumber - 1))
);

// Save the Excel file
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$filenameOpp = 'output/ForecastCommission' . date('Ymd') . '.xlsx';
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
$writer->save($filenameOpp);

$sendate = date('Y.m.d');
$email_send = new \SendGrid\Mail\Mail();

$subject = "Forecast Commission Report - " . $sendate;
$ctoremail = "abquadir@gmail.com";
$ccEmail = "quadir@emrmarketing.in"; 
$email_send->setFrom("quadir@opengovasia.com", "Forecast Report");
$email_send->setSubject($subject);
$email_send->addTo($ctoremail, "Quadir");
$email_send->addCc($ccEmail, "Quadir2"); 
$msg = "<p>Please find the report attached.</p><p><a href='".$domain_url."/autofileuploading/".$filenameOpp."'>Download Report</a></p>";
$email_send->addContent("text/html", $msg);
$sendgrid = new \SendGrid('');

try {
    $response = $sendgrid->send($email_send);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
} catch (Exception $e) {
    echo 'Caught exception: '. $e->getMessage() ."\n";
}
exit;

?>

