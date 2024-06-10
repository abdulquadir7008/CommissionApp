<?php
include '../includes/dbcon.php';
require '../compose_xl/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$folderPath = 'myoglf/';
$files = glob($folderPath . '*.xlsx');

foreach ($files as $file) {
    // Skip hidden or temporary files
    if (basename($file)[0] === '~') {
        continue;
    }

    try {
        $objPHPExcel = IOFactory::load($file);
        $filename = basename($file, '.xlsx');

        $sqlcfmEvent = mysqli_query($conn, "SELECT * FROM cmf_event WHERE upload_file_name='$filename'");
        $listcfmEvent = mysqli_fetch_array($sqlcfmEvent);

        if ($listcfmEvent['upload_file_name'] == $filename) {
            $eventId = $listcfmEvent['ID'];
			$eventCode = $listcfmEvent['EVENT_CODE'];
            
            $sheet = $objPHPExcel->getActiveSheet();
            $highestRow = $sheet->getHighestDataRow();
            $highestColumn = $sheet->getHighestDataColumn();

            // Iterate through each row of the Excel file
            for ($row = 5; $row <= $highestRow; $row++) {
                // Get the row data as an array
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false)[0];

                // Sanitize data for insertion
                $sanitizedData = array_map(function ($value) use ($conn) {
                    return mysqli_real_escape_string($conn, $value);
                }, $rowData);
				
				
				if($eventCode=='OGLF'){
                if ($sanitizedData[27] && $sanitizedData[7]) {
                    $query = "INSERT INTO cm_events_confirmed_delegates (
                        no, de, sector, job_category, session, agency_count, wish_list, hubspot_updated, 
                        date_of_registration, registration_model, wishList_title, reminder_call, reminder_call_pic, 
                        roe, pollingg_device, qr_code, lanyard, grouping, badge_name, delegate_first_name, 
                        delegate_last_name, job_title, company_name, phone_number, mobile_number, email, 
                        alternative_email, data_remarks, street_address, city, postal_code, country_region, 
                        vaccinated, dietary_requirment, parking_coupons_required, uploading_date, filename, event_id
                    ) VALUES (
                        '$sanitizedData[0]', '$sanitizedData[1]', '$sanitizedData[6]', '$sanitizedData[7]', '', 
                        '$sanitizedData[12]', '$sanitizedData[3]', '$sanitizedData[2]', '$sanitizedData[10]', 
                        '$sanitizedData[9]', '', '$sanitizedData[13]', '$sanitizedData[15]', '', 
                        '$sanitizedData[24]', '$sanitizedData[25]', '$sanitizedData[23]', '', '$sanitizedData[27]', 
                        '$sanitizedData[28]', '$sanitizedData[29]', '$sanitizedData[30]', '$sanitizedData[31]', 
                        '$sanitizedData[32]', '$sanitizedData[33]', '$sanitizedData[34]', '$sanitizedData[35]', 
                        '$sanitizedData[36]', '$sanitizedData[37]', '$sanitizedData[38]', '$sanitizedData[39]', 
                        '$sanitizedData[40]', '', '', '$sanitizedData[41]', NOW(), '$filename', '$eventId'
                    )";

                    $result = mysqli_query($conn, $query);
                }
			}
				
				else{
					
					if($sanitizedData[26] && $sanitizedData[32]){	
$query = "insert into cm_events_confirmed_delegates(no,de,sector,job_category,session,agency_count,wish_list,hubspot_updated,date_of_registration,registration_model,wishList_title,reminder_call,reminder_call_pic,roe,pollingg_device,qr_code,lanyard,grouping,badge_name,delegate_first_name,delegate_last_name,job_title,company_name,phone_number,mobile_number,email,alternative_email,data_remarks,street_address,city,postal_code,country_region,vaccinated,dietary_requirment,parking_coupons_required,uploading_date,filename,event_id)values('$sanitizedData[0]','$sanitizedData[1]','$sanitizedData[6]','$sanitizedData[7]','','$sanitizedData[12]','$sanitizedData[3]','$sanitizedData[2]','$sanitizedData[10]','$sanitizedData[9]','','$sanitizedData[13]','$sanitizedData[15]','','$sanitizedData[24]','$sanitizedData[25]','$sanitizedData[23]','','$sanitizedData[26]','$sanitizedData[27]','$sanitizedData[28]','$sanitizedData[29]','$sanitizedData[30]','$sanitizedData[31]','$sanitizedData[32]','$sanitizedData[33]','$sanitizedData[34]','$sanitizedData[35]','$sanitizedData[36]','$sanitizedData[37]','$sanitizedData[38]','$sanitizedData[39]','','','$sanitizedData[40]',now(),'$filename','$eventId')";
$result = mysqli_query($conn, $query);
}
}	
            }
            echo "Successfully File Uploaded";
        }
    } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
        echo "Error loading file: " . $file . " - " . $e->getMessage();
    }
}

?>

