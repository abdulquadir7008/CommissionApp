<?php
session_start();
ob_start();
$ses = session_id();
include 'includes/dbcon.php';

ini_set( 'display_errors', 1 );
ini_set( 'display_startup_errors', 1 );
error_reporting( E_ALL );

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
require 'compose_xl/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if ( isset( $_REQUEST[ 'status' ] ) == $ses ) {

  if ( isset( $_POST[ 'save_excel_data' ] ) ) {
    $event_id = $_REQUEST[ 'event_id' ];
    mysqli_query( $conn, "delete from cm_de_commission WHERE ev_id=$event_id" );
    mysqli_query( $conn, "delete from cm_hubspot WHERE event_id=$event_id" );
    $query4008 = "INSERT INTO cm_de_commission (attend_id,ev_id) SELECT id,event_id FROM qr_attendance_history WHERE event_id=$event_id AND status='YES'";
    mysqli_query( $conn, $query4008 );

    $fileName = $_FILES[ 'import_file' ][ 'name' ];
    $file_ext = pathinfo( $fileName, PATHINFO_EXTENSION );

    $allowed_ext = [ 'xls', 'csv', 'xlsx' ];

    if ( in_array( $file_ext, $allowed_ext ) ) {
      $inputFileNamePath = $_FILES[ 'import_file' ][ 'tmp_name' ];
      $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load( $inputFileNamePath );
      $data = $spreadsheet->getActiveSheet()->toArray();
      $count = "0";


      foreach ( $data as $row ) {
        $firstname1 = mysqli_real_escape_string( $conn, $row[ '1' ] );
        $lastname1 = mysqli_real_escape_string( $conn, $row[ '2' ] );
        $phone = mysqli_real_escape_string( $conn, $row[ '3' ] );
        $email = mysqli_real_escape_string( $conn, $row[ '4' ] );
//        $invited = $row[ '5' ];
        $delegate = mysqli_real_escape_string( $conn, $row[ '5' ] );
        $job_title = mysqli_real_escape_string( $conn, $row[ '6' ] );
        $company = mysqli_real_escape_string( $conn, $row[ '7' ] );
        $stret_address = mysqli_real_escape_string( $conn, $row[ '8' ] );
        $city = mysqli_real_escape_string( $conn, $row[ '9' ] );
        $country = mysqli_real_escape_string( $conn, $row[ '10' ] );
        $postalcode = mysqli_real_escape_string( $conn, $row[ '11' ] );
        $mobileNumber = mysqli_real_escape_string( $conn, $row[ '12' ] );

		/*Start Varibal */
		  
        $rw_firstname = preg_replace( '/[^A-Za-z0-9\-]/', '', $row[ '1' ] );
        $rw_lastname = preg_replace( '/[^A-Za-z0-9\-]/', '', $row[ '2' ] );
        $rw_cleanedid = str_replace( [ ' ', '+' ], '', $row[ '3' ] );
        $rw_company = strtolower( preg_replace( '/[^a-z0-9]+/i', '', $row[ '7' ] ) );
        $rw_street = strtolower( preg_replace( '/[^a-z0-9]+/i', '', $row[ '8' ] ) );
        $rw_city = strtolower( preg_replace( '/[^a-z0-9]+/i', '', $row[ '9' ] ) );
        $rw_country = strtolower( preg_replace( '/[^a-z0-9]+/i', '', $row[ '10' ] ) );
        $rw_postal = strtolower( preg_replace( '/[^a-z0-9]+/i', '', $row[ '11' ] ) );
		$rw_job_title = strtolower( preg_replace( '/[^a-z0-9]+/i', '', $row[ '6' ] ) );
        $rw_cleanedNumber = str_replace( [ ' ', '+' ], '', $row[ '12' ] );
		
		  /*End Varibal */
		  
        if ( $count > 0 ) {
          $query0001 = "insert into cm_hubspot(firstname,lastname,email,de,job_title,company_name,event_id,stret_address,city,country,postalcode,mobilenumber,did)values('$firstname1','$lastname1','$email','$delegate','$job_title','$company','$event_id','$stret_address','$city','$country','$postalcode','$mobileNumber','$phone')";
          $result7008 =mysqli_query( $conn, $query0001 );
			if (!$result7008) {
    			die('Error: ' . mysqli_error($conn));
			}
				

//          $event_data_check = mysqli_query( $conn, "select * from cm_de_commission INNER JOIN qr_attendance_history ON cm_de_commission.attend_id = qr_attendance_history.id WHERE status='YES' and wishlist='yes' AND email='$email' AND event_id=$event_id" );
			
		$event_data_check = mysqli_query( $conn, "select * from qr_attendance_history WHERE status='YES' and wishlist='yes' AND email='$email' AND event_id=$event_id" );
			
			
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
			

            $sql_job_title = mysqli_query( $conn, "select * from cm_level WHERE level_num='$lavel_tag'" );
			$event_list = mysqli_fetch_array( $sql_job_title );


            $email_check_query = mysqli_query( $conn, "select * from cm_de_commission 
					INNER JOIN qr_attendance_history ON cm_de_commission.attend_id = qr_attendance_history.id 
					WHERE email='$email'" );

            $db_firstname = preg_replace( '/[^A-Za-z0-9\-]/', '', $qr_list[ 'firstname' ] );
            $db_lastname = preg_replace( '/[^A-Za-z0-9\-]/', '', $qr_list[ 'lastname' ] );
            $db_cleanedid = str_replace( [ ' ', '+' ], '', $qr_list[ 'did' ] );
			$db_cleanedNumber = str_replace( [ ' ', '+' ], '', $qr_list[ 'mobile' ] );
            $db_company = strtolower( preg_replace( '/[^a-z0-9]+/i', '', $qr_list[ 'org' ] ) );
            $db_street = strtolower( preg_replace( '/[^a-z0-9]+/i', '', $qr_list[ 'street' ] ) );
            $db_city = strtolower( preg_replace( '/[^a-z0-9]+/i', '', $qr_list[ 'city' ] ) );
            $db_country = strtolower( preg_replace( '/[^a-z0-9]+/i', '', $qr_list[ 'country' ] ) );
            $db_postal = strtolower( preg_replace( '/[^a-z0-9]+/i', '', $qr_list[ 'postal_code' ] ) );
			$db_job_title = strtolower( preg_replace( '/[^a-z0-9]+/i', '', $qr_list[ 'job_title' ] ) );
			   
			  
            if(mysqli_num_rows($email_check_query)==2 && $lavel_tag){
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
					$wishlist_category = '';

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
						$com_req_lavel = $event_list['wishlist_2'];
						$wishlist_category = 'wishlist(2)';
					}
						
						
					}
					else if(mysqli_num_rows($email_check_query)==1 && $lavel_tag){
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
						$wishlist_category = '';

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
							$com_req_lavel = $event_list['wishlist_n'];
							$wishlist_category = 'wishlist(n)';
						}
						
						
					}
					else if($lavel_tag){
						
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
						$wishlist_category = '';

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
							$com_req_lavel = '';
							$wishlist_category = 'wishlist(3)';
						}
					}
					else{
						$com_req_lavel = '';
						$wishlist_category = '';
					}
			  
          } else {
            $status = '';
            $com_req_lavel = '';
            $wishlist_category = '';
            $lavel_tag = '';
          }
			if($com_id){
          $query000 = "update cm_de_commission SET de_status='$status',level='$lavel_tag',wishlist_category='$wishlist_category',com_req_level='$com_req_lavel',valid='$invalid2' WHERE attend_id='$com_id'";
          mysqli_query( $conn, $query000 );
			}
          //				echo $count;
        }

        $count++;
      }
    }
  }
  header( 'Location: dashboard.php' );
  ob_end_flush();
}
?>