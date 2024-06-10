<?php
include '../includes/dbcon.php';
require '../compose_xl/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$folderPath = 'MYOGLF/';
$files = glob($folderPath . '*.xlsx');

#hubspot api cal start
include('../HubspotApi/vendor/autoload.php');
use HubSpot\Factory;
use HubSpot\Client\Crm\Contacts\ApiException;
use HubSpot\Client\Crm\Contacts\Model\Filter;
use HubSpot\Client\Crm\Contacts\Model\FilterGroup;
use HubSpot\Client\Crm\Contacts\Model\PublicObjectSearchRequest;
$client = Factory::createWithAccessToken($Hub_token_id);
#hubspot api cal End

foreach ($files as $file) {

    $objPHPExcel = IOFactory::load($file);
    $filename = basename($file, '.xlsx');

	$sqlcfmEvent = mysqli_query( $conn, "select * from cmf_event WHERE upload_file_name='$filename'");
  	$listcfmEvent = mysqli_fetch_array( $sqlcfmEvent );
	if($listcfmEvent['upload_file_name']==$filename){
	$eventId = $listcfmEvent['ID'];
	
	#filter hubspot proerty and property value	
	$propertyOne = $listcfmEvent['ContactProperty'];
	$propertyTwo = $listcfmEvent['ContactPropertyValue'];
	$filter1 = new Filter([
		'property_name' => $propertyOne,
		'value' => $propertyTwo,
		'operator' => 'EQ'
	]);
	$filterGroup1 = new FilterGroup([
		'filters' => [$filter1]
	]);
	$publicObjectSearchRequest = new PublicObjectSearchRequest([
		'limit' => 100,
		'properties' => ['firstname','lastname','phone','email','delegate','jobtitle','company','address','city','country','zip','mobilephone','confirmed_events_2024'],
		'filter_groups' => [$filterGroup1],
	]);	
	#filter hubspot proerty and property value End	
		
    $sheet = $objPHPExcel->getActiveSheet();

    $highestRow = $sheet->getHighestDataRow();
    $highestColumn = $sheet->getHighestDataColumn();

    // Iterate through each row of the Excel file
    for ($row = 5; $row <= $highestRow; $row++) {
        // Get the row data as an array
        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false)[0];

        // Assuming your Excel columns are mapped to database columns
        $column1 =mysqli_real_escape_string( $conn, $rowData[ '0' ] );
        $column2 =mysqli_real_escape_string( $conn, $rowData[ '1' ] );
        // Add more columns as needed

        // Sanitize data for insertion
        $sanitizedData = array_map(function ($value) use ($conn) {
            return mysqli_real_escape_string($conn, $value);
        }, $rowData);
		
if($sanitizedData[27] && $sanitizedData[7]){	
$query = "insert into cm_events_confirmed_delegates(no,de,sector,job_category,session,agency_count,wish_list,hubspot_updated,date_of_registration,registration_model,wishList_title,reminder_call,reminder_call_pic,roe,pollingg_device,qr_code,lanyard,grouping,badge_name,delegate_first_name,delegate_last_name,job_title,company_name,phone_number,mobile_number,email,alternative_email,data_remarks,street_address,city,postal_code,country_region,vaccinated,dietary_requirment,parking_coupons_required,uploading_date,filename,event_id)values('$sanitizedData[0]','$sanitizedData[1]','$sanitizedData[6]','$sanitizedData[7]','','$sanitizedData[12]','$sanitizedData[3]','$sanitizedData[2]','$sanitizedData[10]','$sanitizedData[9]','','$sanitizedData[13]','$sanitizedData[15]','','$sanitizedData[24]','$sanitizedData[25]','$sanitizedData[23]','','$sanitizedData[27]','$sanitizedData[28]','$sanitizedData[29]','$sanitizedData[30]','$sanitizedData[31]','$sanitizedData[32]','$sanitizedData[33]','$sanitizedData[34]','$sanitizedData[35]','$sanitizedData[36]','$sanitizedData[37]','$sanitizedData[38]','$sanitizedData[39]','$sanitizedData[40]','','','$sanitizedData[41]',now(),'$filename','$eventId')";
$result = mysqli_query($conn, $query);
}   		
    }
		#Forecast commission Generet start
try {
    $response = $client->crm()->contacts()->searchApi()->doSearch($publicObjectSearchRequest);
    $data = json_decode($response, true);
	$total = $data['total'];
	$results = $data['results'];
	
if ( $results ) {
    mysqli_query( $conn, "delete from cmf_de_commission WHERE ev_id=$eventId" );
    mysqli_query( $conn, "delete from cmf_hubspot WHERE event_id=$eventId" );
    $query4008 = "INSERT INTO cmf_de_commission (attend_id,ev_id) SELECT id,event_id FROM cm_events_confirmed_delegates WHERE event_id=$eventId";
    mysqli_query( $conn, $query4008 );

//      foreach ( $data as $row ) {
	  foreach ($results as $index => $row) {
       $firstname1 = mysqli_real_escape_string( $conn, $row['properties'][ 'firstname' ] );
        $lastname1 = mysqli_real_escape_string( $conn, $row['properties'][ 'lastname' ] );
        $phone = mysqli_real_escape_string( $conn, $row['properties'][ 'phone' ] );
        $email = mysqli_real_escape_string( $conn, $row['properties'][ 'email' ] );
        $delegate = mysqli_real_escape_string( $conn, $row['properties'][ 'delegate' ] );
        $job_title = mysqli_real_escape_string( $conn, $row['properties'][ 'jobtitle' ] );
        $company = mysqli_real_escape_string( $conn, $row['properties'][ 'company' ] );
        $stret_address = mysqli_real_escape_string( $conn, $row['properties'][ 'address' ] );
        $city = mysqli_real_escape_string( $conn, $row['properties'][ 'city' ] );
        $country = mysqli_real_escape_string( $conn, $row['properties'][ 'country' ] );
        $postalcode = mysqli_real_escape_string( $conn, $row['properties'][ 'zip' ] );
        $mobileNumber = mysqli_real_escape_string( $conn, $row['properties'][ 'mobilephone' ] );

        $rw_firstname = preg_replace( '/[^A-Za-z0-9\-]/', '', $row['properties'][ 'firstname' ] );
        $rw_lastname = preg_replace( '/[^A-Za-z0-9\-]/', '', $row['properties'][ 'lastname' ] );
        $rw_cleanedid = str_replace( [ ' ', '+' ], '', $row['properties'][ 'phone' ] );
        $rw_company = strtolower( preg_replace( '/[^a-z0-9]+/i', '', $row['properties'][ 'company' ] ) );
        $rw_street = strtolower( preg_replace( '/[^a-z0-9]+/i', '', $row['properties'][ 'address' ] ) );
        $rw_city = strtolower( preg_replace( '/[^a-z0-9]+/i', '', $row['properties'][ 'city' ] ) );
        $rw_country = strtolower( preg_replace( '/[^a-z0-9]+/i', '', $row['properties'][ 'country' ] ) );
        $rw_postal = strtolower( preg_replace( '/[^a-z0-9]+/i', '', $row['properties'][ 'zip' ] ) );
		$rw_job_title = strtolower( preg_replace( '/[^a-z0-9]+/i', '', $row['properties'][ 'jobtitle' ] ) );
        $rw_cleanedNumber = str_replace( [ ' ', '+' ], '', $row['properties'][ 'mobilephone' ] );

        if ( $count > 0 ) {
          $query0001 = "insert into cmf_hubspot(firstname,lastname,email,de,job_title,company_name,event_id,stret_address,city,country,postalcode,mobilenumber,did)values('$firstname1','$lastname1','$email','$delegate','$job_title','$company','$eventId','$stret_address','$city','$country','$postalcode','$mobileNumber','$phone')";
          $result7008 =mysqli_query( $conn, $query0001 );
			if (!$result7008) {
    			die('Error: ' . mysqli_error($conn));
			}

			$event_data_check = mysqli_query( $conn, "select * from cm_events_confirmed_delegates WHERE  email='$email' AND event_id=$eventId" );
				
          $qr_list = mysqli_fetch_array( $event_data_check );
          $com_id = $qr_list[ 'id' ];
          if ( $qr_list[ 'email' ] ) {
            $status = 'PRESENT';
            $validDate = '';
            $job_category = strtolower( preg_replace( '/[^a-z0-9]+/i', '', $qr_list[ 'job_category' ] ) );
			
			$fetch_job_category_sql = mysqli_query( $conn, "select * from cm_job_title where keyword='$job_category'" );
  			$array_job_category_list = mysqli_fetch_array( $fetch_job_category_sql );
				$db_job_cat = $array_job_category_list['keyword'];
				if($job_category == $db_job_cat){
					$lavel_tag = $array_job_category_list['level_id'];
				}
				else{
					$lavel_tag ='';
				}
			  
			if($qr_list['wish_list']=='yes' || $qr_list['wish_list']=='Y' || $qr_list['wish_list']=='Yes'){
				$wishtag='yes';
			}else{
				$wishtag='no';
			}

            $sql_job_title = mysqli_query( $conn, "select * from cm_wislist WHERE level='$lavel_tag' and wishlist='$wishtag'" );
			$event_list = mysqli_fetch_array( $sql_job_title );

            $email_check_query = mysqli_query( $conn, "select * from cmf_de_commission 
					INNER JOIN cm_events_confirmed_delegates ON cmf_de_commission.attend_id = cm_events_confirmed_delegates.id 
					WHERE email='$email'" );

            $db_firstname = preg_replace( '/[^A-Za-z0-9\-]/', '', $qr_list[ 'delegate_first_name' ] );
            $db_lastname = preg_replace( '/[^A-Za-z0-9\-]/', '', $qr_list[ 'delegate_last_name' ] );
            $db_cleanedid = str_replace( [ ' ', '+' ], '', $qr_list[ 'phone_number' ] );
			$db_cleanedNumber = str_replace( [ ' ', '+' ], '', $qr_list[ 'mobile_number' ] );
            $db_company = strtolower( preg_replace( '/[^a-z0-9]+/i', '', $qr_list[ 'company_name' ] ) );
            $db_street = strtolower( preg_replace( '/[^a-z0-9]+/i', '', $qr_list[ 'street_address' ] ) );
            $db_city = strtolower( preg_replace( '/[^a-z0-9]+/i', '', $qr_list[ 'city' ] ) );
            $db_country = strtolower( preg_replace( '/[^a-z0-9]+/i', '', $qr_list[ 'country_region' ] ) );
            $db_postal = strtolower( preg_replace( '/[^a-z0-9]+/i', '', $qr_list[ 'postal_code' ] ) );
			$db_job_title = strtolower( preg_replace( '/[^a-z0-9]+/i', '', $qr_list[ 'job_title' ] ) );
			    
            if($lavel_tag){
						
				$invalid2 = '';
				$fields = array(
					'Firstname' => $rw_firstname != $db_firstname,
					'Lastname' => $rw_lastname != $db_lastname,
					'DID' => $rw_cleanedid != $db_cleanedid,
					'Designation' => $rw_job_title != $db_job_title,
					'Mobile' => $rw_cleanedNumber != $db_cleanedNumber,
					'Stret Address' => $rw_street != $db_street,
					'City' => $rw_city != $db_city,
					'Postal Code' => $rw_postal != $db_postal,
					'Country' => $rw_country != $db_country,
					'organization' => $rw_company != $db_company
				);

				foreach ($fields as $field => $condition) {
					if ($condition) {
						$invalid2 .= "$field,";
					}
				}
							
					$com_req_lavel = '';

					if ($rw_firstname != $db_firstname ||
						$rw_lastname != $db_lastname ||
						$rw_cleanedid != $db_cleanedid ||
						$rw_job_title != $db_job_title ||
						$rw_cleanedNumber != $db_cleanedNumber ||
						$rw_company != $db_company ||
						$rw_street != $db_street ||
						$rw_city != $db_city ||
						$rw_postal != $db_postal ||
						$rw_country != $db_country) {
						// If any condition is true, set the variables to default values
					} else {
						$com_req_lavel = $event_list['commission'];	
					}		
					}	
					else{
						$com_req_lavel = '';
						$status ='';	
					}
			  
          } else {
            $status = '';
            $com_req_lavel = '';
            $lavel_tag = '';
          }
			if(mysqli_num_rows($event_data_check)>0){
          $query000 = "update cmf_de_commission SET de_status='$status',level='$lavel_tag',com_req_level='$com_req_lavel',valid='$invalid2' WHERE attend_id='$com_id'";
          mysqli_query( $conn, $query000 );
			}
        }

        $count++;
      }
    }
  
// End	
} catch (ApiException $e) {
    echo "Exception when calling search_api->do_search: ", $e->getMessage();
}		
#forecast commission end		
	echo "Successfully File Uploaded";
	}
}

$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$headerRow = ['FILE NAME','EVENT ID','DE', 'ID','FIRST NAME','LAST NAME','JOB TITLE','JOB CATEGORY','WISH LIST', 'COMPANY NAME','PHONE NUMBER','MOBILE NUMBER','EMAIL','ALTERNATIVE EMAIL','STREET ADDRESS','CITY','POSTAL CODE','COMMISSION','COM REQ LEVEL','MISMATCH','EVENT TITLE','EVENT DATE'];
$sheet->fromArray([$headerRow], null, 'A1');
$rowNumber = 2;

$sqlExcelOutput = "SELECT distinct qra.filename As FileName, qra.event_id, qra.de, qra.id, qra.delegate_first_name, qra.delegate_last_name, qra.job_title, qra.job_category, qra.wish_list, qra.company_name, qra.phone_number, qra.mobile_number, qra.email, qra.alternative_email, qra.street_address, qra.city, qra.postal_code, wl.commission, cdc.com_req_level, cdc.valid AS Mismatch, ce.EVENT_TITLE, ce.EVENT_DATE FROM cm_events_confirmed_delegates qra, cm_job_title jd, cm_wislist wl, cmf_de_commission cdc, cmf_event ce where qra.event_id = cdc.ev_id and qra.id = cdc.attend_id and ce.id = qra.event_id and qra.job_category = jd.title and jd.level_id = wl.level GROUP BY `qra`.`id` ORDER BY qra.event_id;";

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

// Save the Excel file
$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$filenameOpp = 'output/ForecastCommission'.date('Ymd').'.xlsx';
$writer->save($filenameOpp);

require '../vendor_email/autoload.php';

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

