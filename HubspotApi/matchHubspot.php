<?php
session_start();
$ses = session_id();
include '../includes/dbcon.php';
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

?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
<?php include '../includes/head.php';?>

<style>
#main-wrapper[data-layout=vertical][data-sidebartype=full] .page-wrapper {
    margin-left: 0;
}
</style>
<style type="text/css">
.wrapper1, .wrapper2 {
    width: 300px;
    overflow-x: scroll;
    overflow-y: hidden;
}
.wrapper1 {
    height: 20px;
}
.wrapper2 {
    height: 200px;
}
.div1 {
    width: 1000px;
    height: 20px;
}
.div2 {
    width: 1000px;
    height: 200px;
    background-color: #88FF88;
    overflow: auto;
}
table {
    border: 1px solid #f5f5f5;
    border-top-left-radius: 25px;
    border-top-right-radius: 25px;
}
thead {
    position: sticky;
    top: 0;
    background-color: #0B4596;
    color: #fff;
    font-size: 12px;
    margin: 10px;
    width: auto;
}
th {
    padding: 5px;
    text-align: center;
}
td {
    color: #333;
    font-size: 12px;
    text-align: center;
    padding: 10px;
    border-right: 1px solid #f5f5f5;
    border-bottom: 1px solid #f5f5f5;
}
tr:not(:first-child):hover {
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
tr {
    cursor: pointer;
}
.selected-row {
    background-color: #f5f5f5;
    font-weight: bold;
    color: #fff;
}
.RetRock {
    margin-bottom: 30px;
    text-align: left;
}
</style>
<body>
<div class="preloader">
  <div class="lds-ripple">
    <div class="lds-pos"></div>
    <div class="lds-pos"></div>
  </div>
</div>
<div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
  <?php include('../includes/header.php');?>
  <?php
  include( '../logout-modal.php' );
  //		include('includes/sidebar.php');
  ?>
  <div class="page-wrapper">
    <div class="container-fluid"> 
      
      <!-- QR SCANNER ---> 
      <!---- <div class="col-lg-5 col-md-12" style="padding: 0px;margin: 0px;">
                       <iframe src="qr-scanner.php" height="620px" width="100%" title="QR Scanner" style="border:none;padding: 0px;margin: 0px;"scrolling="no"></iframe>
                    </div>
                </div> --> 
      
      <!-- multi-column ordering -->
      <div class="row">
        <div class="col-12">
          <div class="card">
            <button id="customBackButton"><i class="fa fa-angle-left"></i> Back</button>
            <div class="card-body" >
              <div class="row">
                <div class="col-12">
                  <h4 class="card-title">Match to HubSpot Contacts with Current Event.</h4>
                  <div class="card">
                    <div class="card-header"> </div>
                    <?php
                    if ( isset( $_SESSION[ 'famesage' ] ) && is_array( $_SESSION[ 'famesage' ] ) && count( $_SESSION[ 'famesage' ] ) > 0 ) {
                      foreach ( $_SESSION[ 'famesage' ] as $errors ) {
                        echo $errors;
                      }
                      unset( $_SESSION[ 'famesage' ] );
                    }
                    ?>
                    <div class="card-body">
                      <form action="bulk_import.php?status=<?php echo $ses;?>" method="POST" enctype="multipart/form-data">
                        <div class="row">
                        
						
							
							
                        <div class="col-md-3">
                          <label>Event</label>
                          <select name="event_id" class="form-control" required>
                            <option value="">Select Event</option>
                            <?php
                            $sql_qrevent = mysqli_query( $conn, "select * from qr_event WHERE STATUS='Completed'" );
                            while ( $listevent = mysqli_fetch_array( $sql_qrevent ) ) {
                              $dateFormatChange = strtotime( $listevent[ 'EVENT_DATE' ] );
                              $SGdate = date( "d-m-Y", $dateFormatChange );
                              ?>
                            <option value="<?php echo $listevent['ID'];?>"><?php echo $listevent['EVENT_TITLE']." - (".$SGdate.")";?></option>
                            <?php  } ?>
                          </select>
                        </div>
                        <div class="col-md-4" style="margin-top: 30px;">
                          <button type="submit" name="save_excel_data" class="btn btn-primary">Submit</button>
                        </div>
                      </form>
                    </div>
						
						
						<div class="row" id="filterContent">
						
						
						</div>
						
                  </div>
                </div>
              </div>
              <div class="col-6"> 
                
                <!--<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#addModal" style="float: right;"><i class="fas fa-plus"></i> Add Delegate</button> --> 
                
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include '../includes/footer.php';?>
</div>
</div>
<script src="../assets/libs/jquery/dist/jquery.min.js"></script> 
<script src="../assets/libs/bootstrap/dist/js/bootstrap.min.js"></script> 
<script src="../dist/js/feather.min.js"></script> 
<script src="../assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script> 

<!--Custom JavaScript --> 
<script src="../dist/js/custom.min.js"></script> 
<script>
	document.addEventListener('DOMContentLoaded', function () {
    const customBackButton = document.getElementById('customBackButton');
    customBackButton.addEventListener('click', function () {
        window.history.back();
    });
});
	
function showCity(sel) {
	var city_id = sel.options[sel.selectedIndex].value;  
	$("#SubPropContent").html( "" );
	 if (city_id.length > 0 ) { 
 
	 $.ajax({
			type: "POST",
			url: "subProperty.php",
			data: "city_id="+city_id,
			cache: false,
			beforeSend: function () { 
				$('#SubPropContent').html('<img src="loader.gif" alt="" width="24" height="24">');
			},
			success: function(html) {    
				$("#SubPropContent").html( html );
			}
		});
	} 
}
	
	function showSub(sel) {
	var sub_id = sel.options[sel.selectedIndex].value;  
	$("#filterContent").html( "" );
	 if (sub_id.length > 0 ) { 
 
	 $.ajax({
			type: "POST",
			url: "filterContent.php",
			data: "sub_id="+sub_id,
			cache: false,
			beforeSend: function () { 
				$('#filterContent').html('<img src="loader.gif" alt="" width="24" height="24">');
			},
			success: function(html) {    
				$("#filterContent").html( html );
			}
		});
	} 
}
	
	
</script>
</body>
</html>