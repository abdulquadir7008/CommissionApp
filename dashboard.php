<?php
session_start();
include 'includes/dbcon.php';
include 'includes/encrypt_func.php';
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
if ( !empty( $_REQUEST[ 'in_date' ] ) && !empty( $_REQUEST[ 'out_date' ] ) && !empty( $_REQUEST[ 'de' ] ) ) {
  $indate = date( 'Y-m-d', strtotime( $_REQUEST[ 'in_date' ] ) );
  $out_date = date( 'Y-m-d', strtotime( $_REQUEST[ 'out_date' ] ) );
  $deRum = $_REQUEST[ 'de' ];
  $search = "AND de='$deRum' AND DATE(create_date) BETWEEN STR_TO_DATE('$indate', '%Y-%m-%d') AND STR_TO_DATE('$out_date', '%Y-%m-%d')";
  $search_sec = "de='$deRum' AND DATE(create_date) BETWEEN STR_TO_DATE('$indate', '%Y-%m-%d') AND STR_TO_DATE('$out_date', '%Y-%m-%d') AND";
} else if ( !empty( $_REQUEST[ 'in_date' ] ) && !empty( $_REQUEST[ 'out_date' ] ) ) {
  $indate = date( 'd-m-Y', strtotime( $_REQUEST[ 'in_date' ] ) );
  $out_date = date( 'd-m-Y', strtotime( $_REQUEST[ 'out_date' ] ) );
  $search = "AND DATE(create_date) BETWEEN STR_TO_DATE('$indate', '%d-%m-%Y') AND STR_TO_DATE('$out_date', '%d-%m-%Y')";
  $search_sec = "DATE(create_date) BETWEEN STR_TO_DATE('$indate', '%Y-%m-%d') AND STR_TO_DATE('$out_date', '%Y-%m-%d') AND";
} else if ( !empty( $_REQUEST[ 'event' ] ) && !empty( $_REQUEST[ 'de' ] ) ) {
  $_SESSION['event_session'] = $event = $_REQUEST[ 'event' ];
  $_SESSION['de_session'] = $deRum = $_REQUEST[ 'de' ];
	$key = "16_byte_secret_key";
	$event = decrypt_id($event, $key);
  $search = "AND event_id='$event' AND de='$deRum'";
  $search_sec = "event_id='$event' AND de='$deRum' AND";
} else if ( !empty( $_REQUEST[ 'event' ] ) ) {
  $_SESSION['event_session'] = $event = $_REQUEST[ 'event' ];
	unset($_SESSION['de_session']);
	$key = "16_byte_secret_key";
	$event = decrypt_id($event, $key);	
  $search = "AND event_id='$event' ";
  $search_sec = "event_id='$event' AND";
} else if ( !empty( $_REQUEST[ 'de' ] ) ) {
  	$de = $_REQUEST[ 'de' ];
	$_SESSION['de_session']=$de;
	unset($_SESSION['event_session']);
  $search = "AND de='$de' ";
  $search_sec = "de='$de' and";
} else {
  $search = '';
  $search_sec = '';
  $DateSearch = '';
	unset($_SESSION['de_session']);
	unset($_SESSION['event_session']);
}
?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
<?php include 'includes/head.php';?>

<body>
<div class="preloader">
  <div class="lds-ripple">
    <div class="lds-pos"></div>
    <div class="lds-pos"></div>
  </div>
</div>
<div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
  <header class="topbar" data-navbarbg="skin6">
    <nav class="navbar top-navbar navbar-expand-md">
      <div class="navbar-header" data-logobg="skin6"> 
        <!-- This is for the sidebar toggle which is visible on mobile only change --> 
        <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                            class="ti-menu ti-close"></i></a>
        <center>
          <a href="dashboard.php"><img src="assets/images/opengov-logo.jpg" style="max-width: 100%; height: auto;margin-left: -10px;" alt="homepage" class="dark-logo" /></a>
        </center>
        <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
                        data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i
                            class="ti-more"></i></a> </div>
      <div class="navbar-collapse collapse float-left mr-auto" id="navbarSupportedContent">
        <ul class="navbar-nav float-left mr-auto">
          <div class="page-breadcrumb">
            <div class="row">
              <div class="col-12 align-self-left">
                <h5 class="page-title text-truncate text-dark font-weight-medium mb-1" title="Commission Calculation"> Commission Calculation </h5>
              </div>
            </div>
          </div>
        </ul>
        <ul class="navbar-nav float-right">
          <li class="nav-item dropdown"> <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false"> <img src="assets/images/admin.jpg" alt="user" class="rounded-circle"
                                    width="40"> <span class="ml-2 d-none d-lg-inline-block"><span>Hello,</span> <span
                                        class="text-dark"><?php echo $login_access['fullname'];?></span> <i data-feather="chevron-down"
                                        class="svg-icon"></i></span> </a>
            <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
              <div class="pl-4 p-3"><a class="btn btn-sm btn-info" data-toggle="modal"
                                        data-target="#logout-modal" style="color:#fff;"><i data-feather="power"
                                        class="svg-icon mr-2 ml-1"></i> Logout</a></div>
            </div>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <?php
  include( 'logout-modal.php' );
  include( 'includes/sidebar.php' );

  ?>
  <div class="page-wrapper">
    <div class="container-fluid">
      <div class="filter_form">
        <div class="row">
          <div class="col-md-11">
            <form action="dashboard.php" method="get">
              <h4>Filter</h4>
              <div class="row"> 
                <!--
								<div class="col-md-2">
									<label>Full Name</label>
									<input type="text" placeholder="FULL NAME" name="de_name" id="txtCountry" class="form-control typeahead">
								</div>
-->
                
                <div class="decom_dh">
                  <label>DE</label>
                  <input type="text" placeholder="DE" name="de" id="txtDelegetas" class="form-control typeahead">
                </div>
                <div class="decom_dh2">
                  <label>EVENT</label>
                  <select class="form-control eventdh" name="event">
                    <option value="">Select Event</option>
                    <?php
                    $QrEventData = mysqli_query( $conn, "select * from qr_event WHERE STATUS='Completed' order by EVENT_TITLE ASC" );
                    while ( $RowQrEvent = mysqli_fetch_array( $QrEventData ) ) {
						$dateFormatChange = strtotime($RowQrEvent['EVENT_DATE']);
						$SGdate = date("d-m-Y",$dateFormatChange);							
					    $id_to_encrypt1 = $RowQrEvent['ID'];
						$key = "16_byte_secret_key"; // AES requires a 16-byte key
						$encrypted_id = encrypt_id($id_to_encrypt1, $key);
                        
                      ?>
                    <option value="<?php echo $encrypted_id;?>"><?php echo $RowQrEvent['EVENT_TITLE']." - (".$SGdate.")";?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-md-4">
                  <label>DATE</label>
                  <div class="row" style="margin-right:0;">
                    <div class="decom_dh3">
                      <input type="date" name="in_date" class="form-control">
                    </div>
                    <div class="decom_dh3">
                      <input type="date" name="out_date" class="form-control">
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <button name="search" type="submit">Search</button>
                  <?php if(!empty($_REQUEST['de_name']) || !empty($_REQUEST['event']) || !empty($_REQUEST['de']) || !empty($_REQUEST['in_date']) || !empty($_REQUEST['out_date'])){?>
                  <a href="dashboard.php" class="btn btn-dark">RESET</a>
                  <?php } ?>
                </div>
              </div>
            </form>
          </div>
          
        </div>
      </div>
      <div class="row" id="tblattendance">
        <div class="col-12" style="position:static">
          <div class="card" style="position:static">
            <div class="card-body">
				
				<div class="decom_dh4">		 
			  	<div class="decom_dh5">
					<form action="dasboard_commission_date.php" method="post">
              <?php
              if ( isset( $_REQUEST[ 'de' ] ) ) {
				 echo "<input type='hidden' name='de_request' value='".$_REQUEST[ 'de' ]."'>"; 
              }
				if ( isset( $_REQUEST[ 'event' ] ) ) {
				 echo "<input type='hidden' name='event_request' value='".$_REQUEST[ 'event' ]."'>"; 
              }
              ?>
				
              <button type="button" data-toggle="modal" data-target="#form" class="btn btn-orange" style="float: right; background-color: #0b4596; font-size: 11px; margin-top: 10px; padding: 4px 6px; line-height: 1.1; border: 0; border-radius: 5px; color: #fff;"><span style="font-size: 15px;font-weight: bold;">Download</span><br>Commission Report</button>
            </form>
				 </div>
				 <div class="decom_dh5">
					 <form action="dasboard_date.php" method="post">
              <?php
              if ( isset( $_REQUEST[ 'de' ] ) ) {
				 echo "<input type='hidden' name='de_request' value='".$_REQUEST[ 'de' ]."'>"; 
              }
				if ( isset( $_REQUEST[ 'event' ] ) ) {
				 echo "<input type='hidden' name='event_request' value='".$_REQUEST[ 'event' ]."'>"; 
              }
              ?>
				
              <button type="submit" class="btn btn-orange" style="float: right; background-color: #fb8c00; font-size: 12px; margin-top: 10px; padding: 4px 6px; line-height: 1.1;float: left; border-radius: 5px;"><span style="font-size: 15px; font-weight: bold">Download</span><br>Mismatch Report</button>
            </form>
				 </div>
          </div>
              <?php
              if ( isset( $_SESSION[ 'message' ] ) ) {
                echo "<h4>" . $_SESSION[ 'message' ] . "</h4>";
                unset( $_SESSION[ 'message' ] );
              }

              ?>
              <div class="table-responsive">
                <input type="text" id="search-input" class="form-control" placeholder="Search" style="float: right;width: 300px;">
                <style type="text/css">
   .wrapper1, .wrapper2 {
  width: 300px;
  overflow-x: scroll;
  overflow-y:hidden;
}

.wrapper1 {height: 20px; }
.wrapper2 {height: 200px; }

.div1 {
  width:1000px;
  height: 20px;
}

.div2 {
  width:1000px;
  height: 200px;
  background-color: #88FF88;
  overflow: auto;
}
table{
  border: 1px solid #f5f5f5;
  border-top-left-radius: 25px;
  border-top-right-radius: 25px;
}
thead{
position: sticky;
  top: 0;
  background-color: #0B4596;
  color: #fff;
  font-size: 12px;
  margin:10px;
  width: auto;
}

th{
padding:5px;
text-align: center;
}

td{
  color: #333;
  font-size: 12px;
  text-align: center;
  padding:10px;
  border-right: 1px solid #f5f5f5;
  border-bottom: 1px solid #f5f5f5;

}
tr:not(:first-child):hover{
  background: #f5f5f5;
  transition: background-color 0.2s ease;
}
table tr {
  counter-increment: row-num;
 
}
table tr td:first-child::before {
    content: counter(row-num) " ";

}

th:first-child, td:first-child {
  position: sticky;
  left: 0;
  background-color: #0B4596;
  color: #fff;
   border-bottom: none;
}
tr{
/*  cursor: pointer;*/
}
.selected-row{
  background-color: #f5f5f5;
  font-weight: bold;
  color: #fff;
}
					input#search-input{
						display: none;
					}
					.show-detail.rt1{
						margin-top: 50px;
					}
					.show-detail h4{
						font-size: 15px;
						margin-bottom: 20px;
					}
.panel {
  border-radius: 4px;
  padding: 1rem;
  margin-top: 0.2rem;
  background-color: #F5F5F5;
  color: #323B40;
}
.panel.panel-blue {
  background-color: #E0F5FF;
  color: #00A5FA;
}
.panel.panel-big-height {
  min-height: 150px;
}

</style>
                <script>
            
            function selectedRow(){
                
                var index,
                    table = document.getElementById("multi_col_order");
            
                for(var i = 1; i < table.rows.length; i++)
                {
                    table.rows[i].onclick = function()
                    {
                         // remove the background from the previous selected row
                        if(typeof index !== "undefined"){
                           table.rows[index].classList.toggle("selected-row");
                        }
                        console.log(typeof index);
                        // get the selected row index
                        index = this.rowIndex;
                        // add class selected to the row
                        this.classList.toggle("selected-row");
                        console.log(typeof index);
                     };
                }
                
            }
            selectedRow();
        </script>
                <div onscroll='scroller("scroller", "scrollme")' style="overflow:scroll; height: 10;overflow-y: hidden;" id=scroller> 
                  
                  <!-- <img src="" height=1 width=2066 style="width:2066px;" --> 
                  <img src="" height=1 width='2300' style="width:100%;"> </div>
                <div>
                  <div onscroll='scroller("scrollme", "scroller")' style="overflow:scroll; height:650px" id="scrollme">
                    <table style="width:100%" id="multi_col_order">
                      <thead>
                      <th>#</th>
                        <th>DELEGATES</th>
                        <th>DE</th>
                        <th>EVENT</th>
                        <th>JOB TITLE</th>
                        <th>EMAIL</th>
                        <!--
                     <th>INVITED DELEGATES</th>-->
                        <th>COMPANY NAME</th>
                        <th>LEVEL</th>
                        <th>WISHLIST CATEGORY</th>
                        <th>HUBSPOT STATUS</th>
                        <th>COMMISSIOS CALCULATED</th>
                        <th></th>
                        </thead>
                      <tbody>
                        <?php
                        $sql2 = "SELECT * FROM cm_de_commission
					  INNER JOIN qr_attendance_history ON cm_de_commission.attend_id = qr_attendance_history.id
					  WHERE status='YES' $search ORDER BY attend_id DESC";
                        $totalNetcom = 0;
                        $result2 = $conn->query( $sql2 );
                        if ( $result2->num_rows > 0 ) {
                          while ( $row2 = $result2->fetch_array() ) {
                            $event_id = $row2[ 'event_id' ];
                            $qrSql_event = mysqli_query( $conn, "select * from qr_event WHERE ID='$event_id'" );
                            $listevent = mysqli_fetch_array( $qrSql_event );
                            if ( $row2[ 'com_req_level' ] ) {
                              $totalNetcom += intval($row2['com_req_level']);
                            }

                            ?>
                        <tr>
                          <td></td>
                          <td><?php echo $row2['firstname']." ".$row2['lastname'];?></td>
                          <td><?php echo $row2['de'];?></td>
                          <td><?php echo $listevent['EVENT_TITLE'];?></td>
                          <td><?php echo $row2['job_title'] ;?></td>
                          <td><?php echo $row2['email'] ;?></td>
                          <td><?php echo $row2['org'] ;?></td>
                          <!--						  <td><?php //	echo $row2['invited'] ;?></td>
-->
                          <td><?php echo $row2['level'] ;?></td>
                          <td><?php echo $row2['wishlist_category'] ;?></td>
                          <td><?php
                          if ( $row2[ 'de_status' ] == 'PRESENT' && $row2[ 'level' ] && $row2[ 'wishlist_category' ] ) {
                            echo "<a data-toggle='modal' class='inv novBluebtn' data-target='#modaldata' id='" . $row2[ 'id' ] . "'>" . $row2[ 'de_status' ] . "</a>";

                          } else {
                            if ( $row2[ 'valid' ] ) {
                              echo "<a data-toggle='modal' class='inv' data-target='#modaldata' id='" . $row2[ 'id' ] . "'>Mismatch</a><br> " . $row2[ 'valid' ] . "</span>";
                            } else {
                              echo "<a data-toggle='modal' class='inv novmissing' data-target='#modaldata' id='" . $row2[ 'id' ] . "'>Missing</a></span><br> Email</span>";
                            }

                            if ( empty( $row2[ 'job_category' ] ) ) {
                              echo "<span style='color:red; line-height:.6;'><br> Job Category is Empty</span>";
                            }
                          }
                          ?></td>
                          <td><?php if( $row2['com_req_level']){echo "$".$row2['com_req_level'] ;}?></td>
                        </tr>
                        <?php
                        }
                        } else {
                          echo "<center><p> No Records</p></center>";
                        }

                        $conn->close();
                        ?>
                      </tbody>
                    </table>
                  </div>
                  <?php include 'includes/dbcon.php';?>
                  <div style="float: right; color: #0B4596; font-weight: bold; font-size: 12px; margin-top: 20px; margin-right: 10px;">Total Comission: <span> $<?php echo $totalNetcom;?></span></div>
                  <div class="show-detail rt1" id="target_hit_content">
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
                        $total_delegate = mysqli_query( $conn, "select DISTINCT de,ev_id from cm_de_commission 
						INNER JOIN qr_attendance_history ON cm_de_commission.attend_id = qr_attendance_history.id
						WHERE $search_sec status='YES'" );
                        while ( $list_delegate = mysqli_fetch_array( $total_delegate ) ) {
                          $same_de = $list_delegate[ 'de' ];
                          $evid = $list_delegate[ 'ev_id' ];

                          $numbof_del = mysqli_query( $conn, "select * from cm_de_commission
						INNER JOIN qr_attendance_history ON cm_de_commission.attend_id = qr_attendance_history.id
						WHERE de='$same_de' and ev_id='$evid'" );

                          $evid_del = mysqli_query( $conn, "select * from cm_de_commission
						INNER JOIN qr_attendance_history ON cm_de_commission.attend_id = qr_attendance_history.id
						WHERE ev_id='$evid' and de='$same_de' AND de_status='PRESENT' AND level != '' and wishlist_category!=''" );

                          $eventDetail = mysqli_query( $conn, "select * from qr_event WHERE id='$evid'" );
                          $eventDetailsRow = mysqli_fetch_array( $eventDetail );
                          $totalNet = '0';
                          while ( $list_delegate20 = mysqli_fetch_array( $numbof_del ) ) {
                            if ( $list_delegate20[ 'com_req_level' ] ) {
								$totalNet += intval($list_delegate20['com_req_level']);
//                              $totalNet = ( $totalNet + $list_delegate20[ 'com_req_level' ] );
                            }
                          }
                          $sql_target_hit = mysqli_query( $conn, "select * from cm_target_hit WHERE eventId='$evid' and de='$same_de'" );
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
                      $totalhit = $totalhit + $hitCom;
                      $floorTtoal = $floorTtoal + $subtotalcom;
                      }
                      ?>
                      <thead>
                      <th></th>
                        <th>Total:</th>
                        <th></th>
                        <th><?php echo $total_num_del;?></th>
                        <th><?php echo $total_present_data;?></th>
                        <th><?php echo $total_num_miss;?></th>
                        <th> <span> $<?php echo $totalNetcom;?></span></th>
                        <th>$<?php echo $totalhit;?> </th>
                        <th>$<?php echo $floorTtoal;?></th>
                        <th></th>
                        </thead>
                    </table>
                  </div>
                </div>
                <script>
    var arescrolling = 0;
function scroller(from,to) {
  if (arescrolling) return; // avoid potential recursion/inefficiency
  arescrolling = 1;
  // set the other div's scroll position equal to ours
  document.getElementById(to).scrollLeft =
    document.getElementById(from).scrollLeft;
  arescrolling = 0;
}
  </script> 
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php include 'includes/footer.php';?>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modaldata" role="dialog" style="position: fixed; top: 50%; left: 50%;      transform: translate(-50%, -50%);">
  <div class="modal-dialog modal-sm" style="max-width:870px; top: 0;" id="commision_metchData"> </div>
</div>
<!--end modal--> 
<div class="modal fade" id="form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header border-bottom-0">
        <h5 class="modal-title" id="exampleModalLabel">Download Commission Report</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" action="dasboard_commission_date.php">
        <div class="modal-body">
          <div class="form-group">
            <label for="email1">Start Date</label>
            <input type="date" class="form-control" name="start_date" required>
            
          </div>
          <div class="form-group">
            <label for="email1">End Date</label>
            <input type="date" class="form-control" name="end_date" required>
          </div>
          
        </div>
        <div class="modal-footer border-top-0 d-flex justify-content-center">
          <button type="submit" name="report_filter" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
		$('.modal-toggle').on('click', function(e) {
  e.preventDefault();
  $('#logout-modal').toggleClass('is-visible');
});
	</script> 
<script src="assets/libs/popper.js/dist/umd/popper.min.js"></script> 
<script src="assets/libs/bootstrap/dist/js/bootstrap.min.js"></script> 
<script src="dist/js/app-style-switcher.js"></script> 
<script src="dist/js/feather.min.js"></script> 
<script src="assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script> 
<script src="dist/js/sidebarmenu.js"></script> 
<script src="dist/js/custom.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script> 
<script>
    $(document).ready(function () {
        $('#txtCountry').typeahead({
            source: function (query, result) {
                $.ajax({
                    url: "server.php",
					data: 'query=' + query,            
                    dataType: "json",
                    type: "POST",
                    success: function (data) {
						result($.map(data, function (item) {
							return item;
                        }));
                    }
                });
            }
        });
    });
	$(document).ready(function () {
        $('#txtDelegetas').typeahead({
            source: function (query, result) {
                $.ajax({
                    url: "de.php",
					data: 'query=' + query,            
                    dataType: "json",
                    type: "POST",
                    success: function (data) {
						result($.map(data, function (item) {
							return item;
                        }));
                    }
                });
            }
        });
    });

$('.inv').click(function(){
		var id = $(this).attr("id");
		//var priceid = $('#priceid' + id + '').val();
		$('.ajax-loader').show();
		
		$.ajax({
			url : "attendance_hubspot.php",
			method:"post",
			datatype:"text",
			data:{id:id},
			success:function(msg){
//        $('#countcart').html(msg);
        $('#commision_metchData').html(msg);
				$('.ajax-loader').hide();
				
			}
		});
	});
	
$('.target_hit').click(function(){
		var id = $(this).attr("id");
		//var priceid = $('#priceid' + id + '').val();
		$('.ajax-loader').show();
		
		$.ajax({
			url : "target_hit_process.php",
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
	<?php
//	unset($_SESSION['de_session']);
	?>
</body>
</html>