<?php
session_start();
include 'includes/dbcon.php';
include 'includes/encrypt_func.php';
if ( $_SESSION[ 'id' ] ) {
  $id = $_SESSION[ 'id' ];
  $login_sql = mysqli_query( $conn, "select * from qr_users WHERE id=$id and status='1'" );
	$login_access = mysqli_fetch_array( $login_sql );
	if(isset($_REQUEST['edit'])){
		$encrypted_id1 = $_REQUEST['edit'];
		$key = "16_byte_secret_key";
		$roleid = decrypt_id($encrypted_id1, $key);
		
		$sqlcheck = mysqli_query( $conn, "select * from cm_job_title WHERE id='$roleid'" );
  		$listrole_sql = mysqli_fetch_array( $sqlcheck );
		$btn_sub = "roleupdate";
		$btn_txt = "Update";
		if($roleid){$roleid=decrypt_id($encrypted_id1, $key);}else{$roleid='0';}
		if(isset($listrole_sql['id'])){$listId = $listrole_sql['id'];}else{$listId='';}
	}
	else{
		$btn_sub = "rolesubmit";
		$btn_txt = "Save";
		$roleid='1';
		$listId='1';
	}
	
} else {
  echo '<script type="text/javascript">
           window.location = "' . $domain_url . 'index.php"
     </script>';
  unset( $_SESSION[ 'id' ] );
}

?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
<?php include 'includes/head.php';?>
<script type="text/javascript">
    window.onload = function() {
  var statusField = document.getElementById("status");
  var remarksField = document.getElementById("remarks");

  if (statusField.value === "YES") {
    remarksField.value = "ATTENDED";
    remarksField.disabled = true;
  } else if (statusField.value === "DROPPED") {
    remarksField.value = "NOT ATTENDING";
    remarksField.disabled = true;
  }
};

</script>

<body>
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
       
			<?php if($roleid == $listId){?>
		   <div class="col-4">
          <div class="card">
            <div class="card-body">
            <h4 class="card-title">Add Category</h4>
            <!-- <small class="form-text text-muted mb-3">*This table displays all scanned attendees with <b>VALID</b> QR codes</small><br>-->
            
            <form method="post" action="job_title.php">
              <div class="row">
				 <div class="col-md-12 mt-4" style="margin-bottom: 20px;">
                  <small id="label" class="form-text text-muted mb-2">Level</small>
					<select name="level_id" class="form-control" required>
						<option value="">Select Lavel</option>
						<?php
							$lavelSql = mysqli_query( $conn, "select * from cm_level order by level_num ASC" );
							while($ListLavel = mysqli_fetch_array( $lavelSql )){
						?>
						<option value="<?php echo $ListLavel['level_num'];?>" <?php if(isset($_REQUEST['edit'])){ if($listrole_sql['level_id']==$ListLavel['level_num']){ echo "selected";}} ?>><?php echo $ListLavel['level_num'];?></option>
						<?php } ?>
						
					</select>
                  </div>	 
                <div class="col-md-12">
                  <small id="name" class="form-text text-muted mb-3">Job Category</small> 
					<input type="text" class="form-control" id="title" name="title" aria-describedby="Title" value="<?php if(isset($_REQUEST['edit'])){ echo $listrole_sql['title'];} ?>">
					
					<input type="hidden" name="id" value="<?php if(isset($_REQUEST['edit'])){ echo $listrole_sql['id'];} ?>">
					
					
                  </div>
              </div>
              </div>
              <div class="modal-footer"> <a href="job_title.php" type="button" class="btn btn-light">Back</a>
                <button type="submit" name="<?php echo $btn_sub;?>" id="submit" class="btn btn-primary"><?php echo $btn_txt;?></button>
              </div>
            </form>
          </div>
			     </div>
			<?php } else{?>
			<div style="margin: auto; margin-top: 7%; text-align: center; width: 100%;">
			<h4 style="font-size: 5em; font-weight: bold; color: #333;">404</h4>
			<p style="font-size: 2em; color: #333;">Oops... Page not found.</p>
			<p>We don't know how you ended up here, but you should go away now.</p>
				</div>
			<?php }?>
			
			
      
      </div>
    </div>
  </div>
  <?php include 'includes/footer.php';?>
</div>
</div>
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
<script src="assets/extra-libs/datatables.net/js/jquery.dataTables.min.js"></script> 
<script src="dist/js/pages/datatable/datatable-basic.init.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
</body>
</html>