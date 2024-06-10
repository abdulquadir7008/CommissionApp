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
   

    $fileName = $_FILES[ 'import_file' ][ 'name' ];
    $file_ext = pathinfo( $fileName, PATHINFO_EXTENSION );

    $allowed_ext = [ 'xls', 'csv', 'xlsx' ];

    if ( in_array( $file_ext, $allowed_ext ) ) {
      $inputFileNamePath = $_FILES[ 'import_file' ][ 'tmp_name' ];
      $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load( $inputFileNamePath );
      $data = $spreadsheet->getActiveSheet()->toArray();
      $count = "0";


      foreach ( $data as $row ) {
        $row1 = mysqli_real_escape_string( $conn, $row[ '0' ] );
        $row2 = mysqli_real_escape_string( $conn, $row[ '1' ] );
		$row3 = mysqli_real_escape_string( $conn, $row[ '2' ] );
		$row4 = mysqli_real_escape_string( $conn, $row[ '3' ] );
        $row7 = mysqli_real_escape_string( $conn, $row[ '6' ] );
        $row8 = mysqli_real_escape_string( $conn, $row[ '7' ] );
		$row10 = mysqli_real_escape_string( $conn, $row[ '9' ] );
		$row11 = mysqli_real_escape_string( $conn, $row[ '10' ] );
        $row13 = mysqli_real_escape_string( $conn, $row[ '12' ] );
		  $row14 = mysqli_real_escape_string( $conn, $row[ '13' ] );
		  $row15 = mysqli_real_escape_string( $conn, $row[ '14' ] );
         $row24 = mysqli_real_escape_string( $conn, $row[ '23' ] );
		  $row25 = mysqli_real_escape_string( $conn, $row[ '24' ] );
		  $row26 = mysqli_real_escape_string( $conn, $row[ '25' ] );
		  $row27 = mysqli_real_escape_string( $conn, $row[ '26' ] );
		  $row28 = mysqli_real_escape_string( $conn, $row[ '27' ] );
		  $row29 = mysqli_real_escape_string( $conn, $row[ '28' ] );
         $row30 = mysqli_real_escape_string( $conn, $row[ '29' ] );
		  $row31 = mysqli_real_escape_string( $conn, $row[ '30' ] );
		  $row32 = mysqli_real_escape_string( $conn, $row[ '31' ] );
		  $row33 = mysqli_real_escape_string( $conn, $row[ '32' ] );
		  $row34 = mysqli_real_escape_string( $conn, $row[ '33' ] );
		  $row35 = mysqli_real_escape_string( $conn, $row[ '34' ] );
		  $row36 = mysqli_real_escape_string( $conn, $row[ '35' ] );
		  $row37 = mysqli_real_escape_string( $conn, $row[ '36' ] );
		  $row38 = mysqli_real_escape_string( $conn, $row[ '37' ] );
		  $row39 = mysqli_real_escape_string( $conn, $row[ '38' ] );
		  $row40 = mysqli_real_escape_string( $conn, $row[ '39' ] );
		  $row41 = mysqli_real_escape_string( $conn, $row[ '40' ] );
		  $row42 = mysqli_real_escape_string( $conn, $row[ '41' ] );
		
		  /*End Varibal */
		  	

        if ( $count > 3 ) {
//          $query0001 = "insert into cm_events_confirmed_delegates(no,de,sector,job_category,session,agency_count,wish_list,hubspot_updated,date_of_registration,registration_model,wishList_title,reminder_call,reminder_call_pic,roe,pollingg_device,qr_code,lanyard,grouping,badge_name,delegate_first_name,delegate_last_name,job_title,company_name,phone_number,mobile_number,email,alternative_email,data_remarks,street_address,city,postal_code,country_region,vaccinated,dietary_requirment,parking_coupons_required,remarks,event_id)values('$row1','$row2','$row7','$row8','','$row13','$row4','$row3','$row11','$row10','','$row14','$row15','','$row25','$row26','$row24','','$row27','$row28','$row29','$row30','$row31','$row32','$row33','$row34','$row35','$row36','$row37','$row38','$row39','$row40','','$row41','$row42','$event_id')";

			
//OGBI Data Insert Process
			 
//$query0001 = "insert into cm_events_confirmed_delegates(no,de,sector,job_category,session,agency_count,wish_list,hubspot_updated,date_of_registration,registration_model,wishList_title,reminder_call,reminder_call_pic,roe,pollingg_device,qr_code,lanyard,grouping,badge_name,delegate_first_name,delegate_last_name,job_title,company_name,phone_number,mobile_number,email,alternative_email,data_remarks,street_address,city,postal_code,country_region,vaccinated,dietary_requirment,parking_coupons_required,event_id,uploading_date)values('$row1','$row2','$row7','$row8','','$row13','$row4','$row3','$row11','$row10','','$row14','$row15','','$row25','$row26','$row24','','$row27','$row28','$row29','$row30','$row31','$row32','$row33','$row34','$row35','$row36','$row37','$row38','$row39','$row40','','','$row41','$event_id',now())";
	
			
//OGLF Data Insert Process
			
$query0001 = "insert into cm_events_confirmed_delegates(no,de,sector,job_category,session,agency_count,wish_list,hubspot_updated,date_of_registration,registration_model,wishList_title,reminder_call,reminder_call_pic,roe,pollingg_device,qr_code,lanyard,grouping,badge_name,delegate_first_name,delegate_last_name,job_title,company_name,phone_number,mobile_number,email,alternative_email,data_remarks,street_address,city,postal_code,country_region,vaccinated,dietary_requirment,parking_coupons_required,event_id,uploading_date)values('$row1','$row2','$row7','$row8','','$row13','$row4','$row3','$row11','$row10','','$row14','$row15','','$row25','$row26','$row24','','$row28','$row29','$row30','$row31','$row32','$row33','$row34','$row35','$row36','$row37','$row38','$row39','$row40','$row41','','','$row41','$event_id',now())";			
			
			
			
          $result7008 =mysqli_query( $conn, $query0001 );
			$user_id=mysqli_insert_id($conn);
			mysqli_query($conn,"delete from cm_events_confirmed_delegates WHERE id=$user_id and badge_name='' and email='' and mobile_number=''");
			
			if (!$result7008) {
    			die('Error: ' . mysqli_error($conn));
			}
          //				echo $count;
        }

        $count++;
      }
    }
  }
  header( 'Location: forcast_dashboard.php' );
  ob_end_flush();
}
?>