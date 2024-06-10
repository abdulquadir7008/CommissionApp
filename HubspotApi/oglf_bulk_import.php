<?php
session_start();
ob_start();

$ses = session_id();
include '../includes/dbcon.php';

// Hubspot Api Start

include('vendor/autoload.php');
use HubSpot\Factory;
use HubSpot\Client\Crm\Contacts\ApiException;
use HubSpot\Client\Crm\Contacts\Model\Filter;
use HubSpot\Client\Crm\Contacts\Model\FilterGroup;
use HubSpot\Client\Crm\Contacts\Model\PublicObjectSearchRequest;
$client = Factory::createWithAccessToken($Hub_token_id);

if(isset($_REQUEST['save_excel_data'])){
	$event_id = $_REQUEST[ 'event_id' ];
	$event_detail_qr = mysqli_query( $conn, "select * from cmf_event WHERE ID='$event_id'" );
	$event_single_fetch = mysqli_fetch_array( $event_detail_qr );
	$propertyOne = $event_single_fetch['ContactProperty'];
	$propertyTwo = $event_single_fetch['ContactPropertyValue'];
	
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
try {
    $response = $client->crm()->contacts()->searchApi()->doSearch($publicObjectSearchRequest);
    $data = json_decode($response, true);
$total = $data['total'];
$results = $data['results'];

//comission app code start
	
if ( $_SESSION[ 'id' ] ) {
  $id = $_SESSION[ 'id' ];
  $login_sql = mysqli_query( $conn, "select * from qr_users WHERE id=$id and status='1'" );
  $login_access = mysqli_fetch_array( $login_sql );

} else {
  echo '<script type="text/javascript">
           window.location = "' . $domain_url . 'index.php"
     </script>';
  unset( $_SESSION[ 'id' ] );
}


if ( isset( $_REQUEST[ 'status' ] ) == $ses ) {

  if ( $results ) {
    mysqli_query( $conn, "delete from cmf_de_commission WHERE ev_id=$event_id" );
    mysqli_query( $conn, "delete from cmf_hubspot WHERE event_id=$event_id" );
    $query4008 = "INSERT INTO cmf_de_commission (attend_id,ev_id) SELECT id,event_id FROM cm_events_confirmed_delegates WHERE event_id=$event_id";
    mysqli_query( $conn, $query4008 );


//      foreach ( $data as $row ) {
	  foreach ($results as $index => $row) {
       $firstname1 = mysqli_real_escape_string( $conn, $row['properties'][ 'firstname' ] );
        $lastname1 = mysqli_real_escape_string( $conn, $row['properties'][ 'lastname' ] );
        $phone = mysqli_real_escape_string( $conn, $row['properties'][ 'phone' ] );
        $email = mysqli_real_escape_string( $conn, $row['properties'][ 'email' ] );
//        $invited = $row[ '5' ];
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
          $query0001 = "insert into cmf_hubspot(firstname,lastname,email,de,job_title,company_name,event_id,stret_address,city,country,postalcode,mobilenumber,did)values('$firstname1','$lastname1','$email','$delegate','$job_title','$company','$event_id','$stret_address','$city','$country','$postalcode','$mobileNumber','$phone')";
          $result7008 =mysqli_query( $conn, $query0001 );
			if (!$result7008) {
    			die('Error: ' . mysqli_error($conn));
			}
				


			
		$event_data_check = mysqli_query( $conn, "select * from cm_events_confirmed_delegates WHERE  email='$email' AND event_id=$event_id" );
			
			
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
          //				echo $count;
        }

        $count++;
      }
    }
  }
	
// End	
	
} catch (ApiException $e) {
    // Handle API exception
    echo "Exception when calling search_api->do_search: ", $e->getMessage();
}
//End
  header( 'Location: ../forcast_dashboard.php' );
  ob_end_flush();
}
?>