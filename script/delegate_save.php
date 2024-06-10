<?php
session_start();
ob_start();
$ses=session_id();
include '../includes/dbcon.php';
if(isset($_REQUEST['save'])){
	$uniqID =$_REQUEST['unique_id'];
	$event_id = $_REQUEST['event_id'];
	$username = $_REQUEST['username'];
	mysqli_query( $conn, "delete from qr_file_upload WHERE unique_id='$uniqID'" );
	$event_data_check = mysqli_query( $conn, "select * from qr_delegates WHERE unique_id='$uniqID'" );
  	while($event_list = mysqli_fetch_array( $event_data_check )){
		$uniq_list = $event_list['unique_id'];
		mysqli_query( $conn, "delete from qr_delegates WHERE unique_id='$uniq_list'" );
	}
//	mysqli_query($conn,"TRUNCATE TABLE qr_delegates");
					$getdata = $_SESSION['store_data'];
//					print $_SESSION['store_data'];
	
					$count = "0";
					foreach($getdata as $list){
					$notes = mysqli_real_escape_string($conn,$list['1']);
					$onsite_remarks = mysqli_real_escape_string($conn,$list['2']);
					$status = mysqli_real_escape_string($conn,$list['3']);
					$polling_no = mysqli_real_escape_string($conn,$list['4']);
					$grouping = mysqli_real_escape_string($conn,$list['5']);
					$badge_name = mysqli_real_escape_string($conn,$list['6']);
					$org = mysqli_real_escape_string($conn,$list['7']);
					$qrcode = mysqli_real_escape_string($conn,$list['8']);
					$firstname = mysqli_real_escape_string($conn,$list['9']);
					$lastname = mysqli_real_escape_string($conn,$list['10']);
					$job_title = mysqli_real_escape_string($conn,$list['11']);
					$did = mysqli_real_escape_string($conn,$list['12']);
					$mobile = mysqli_real_escape_string($conn,$list['13']);
					$email = mysqli_real_escape_string($conn,$list['14']);
					$alt_email = mysqli_real_escape_string($conn,$list['15']);
					$office_address = mysqli_real_escape_string($conn,$list['16']);
					$sched = mysqli_real_escape_string($conn,$list['17']);
					$vaccinated = mysqli_real_escape_string($conn,$list['18']);
					$diet = mysqli_real_escape_string($conn,$list['19']);
					$lanyard = mysqli_real_escape_string($conn,$list['20']);
					$roe = mysqli_real_escape_string($conn,$list['21']);
					$de = mysqli_real_escape_string($conn,$list['22']);
					$reminder_call1 = mysqli_real_escape_string($conn,$list['23']);
					$reminder_call2 = mysqli_real_escape_string($conn,$list['24']);
					
					if($count > 0){
						
					$studentQuery = "INSERT INTO qr_delegates (notes,onsite_remarks,status,polling_no,grouping,badge_name,org,qrcode,firstname,lastname,job_title,did,mobile,email,alt_email,office_address,sched,vaccinated,diet,lanyard,roe,de,reminder_call1,reminder_call2,create_date,unique_id,event_Id) VALUES ('$notes','$onsite_remarks','$status','$polling_no','$grouping','$badge_name','$org','$qrcode','$firstname','$lastname','$job_title','$did','$mobile','$email','$alt_email','$office_address','$sched','$vaccinated','$diet','$lanyard','$roe','$de','$reminder_call1','$reminder_call2',now(),'$uniqID','$event_id')";
                $result = mysqli_query($conn, $studentQuery);
    			mysqli_query( $conn, "delete from qr_delegates WHERE qrcode=''" );
							$_SESSION['succmessage'] = " Data Save Successfully.";
							unset($_SESSION['store_data']);
						}
	$count++;}
	$update_file_stutas = "INSERT INTO qr_file_upload (event_id,upload_by,unique_id,upload_datetime,approved_date_datetime,status) VALUES ('$event_id','$username','$uniqID',now(),now(),'1')";
                $result = mysqli_query($conn, $update_file_stutas);
	header('Location: ../data_import.php?status='.$ses."&&uniq=".$uniqID);
	ob_end_flush();
				}
?>