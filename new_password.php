<?php include 'includes/dbcon.php'; session_start();?>
<!DOCTYPE html>
<html dir="ltr">
<?php include 'includes/head.php';?>



<body>
    <div class="main-wrapper">
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative">
            <div class="auth-box row">
                <div class="col-lg-3 col-md-9 modal-bg-img" style="background-image: url(assets/images/opengov.jpg);
    background-position: initial;
    background-size: contain;
    background-repeat: no-repeat;
    background-color: #fff;">
                </div>
                <div class="col-lg-9 col-md-9 bg-white">
                    <div class="p-3">
                         <?php
if( isset($_SESSION['resetarror']) && is_array($_SESSION['resetarror']) && count($_SESSION['resetarror']) >0 ) {
foreach($_SESSION['resetarror'] as $msg) {
echo $msg;  
}
unset($_SESSION['resetarror']); }?>
                        <h2 class="mt-3 text-center">Reset New Password</h2>
                        <p class="text-center">Filled the Below Details.</p>
                         <form class="mt-4" action="script/forgotpass_script.php" method="post">
                            <div class="row">
                               
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="pwd">Password</label>
                                        <input class="form-control pword" id="password" name="password" type="password"
                                            placeholder="enter your password" required>
										
										<i class="toggle-password fa fa-fw fa-eye-slash kdrsk"></i>
										
                                 	 <div class="tooltip_og">
                                    <div> 10 Characters  : <span class="passfail passfailSix"><span class="fail">Fail</span></span> </div>
                                    <div> 1 LowerCase  : <span class="passfail passfailLower"><span class="fail">Fail</span></span> </div>
                                    <div> 1 Uppercase  : <span class="passfail passfailUpper"><span class="fail">Fail</span></span> </div>
                                    <div> 1 Number  : <span class="passfail passfailNum"><span class="fail">Fail</span></span> </div>
                                    <div> 1 Special Character  : <span class="passfail passfailSpecial"><span class="fail">Fail</span></span> </div>
                                    <hr />
                                    <div class="alltogCont"> Good Password  : <span class="passfail passfailAll"><span class="fail">Fail</span></span><span class="checkmark">&#x2714;</span> </div>
                                  </div>
										
                                    </div>
                                </div>
								
								<div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="pwd">Confirm Password</label>
                                        <input class="form-control" type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                                    </div>
                                </div>
                                <div class="col-lg-12 text-center">
									<input type="hidden" name="uniqid" value="<?php echo $_REQUEST['reset'];?>">
									<button type="submit" name="updatepassword" class="btn btn-block btn-dark regbtn" >Register</button>
                                </div>
                                <div class="col-lg-12 text-center mt-5">
                                   <?php //include 'includes/footer.php';?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
  
    <script src="../assets/libs/jquery/dist/jquery.min.js "></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../assets/libs/popper.js/dist/umd/popper.min.js "></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.min.js "></script>
	<script src="dist/js/passowrd.js"></script>
    <!-- ============================================================== -->
    
    <script>
        $(".preloader ").fadeOut();
    </script>
	<script>
$(document).ready(function(){

    $("#txt_username").keyup(function(){

         var username = $(this).val();
         var usernameRegex = /^[a-zA-Z0-9]+$/;

         if(usernameRegex.test(username) && username != ''){

              $.ajax({
                   url: 'userverify.php',
                   type: 'post',
                   data: {username: username},
                   success: function(response){

                        $('#uname_response').html(response);

                   }
              });
         }else{
              $("#uname_response").html("<span style='color: red; float:right;font-size:13px;'>Enter valid username</span>");
         }

    });

 });
</script>
	
	
</body>

</html>