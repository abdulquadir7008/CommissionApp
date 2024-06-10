<?php
session_start();
ob_start();
$ses = session_id();
include 'includes/dbcon.php';

ini_set( 'display_errors', 1 );
ini_set( 'display_startup_errors', 1 );
error_reporting( E_ALL );
echo "<table border='1' cellpadding='5' width='100%'><tr>
<th>job_category</th>
<th>wish_list</th>
<th>badge_name</th>
<th>job_title</th>
<th>email</th>
<th>delegate_first_name</th>
<th>delegate_last_name</th>
<th>Level</th>
<th>commission</th>
</tr>";

$login_sql = mysqli_query( $conn, "select * from cm_events_confirmed_delegates" );
while($login_access = mysqli_fetch_array( $login_sql )){
	$job_category = strtolower( preg_replace( '/[^a-z0-9]+/i', '', $login_access[ 'job_category' ] ) );
			if($login_access['wish_list']=='yes'){
				$wishtag=$login_access['wish_list'];
			}else{
				$wishtag='no';
			}
			$fetch_job_category_sql = mysqli_query( $conn, "select * from cm_job_title where keyword='$job_category'" );
  			$array_job_category_list = mysqli_fetch_array( $fetch_job_category_sql );
			if(mysqli_num_rows($fetch_job_category_sql)>0){	
			$db_job_cat = $array_job_category_list['keyword'];
				if($job_category == $db_job_cat){
					$lavel_tag = $array_job_category_list['level_id'];
					$sqsm_commsion = mysqli_query( $conn, "select * from cm_wislist WHERE level='$lavel_tag' and wishlist='$wishtag'" );
					$deCommission = mysqli_fetch_array( $sqsm_commsion );
				}
				else{
					$lavel_tag ='';
				}
			}
	else{
		$db_job_cat ='';
	}
	echo "<tr>
<td>".$login_access['job_category']."</td>
<td>".$login_access['wish_list']."</td>
<td>".$login_access['badge_name']."</td>
<td>".$login_access['job_title']."</td>
<td>".$login_access['email']."</td>
<td>".$login_access['delegate_first_name']."</td>
<td>".$login_access['delegate_last_name']."</td>
<td>".$lavel_tag."</td>
<td>".$deCommission['commission']."</td>
</tr>";
}
echo "</table>";
?>