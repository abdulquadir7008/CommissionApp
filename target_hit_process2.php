	<?php
session_start();
include 'includes/dbcon.php';
if(isset($_REQUEST['id'])){
	$explodedArray = explode(',', $_REQUEST['id']);
	$firstValue = $explodedArray[0];
	$secondValue = $explodedArray[1];
	if(isset($explodedArray[2]) && $explodedArray[2]=='checked'){
		mysqli_query($conn,"delete from cmf_target_hit WHERE eventId=$secondValue and de='$firstValue'");	
	}
	else{
	$query0001 = "insert into cmf_target_hit(eventId,de,Commission)values('$secondValue','$firstValue','50')";
    $result7008 =mysqli_query( $conn, $query0001 );	
	}
	

	
	if(isset($_SESSION['event_session']) && isset($_SESSION['de_session'])){
		$eventDes = $_SESSION['event_session'];
		$deerm = $_SESSION['de_session'];
		$search_sec = "WHERE event_id='$eventDes' AND de='$deerm'";
	}
	else if(isset($_SESSION['event_session'])){
		$eventDes = $_SESSION['event_session'];
		$search_sec = "WHERE event_id='$eventDes'";
	}
	else if(isset($_SESSION['de_session'])){
		$deerm = $_SESSION['de_session'];
		$search_sec = "WHERE de='$deerm'";
	}
	else{
		$search_sec= '';
	}
?>
<h4>Show Total.</h4>
                  <table>
                      <thead>
                      <th></th>
                        <th>EVENT</th>
                        <th>DE</th>
                        <th>DELEGATES</th>
                        <th>MATCH</th>
                        <th>MISSING</th>
                        <th>Total Commission</th>
                        <th>Target Hit Commission</th>
                        <th>Total</th>
                        <th>Target Hit</th>
                        </thead>
                        <?php
                        $total_num_miss = '0';
                        $total_present_data = '0';
                        $total_num_del = '0';
                        $totalhit = '0';
                        $floorTtoal = '0';
	$floortotalicn='0';
//                        $total_delegate = mysqli_query( $conn, "select DISTINCT de,ev_id from cmf_de_commission 
//						INNER JOIN cm_events_confirmed_delegates ON cmf_de_commission.attend_id = cm_events_confirmed_delegates.id
//						WHERE $search_sec status='YES'" );
						
						 $total_delegate = mysqli_query( $conn, "select DISTINCT de,ev_id from cmf_de_commission 
						INNER JOIN cm_events_confirmed_delegates ON cmf_de_commission.attend_id = cm_events_confirmed_delegates.id  $search_sec" );
						
                        while ( $list_delegate = mysqli_fetch_array( $total_delegate ) ) {
                          $same_de = $list_delegate[ 'de' ];
                          $evid = $list_delegate[ 'ev_id' ];

                          $numbof_del = mysqli_query( $conn, "select * from cmf_de_commission
						INNER JOIN cm_events_confirmed_delegates ON cmf_de_commission.attend_id = cm_events_confirmed_delegates.id
						WHERE de='$same_de' and ev_id='$evid'" );

                          $evid_del = mysqli_query( $conn, "select * from cmf_de_commission
						INNER JOIN cm_events_confirmed_delegates ON cmf_de_commission.attend_id = cm_events_confirmed_delegates.id
						WHERE ev_id='$evid' and de='$same_de' AND de_status='PRESENT' AND level != '' and valid=''" );

                          $eventDetail = mysqli_query( $conn, "select * from cmf_event WHERE id='$evid'" );
                          $eventDetailsRow = mysqli_fetch_array( $eventDetail );
                          $totalNet = '0';
                          while ( $list_delegate20 = mysqli_fetch_array( $numbof_del ) ) {
                            if ( $list_delegate20[ 'com_req_level' ] ) {
                              $totalNet = ( $totalNet + $list_delegate20[ 'com_req_level' ] );
                            }
                          }
                          $sql_target_hit = mysqli_query( $conn, "select * from cmf_target_hit WHERE eventId='$evid' and de='$same_de'" );
                          $list_target_hit = mysqli_fetch_array( $sql_target_hit );
                          ?>
                      <tr>
                        <td></td>
                        <td><?php echo $eventDetailsRow['EVENT_TITLE'];?></td>
                        <td><?php echo $list_delegate['de'];?></td>
                        <td><?php echo $num_del = mysqli_num_rows ( $numbof_del );
                        $total_num_del = $total_num_del + $num_del;
                        ?></td>
                        <td><?php echo $present_data = mysqli_num_rows ( $evid_del );
                        $total_present_data = $total_present_data + $present_data;
                        ?></td>
                        <td><?php echo $nomis = ( mysqli_num_rows( $numbof_del ) - mysqli_num_rows( $evid_del ) );
                        $total_num_miss = $total_num_miss + $nomis;
                        ?></td>
                        <td><?php echo $totalNet; ?></td>
                        <td><?php if($list_target_hit){echo $hitCom = $list_target_hit['Commission'];}else{$hitCom='0';}?></td>
                        <td><?php echo $subtotalcom = $totalNet+$hitCom; ?></td>
                        <td><div class="checkbox-btn checkbox-btn--rounded">
                            <?php
                            if ( $list_target_hit ) {
                              $genLink = $list_delegate[ 'de' ] . "," . $evid . ",checked";
                            } else {
                              $genLink = $list_delegate[ 'de' ] . "," . $evid;
                            }
                            ?>
                            <input type="checkbox" id="<?php echo $genLink;?>" class="checkbox target_hit" <?php if($list_target_hit){?>checked<?php } ?>>
                            <div class="toggler" data-label-checked="Yes" data-label-unchecked="No"></div>
                          </div></td>
                      </tr>
                      <?php
                      $totalhit += intval($hitCom);
                      $floorTtoal += intval($subtotalcom);
							$floortotalicn += intval($totalNet);
                      }
                      ?>
                      <thead>
                      <th></th>
                        <th>Total:</th>
                        <th></th>
                        <th><?php echo $total_num_del;?></th>
                        <th><?php echo $total_present_data;?></th>
                        <th><?php echo $total_num_miss;?></th>
                        <th> <span> $<?php echo $floortotalicn;?></span></th>
                        <th>$<?php echo $totalhit;?> </th>
                        <th>$<?php echo $floorTtoal;?></th>
                        <th></th>
                        </thead>
                    </table>


<?php }

?>
<script>
$('.target_hit').click(function(){
		var id = $(this).attr("id");
		//var priceid = $('#priceid' + id + '').val();
		$('.ajax-loader').show();
		
		$.ajax({
			url : "target_hit_process2.php",
			method:"post",
			datatype:"text",
			data:{id:id},
			success:function(msg){
//        $('#countcart').html(msg);
        $('#target_hit_content').html(msg);
				$('.ajax-loader').hide();
				
			}
		});
	});	

</script>	