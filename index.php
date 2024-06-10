<?php session_start();?>
<!DOCTYPE html>
<html dir="ltr">
<?php include 'includes/dbcon.php'; include 'includes/head.php';
		
	if(isset($_SESSION['id'])){
	echo '<script type="text/javascript">
           window.location = "'.$domain_url.'dashboard.php"
     </script>';
}
	else{
		unset($_SESSION['id']);
	}
?>
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
      <div class="col-lg-7 col-md-5 modal-bg-img" style="background-image: url(assets/images/opengov.jpg);"> </div>
      <div class="col-lg-5 col-md-7 bg-white">
		
        <div class="p-3">
			<?php
          if ( isset( $_SESSION[ 'loginerror' ] ) && is_array( $_SESSION[ 'loginerror' ] ) && count( $_SESSION[ 'loginerror' ] ) > 0 ) {
            foreach ( $_SESSION[ 'loginerror' ] as $msg ) {
              echo $msg;
            }
            unset( $_SESSION[ 'loginerror' ] );
          }
          ?>
          <?php
          if ( isset( $_SESSION[ 'register_success' ] ) && is_array( $_SESSION[ 'register_success' ] ) && count( $_SESSION[ 'register_success' ] ) > 0 ) {
            foreach ( $_SESSION[ 'register_success' ] as $msg ) {
              echo $msg;
            }
            unset( $_SESSION[ 'register_success' ] );
          }
          ?>
          <?php
          if ( isset( $_SESSION[ 'passwordch' ] ) && is_array( $_SESSION[ 'passwordch' ] ) && count( $_SESSION[ 'passwordch' ] ) > 0 ) {
            foreach ( $_SESSION[ 'passwordch' ] as $msg ) {
              echo $msg;
            }
            unset( $_SESSION[ 'passwordch' ] );
          }
          ?>
          <h2 class="mt-3 text-center">Log In</h2>
          <p class="text-center">Enter your email address and password to access admin panel.</p>
          <form class="mt-4" action="script/login_proccess.php" method="POST">
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                  <label class="text-dark" for="uname">Username or Email ID</label>
                  <input class="form-control" name="username" type="text"
                                            placeholder="enter your username">
                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group">
                  <label class="text-dark" for="pwd">Password</label>
                  <input class="form-control" name="password"  type="password"
                                            placeholder="enter your password">
                </div>
              </div>
              <div class="col-lg-12 text-center">
                <input type="submit" name="log" class="btn btn-block btn-dark" value="Login">
                <a href="forget_password.php" class="forget-class">Forget Password</a> </div>
              <div class="col-lg-12 text-center mt-5">
                <?php include 'includes/footer.php';?>
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
<!-- ============================================================== --> 

<script>
        $(".preloader ").fadeOut();
    </script>
</body>
</html>