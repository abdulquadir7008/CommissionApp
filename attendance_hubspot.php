<?php
session_start();
include 'includes/dbcon.php';
if(isset($_REQUEST['id'])){
	$attendence_id = $_REQUEST['id'];
	$sql_attencde = mysqli_query( $conn, "select * from qr_attendance_history WHERE id=$attendence_id" );
  	$list_attendce = mysqli_fetch_array( $sql_attencde );
	$event_id = $list_attendce['event_id'];
	$email_id = $list_attendce['email'];
	
			$firstname =preg_replace('/[^A-Za-z0-9\-]/', '', $list_attendce['firstname']);
			$lastname =preg_replace('/[^A-Za-z0-9\-]/', '', $list_attendce['lastname']);
			$jobtitle =preg_replace('/[^A-Za-z0-9\-]/', '', $list_attendce['job_title']);
			$cleanedid = str_replace([' ', '+'], '', $list_attendce['did']);		
			$cleanedNumber = str_replace([' ', '+'], '', $list_attendce['mobile']);
			$rw_company = strtolower(preg_replace('/[^a-z0-9]+/i', '',  $list_attendce['org']));
			$rw_street = strtolower(preg_replace('/[^a-z0-9]+/i', '',  $list_attendce['street']));
			$rw_city = strtolower(preg_replace('/[^a-z0-9]+/i', '',  $list_attendce['city']));
			$rw_country = strtolower(preg_replace('/[^a-z0-9]+/i', '',  $list_attendce['country']));
			$rw_postal = strtolower(preg_replace('/[^a-z0-9]+/i', '',  $list_attendce['postal_code']));
	
	$sql_event_core = mysqli_query( $conn, "select * from qr_event WHERE ID='$event_id'" );
  	$list_event_core = mysqli_fetch_array( $sql_event_core );
	
	$sql_cm_hubspot = mysqli_query( $conn, "select * from cm_hubspot WHERE event_id='$event_id' and email='$email_id'" );
  	$list_hubspot = mysqli_fetch_array( $sql_cm_hubspot );
	
			/* Start Variable*/
	
			$firstname1 =preg_replace('/[^A-Za-z0-9\-]/', '', $list_hubspot['firstname']);
			$lastname1 =preg_replace('/[^A-Za-z0-9\-]/', '', $list_hubspot['lastname']);
			$cleanedid1 = str_replace([' ', '+'], '', $list_hubspot['did']);		
			$cleanedNumber1 = str_replace([' ', '+'], '', $list_hubspot['mobilenumber']);
			$rw_company1 = strtolower(preg_replace('/[^a-z0-9]+/i', '',  $list_hubspot['company_name']));
			$rw_street1 = strtolower(preg_replace('/[^a-z0-9]+/i', '',  $list_hubspot['stret_address']));
			$rw_city1 = strtolower(preg_replace('/[^a-z0-9]+/i', '',  $list_hubspot['city']));
			$rw_country1 = strtolower(preg_replace('/[^a-z0-9]+/i', '',  $list_hubspot['country']));
			$rw_postal1 = strtolower(preg_replace('/[^a-z0-9]+/i', '',  $list_hubspot['postalcode']));
			$jobtitle1 =preg_replace('/[^A-Za-z0-9\-]/', '', $list_hubspot['job_title']);
			
			/* End Variable*/
}

?>
	

	<div class="modal-content">
		
        <div class="modal-header" style="background: #fff;">
			<h4><?php echo $list_event_core['EVENT_TITLE'].":".$list_event_core['EVENT_VENUE'];?> | Date: <?php echo $list_event_core['EVENT_DATE'];?></h4>
			
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        <div class="modal-body">
			<div class="ajax-loader">
		<img src="Spin-1s-200px.gif">
		</div>
          <div class="show-detail">
					  
					 <?php if(mysqli_num_rows($sql_cm_hubspot) > 0){?>
				  	<table width="800px">
					  <thead>
						  <tr>
						 <th></th>
						  <th>Field</th>
						  <th>Attendance</th>
						  <th>HubSpot</th>
							  </tr>
						
						<tbody>
							<tr>
								<td></td>
								<td class="rick">First Name</td>
								<td><?php echo $list_attendce['firstname'];?></td>
								<td <?php if($firstname!=$firstname1){?> style="color: red"<?php } ?> ><?php echo $list_hubspot['firstname'];?></td>
							</tr>
							<tr>
								<td></td>
								<td class="rick">Last Name</td>
								<td><?php echo $list_attendce['lastname'];?></td>
								<td <?php if($lastname!=$lastname1){?> style="color: red"<?php }?>><?php echo $list_hubspot['lastname'];?></td>
							</tr>
							<tr>
								<td></td>
								<td class="rick">did</td>
								<td><?php echo $list_attendce['did'];?></td>
								<td <?php if($cleanedid!=$cleanedid1){?> style="color: red"<?php }?>><?php echo $list_hubspot['did'];?></td>
							</tr>
							<tr>
								<td></td>
								<td class="rick">Email</td>
								<td><?php echo $list_attendce['email'];?></td>
								<td><?php echo $list_hubspot['email'];?></td>
							</tr>
							<tr>
								<td></td>
								<td class="rick">Designation</td>
								<td><?php echo $list_attendce['job_title'];?></td>
								<td <?php if($jobtitle!=$jobtitle1){?> style="color: red"<?php }?>><?php echo $list_hubspot['job_title'];?></td>
							</tr>
						
							<tr>
								<td></td>
								<td class="rick">Mobile</td>
								<td><?php echo $list_attendce['mobile'];?></td>
								<td <?php if($cleanedNumber!=$cleanedNumber1){?> style="color: red"<?php }?>><?php echo $list_hubspot['mobilenumber'];?></td>
							</tr>
							<tr>
								<td></td>
								<td class="rick">organization</td>
								<td><?php echo $list_attendce['org'];?></td>
								<td <?php if($rw_company!=$rw_company1){?> style="color: red"<?php }?>><?php echo $list_hubspot['company_name'];?></td>
							</tr>
							<tr>
								<td></td>
								<td class="rick">Street Address</td>
								<td><?php echo $list_attendce['street'];?></td>
								<td <?php if($rw_street!=$rw_street1){?> style="color: red"<?php }?>><?php echo $list_hubspot['stret_address'];?></td>
							</tr>
							<tr>
								<td></td>
								<td class="rick">City</td>
								<td><?php echo $list_attendce['city'];?></td>
								<td <?php if($rw_city!=$rw_city1){?> style="color: red"<?php }?>><?php echo $list_hubspot['city'];?></td>
							</tr>
							<tr>
								<td></td>
								<td class="rick">Postal Code</td>
								<td><?php echo $list_attendce['postal_code'];?></td>
								<td <?php if($rw_postal!=$rw_postal1){?> style="color: red"<?php }?>><?php echo $list_hubspot['postalcode'];?></td>
							</tr>
							
							<tr>
								<td></td>
								<td class="rick">Country</td>
								<td><?php echo $list_attendce['country'];?></td>
								<td <?php if($rw_country!=$rw_country1){?> style="color: red"<?php }?>><?php echo $list_hubspot['country'];?></td>
							</tr>
							
							<tr>
								<td></td>
								<td class="rick">Job Category</td>
								<td><strong><?php if($list_attendce['job_category']){echo $list_attendce['job_category'];}else{ echo "<span style='color:red'>Job Category not found in Attendance list.</span>";}?></strong></td>
								
							</tr>
						</tbody>

					  </table>
					  <?php } else{echo "<span style='color:red; font-weight:bold'>Cannot match other due to email not matching</span>";} ?>
				  </div>
        </div>
        
      </div>
