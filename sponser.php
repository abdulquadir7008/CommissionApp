<?php
session_start();
include 'includes/dbcon.php' ;
if ( $_SESSION[ 'id' ] ) {
	$id = $_SESSION[ 'id' ];
	$login_sql = mysqli_query( $conn, "select * from qr_users WHERE id=$id and status='1'" );
	$login_access = mysqli_fetch_array( $login_sql );
	if(isset($_REQUEST['rolesubmit'])){
		$sponser = $_REQUEST['sponser'];
		$query000="insert into sponser(sponser)values('$sponser')"; 
		mysqli_query($conn,$query000);
		$errmsg_arr[] = '<div class="alert alert-danger alert-dismissable"> <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a> <strong>Success!</strong> Data Add Sucessfully.</div>';
	$errflag = true;
	$_SESSION['register_success'] = $errmsg_arr;
	session_write_close();
	header('Location: sponser.php');
	}
	else if(isset($_REQUEST['roleupdate'])){
		$sponser = $_REQUEST['sponser'];
		$sponser_id = $_REQUEST['sponser_id'];
		$query000="update sponser SET sponser='$sponser' WHERE sponser_id=$sponser_id";
		mysqli_query($conn,$query000);
		
		$errmsg_arr[] = '<div class="alert alert-danger alert-dismissable"> <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a> <strong>Success!</strong> Data Add Sucessfully.</div>';
	$errflag = true;
	$_SESSION['register_success'] = $errmsg_arr;
	session_write_close();
	header('Location: sponser.php');
	}
	else if(isset($_REQUEST['del']))
{
$job_id=$_REQUEST['del'];
$query="delete from sponser WHERE sponser_id=$job_id";
mysqli_query($conn,$query);		
	$errmsg_arr = array();
$errflag = false;
$errmsg_arr[] = '<div class="alert alert-success alert-dismissable"> <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a> <strong>Success ! </strong>Record Delete successfully.</div>';
$errflag = true;
$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
session_write_close();
header( 'Location: ' . $_SERVER[ 'HTTP_REFERER' ] );
ob_end_flush();
}
}
else{
	echo '<script type="text/javascript">
           window.location = "'.$domain_url.'index.php"
     </script>';
		unset($_SESSION['id']);
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
        
        <?php include('includes/header.php');?>
      
        <?php 
		include('logout-modal.php'); 
		include('includes/sidebar.php');
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
                            <div class="card-body">
                                 <div class="row">
                                    <div class="col-6">
                                        <h4 class="card-title">User sponser List</h4>
                                        <small class="form-text text-muted mb-3">*This table displays all <b>sponser</b> </small><br>
                                    </div>
                                        <div class="col-6">
                                           
                                            <!--<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#addModal" style="float: right;"><i class="fas fa-plus"></i> Add Delegate</button> -->

                                            <a type="button" class="btn btn-info btn-sm" href="sponser-form.php" style="float: right;"><i class="fas fa-plus"></i> Add sponser</a>

                                           

                                        </div>
                                    </div>

                                    


                                     <div class="table-responsive">

                                  <?php
                                  include 'includes/dbcon.php';
                                  ?>

                             


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
  cursor: pointer;
}
.selected-row{
  background-color: #f5f5f5;
  font-weight: bold;
  color: #fff;
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
                             <?php include 'includes/dbcon.php';?>

<br><br>
<div onscroll='scroller("scroller", "scrollme")' style="" id=scroller>
  <!-- <img src="" height=1 width=2066 style="width:2066px;" -->
  <img src="" height="1">
</div>


<div onscroll='scroller("scrollme", "scroller")' id="scrollme">
  <table id="multi_col_order" width="450">
     <thead>
                                        <th>#</th>
                                        <th>Sponser Name</th>
                                        
                                        <th></th>
                   </thead>


     <tbody>

                     <?php $sql2 = "SELECT * FROM sponser ORDER BY sponser_id ASC";
                                                $result2 = $conn->query($sql2);
                                                if($result2->num_rows > 0){
                                                while($row2 = $result2->fetch_array()){?>

                  
                   <tr>

                                        <td></td>
                                        <td id="status"><?php echo $row2['sponser'];?></td>
                                        
                                        <td id="status">
											<a href="sponser-form.php?role_id=<?php echo $row2['sponser_id']; ?>" class="btn btn-info btn-sm">Edit</a>
											
											<a href="sponser.php?del=<?php echo $row2['sponser_id']; ?>" class="btn btn-danger btn-sm" onClick="return confirm('Do you really want to remove it?')">Delete</a>
					   </td>
                                    </tr>

                                    
                    
                     <?php
                                                    }   
                                                }else{
                                                    echo "<center><p> No Records</p></center>";
                                                }
                                        
                                        $conn->close();
                                        ?>
                 
                  
                  </tbody>
  </table>

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



    

<script type="text/javascript">
    var refreshInterval;

    $(document).ready(function(){
        $('#search-input').on('keyup', function(){
            var searchText = $(this).val().toLowerCase();
            $('#multi_col_order tbody tr').filter(function(){
                $(this).toggle($(this).text().toLowerCase().indexOf(searchText) > -1);
            });
            
            // If the search input is active, clear the refresh interval
            //clearInterval(refreshInterval);
            if (searchText.length > 0) {
                clearInterval(refreshInterval);
            }

        });

        // Disable refresh on row click
        $('#multi_col_order tbody').on('click', 'tr', function() {
            clearInterval(refreshInterval);
        });
        
        // Set up the initial refresh interval
        refreshInterval = setInterval(function() {
            $('#multi_col_order').load(document.URL + ' #multi_col_order');
            $('#attended').load(document.URL + ' #attended');
            $('#dropped').load(document.URL + ' #dropped');
            $('#arrived').load(document.URL + ' #arrived');
            $('#otw').load(document.URL + ' #otw');
            $('#replacement').load(document.URL + ' #replacement');
            $('#late').load(document.URL + ' #late');
            $('#new1').load(document.URL + ' #new1');
            $('#noanswer').load(document.URL + ' #noanswer'); // add this line to refresh the element with the 'attended' id
            $('#yet').load(document.URL + ' #yet'); // add this line to refresh the element with the 'yet to register' id
        }, 2000);
    });
</script>




                       
   
    <script src="assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="dist/js/app-style-switcher.js"></script>
    <script src="dist/js/feather.min.js"></script>
    <script src="assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="dist/js/custom.min.js"></script>


    <!--Tables -->

    <!-- <script src="assets/libs/double-scroll/double-scrollbar.js"></script> -->
 <!-- <script src="assets/libs/double-scroll/double-scroll-table.js"></script> -->



    <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>

</body>
</html>