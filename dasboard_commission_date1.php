<?php
ob_start();
include 'includes/dbcon.php';


if(isset($_REQUEST['report_filter'])){
	echo $start_date = $_REQUEST['start_date'];
	
	echo $end_date = $_REQUEST['end_date'];
}
?>


<table>
<tr>
	<td>DE</td>
	<td>COMMISSION</td>
	<td>DE STATUS</td>
	<td>BADGE NAME</td>
	<td>ORG</td>
	<td>JOB TITLE</td>
	<td>JOB CATEGORY</td>
	<td>EVENT TITLE</td>
	<td>EVENT DATE</td>
	<td>EVENT ID</td>
	<td>ATTENDACE ID</td>
	<td>LAVEL</td>
	</tr>


<?php
//$sql2 = "SELECT * FROM cm_hubspot INNER JOIN qr_attendance_history";
echo $sql2 = "SELECT b.de, a.com_req_level commission, a.de_status, b.badge_name, b.org, b.job_title, b.job_category, c.EVENT_TITLE, c.EVENT_DATE, a.ev_id, a.attend_id, a.level
FROM cm_de_commission a,
qr_attendance_history b,
qr_event c
WHERE com_req_level > 0
AND a.attend_id = b.id
AND c.ID = b.event_id
AND b.event_id = a.ev_id
AND c.EVENT_DATE  BETWEEN '$start_date' AND '$end_date' ORDER BY b.de, c.EVENT_DATE, b.org";
//FROM cm_hubspot INNER JOIN qr_attendance_history ON cm_hubspot.email = qr_attendance_history.email $search";
	$eventDetail = mysqli_query( $conn, $sql2 );
while ( $row2 = mysqli_fetch_array( $eventDetail ) ) {
	?>
	<tr>
		<td><?php echo $row2['de'];?></td>
		<td><?php echo $row2['commission'];?></td>
		<td><?php echo $row2['de_status'];?></td>
		<td><?php echo $row2['badge_name'];?></td>
		<td><?php echo $row2['job_title'];?></td>
		<td><?php echo $row2['job_category'];?></td>
		<td><?php echo $row2['EVENT_TITLE'];?></td>
		<td><?php echo $row2['EVENT_DATE'];?></td>
		<td><?php echo $row2['ev_id'];?></td>
		<td><?php echo $row2['attend_id'];?></td>
		<td><?php echo $row2['level'];?></td>
	</tr>
	<?php } ?>

</table>