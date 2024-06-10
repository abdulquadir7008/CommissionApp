<?php
session_start();
include 'includes/dbcon.php';
if ( $_SESSION[ 'id' ] ) {
  $id = $_SESSION[ 'id' ];
  $login_sql = mysqli_query( $conn, "select * from qr_users WHERE id=$id and status='1'" );
  $login_access = mysqli_fetch_array( $login_sql );
} else {
  echo '<script type="text/javascript">
           window.location = "' . $domain_url . 'dashboard.php"
     </script>';
  unset( $_SESSION[ 'id' ] );
}
if(isset($_REQUEST['delete'])){
	 $id = $_REQUEST[ 'event' ];
  	$query = "delete from cm_hubspot WHERE event_id=$id";
  	mysqli_query( $conn, $query );
	  $errmsg_arr = array();
  $errflag = false;
  $errmsg_arr[] = ' Record Delete successfully.';
  $errflag = true;
  $_SESSION[ 'message' ] = $errmsg_arr;
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
                    <!-- This is for the sidebar toggle which is visibles on mobile only -->
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                            class="ti-menu ti-close"></i></a>
                   
                    
                    <center><a href="dashboard.php"><img src="assets/images/opengov-logo.jpg" style="max-width: 100%; height: auto;margin-left: -10px;" alt="homepage" class="dark-logo" /></a></center>
                                
                                
                    
                    <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
                        data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i
                            class="ti-more"></i></a>
                </div>
              
                <div class="navbar-collapse collapse float-left mr-auto" id="navbarSupportedContent">
                    <ul class="navbar-nav float-left mr-auto">


        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-12 align-self-left">
                    <h5 class="page-title text-truncate text-dark font-weight-medium mb-1" title="Commission Calculation">
                        Hubspot Data
                    </h5>
                    
                </div>
            </div>
        </div>



                      
                    </ul>
                    <ul class="navbar-nav float-right">
                        

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <img src="assets/images/admin.jpg" alt="user" class="rounded-circle"
                                    width="40">
                                <span class="ml-2 d-none d-lg-inline-block"><span>Hello,</span> <span
                                        class="text-dark"><?php echo $login_access['fullname'];?></span> <i data-feather="chevron-down"
                                        class="svg-icon"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                                <div class="pl-4 p-3"><a class="btn btn-sm btn-info" data-toggle="modal"
                                        data-target="#logout-modal" style="color:#fff;"><i data-feather="power"
                                        class="svg-icon mr-2 ml-1"></i>
                                    Logout</a></div>


                                
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
	
	
	
	
						
					</div>
      <div class="row" id="tblattendance">
        <div class="col-12">
          <div class="card">
			  
			  
            <div class="card-body">
				
				<?php
          if ( isset( $_SESSION[ 'message' ] ) && is_array( $_SESSION[ 'message' ] ) && count( $_SESSION[ 'message' ] ) > 0 ) {
            foreach ( $_SESSION[ 'message' ] as $msg ) {
              echo "<h4>" . $msg . "</h4>";
            }
            unset( $_SESSION[ 'message' ] );
          }
          ?>
              <div class="table-responsive">
              <form action="export2.php" method="post">
			<button type="submit" class="btn btn-info" style="float: right;">CSV Export</button>
		</form>
               
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
					.show-detail{
						margin-top: 50px;
					}
					.show-detail h4{
						font-size: 15px;
						margin-bottom: 20px;
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
              <form action="hubspot_data.php" method="post">
							
							<div class="row">

								
								<div class="col-md-2">
									<select class="form-control" name="event" required>
										<option value="">Select Event</option>
										<?php
										$QrEventData = mysqli_query( $conn, "select * from qr_event WHERE STATUS='Completed' order by EVENT_TITLE ASC" );
										while($RowQrEvent = mysqli_fetch_array( $QrEventData )){
											$dateFormatChange = strtotime($RowQrEvent['EVENT_DATE']);
											$SGdate = date("d-m-Y",$dateFormatChange);
										?>
										<option value="<?php echo $RowQrEvent['ID'];?>"><?php echo $RowQrEvent['EVENT_TITLE']." - (".$SGdate.")";?></option>
										<?php } ?>
										
									</select>
								</div>
								
								<div class="col-md-2">
									<button name="delete" class="btn btn-danger" type="submit">Delete</button>
									
									
								</div>
							</div>
							
							
							
							
						</form>
				  
				  
                <div onscroll='scroller("scroller", "scrollme")' style="overflow:scroll; height: 10;overflow-y: hidden;" id=scroller>
					
					
                  <!-- <img src="" height=1 width=2066 style="width:2066px;" --> 
                  <img src="" height=1 width='2300' style="width:100%;"> </div>
                <div onscroll='scroller("scrollme", "scroller")' style="overflow:scroll; height:650px" id="scrollme">
                  <table style="width:100%" id="multi_col_order">
                    <thead>
                    <th>#</th>
						        <th></th>
					          <th>FULLNAME</th>
                    <th>EMAIL</th>
                    <th>INVITED DELEGATE</th>
                    <th>DELEGATE</th>
					          <th>JOB TITLE</th>
                    <th>COMPANY NAME</th>
                    <th>EVENT NAME</th>
                    <th>STREET ADDRESS</th>
                    <th>CITY</th>
                    <th>COUNTRY</th>
                    <th>POSTAL CODE</th>
                    <th>MOBILE NUMBER</th>
                    <th></th>
                    </thead>
                    <tbody>
                      <?php
                      $sql2 = "SELECT * FROM cm_hubspot order BY record_id desc";
                      $result2 = $conn->query( $sql2 );
                      if ( $result2->num_rows > 0 ) {
                        while ( $row2 = $result2->fetch_array() ) {
                      ?>
                      <tr>
                        <td></td>
						  <td>
<!--							  <input type="checkbox" name="users[]" value="<?php echo $row2["record_id"]; ?>" />-->
						  </td>
                        <td><?php echo $row2['firstname']." ".$row2['lastname'];?></td>
						<td><?php echo $row2['email'];?></td>
                        <td><?php echo $row2['invited_delegate'];?></td>
                        <td><?php echo $row2['de'] ;?></td>
                        <td><?php echo $row2['job_title'] ;?></td>
						            <td><?php echo $row2['company_name'] ;?></td>
                        <td>
                          <?php $eventId = $row2['event_id'];
                          $eventSql = mysqli_query( $conn, "select * from qr_event WHERE ID=$eventId" );
                          $listEventcall = mysqli_fetch_array( $eventSql );
                          echo $listEventcall['EVENT_TITLE'];
                          ?>
                        </td>
                        <td><?php echo $row2['stret_address'] ;?></td>
                        <td><?php echo $row2['city'] ;?></td>
                        <td><?php echo $row2['country'] ;?></td>
                        <td><?php echo $row2['postalcode'] ;?></td>
                        <td><?php echo $row2['mobilenumber'] ;?></td>
                        
                       
						 
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
				  <div class="show-detail">
					  <h4>Show Total Row:<?php echo mysqli_num_rows($result2);?></h4>
				  	
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
</script>
</body>
</html>