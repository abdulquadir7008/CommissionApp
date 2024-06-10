<?php

require '../vendor_email/autoload.php'; // Ensure SendGrid library is loaded

use SendGrid\Mail\Mail;
use SendGrid\Mail\Attachment;

$email_send = new Mail();

$subject = "Forecast Comission Report - " . date('Y.m.d.H:i:s');
$ctoremail = "abquadir@gmail.com";

$email_send->setFrom("quadir@opengovasia.com", "Forecast Report");
$email_send->setSubject($subject);
$email_send->addTo($ctoremail, "Quadir");

$msg = "<p>Please find the report attached.</p><p><a href='http://example.com/report.pdf'>Download Report</a></p>";

$email_send->addContent("text/html", $msg);

// Optional: To include a file attachment, use the following block of code
$file_path = 'output/ForecastCommission20240527123859.xlsx'; // Path to the file you want to attach
$file_encoded = base64_encode(file_get_contents($file_path));
$file_name = basename($file_path);

$attachment = new Attachment();
$attachment->setContent($file_encoded);
$attachment->setType("application/pdf");
$attachment->setFilename($file_name);
$attachment->setDisposition("attachment");
$email_send->addAttachment($attachment);

$sendgrid = new \SendGrid('');

try {
    $response = $sendgrid->send($email_send);
    echo $response->statusCode() . "\n";
    echo json_encode($response->headers(), JSON_PRETTY_PRINT) . "\n";
    echo $response->body() . "\n";
} catch (Exception $e) {
    echo 'Caught exception: '. $e->getMessage() ."\n";
}
?>
