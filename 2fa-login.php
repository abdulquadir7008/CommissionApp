<?php
declare(strict_types=1);
session_start();
ob_start();
include 'includes/dbcon.php';
$sess = session_id();
include('vndr/autoload.php');
$secret = 'XVQ2UIGO75XRUKJO';
$resultm=mysqli_query($conn,"SELECT * FROM qr_users where session_id='$sess' and status='1'");
$loginm = mysqli_fetch_array($resultm);
$username = $loginm['fullname'];
$useremail = $loginm['useremail'];
$qrlink = \Sonata\GoogleAuthenticator\GoogleQrUrl::generate($useremail, $secret, "OGApp");
$g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
//echo 'Current Code is: '.$g->getCode($secret);

if(isset($_REQUEST['fasubm'])){
	$numver = implode('',$_REQUEST['number']);
	if ($g->checkCode($secret, $numver)) {
//		echo "YES \n";
		if(mysqli_num_rows($resultm)>0){
		$id=$_SESSION['id'] = $loginm['id'];
		header('Location:dashboard.php');
		ob_end_flush();
	}
	} else {
		$errmsg_arr[] = '<div class="alert alert-danger alert-dismissable show-hidden-menu"> <a class="close hidden-menu" onclick="funcShow();">×</a> <strong>Wrong!</strong> Enter verification code is wrong.</div>';
		$errflag = true;
		$_SESSION['2faerror'] = $errmsg_arr;
		session_write_close();
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		ob_end_flush();
	}
	
	
}
?>
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
                <div class="col-lg-3 col-md-3 modal-bg-img" style="background-image: url(assets/images/opengov.jpg);
    background-position: initial;
    background-size: contain;
    background-repeat: no-repeat;
    background-color: #fff;">
                </div>
                <div class="col-lg-9 col-md-9 bg-white">
                    <div class="p-3">
                         <?php
if( isset($_SESSION['2faerror']) && is_array($_SESSION['2faerror']) && count($_SESSION['2faerror']) >0 ) {
foreach($_SESSION['2faerror'] as $msg) {
echo $msg;  
}
unset($_SESSION['2faerror']); }?>
						<?php
if( isset($_SESSION['famesage']) && is_array($_SESSION['famesage']) && count($_SESSION['famesage']) >0 ) {
foreach($_SESSION['famesage'] as $msg) {
echo $msg;  
}
unset($_SESSION['famesage']); }?>
                        <h2 class="mt-3 text-center">Login</h2>
						<p align="center"><strong><?php echo "The QR Code for this secret (to scan with the Google Authenticator App: \n";?></strong></p>
						
						<p align="center"><img src="<?php echo $qrlink;?>" width="150"></p>
                        <p class="text-center">Please enter the two-factor authentication (2FA) verification code below to login. Depending on your 2FA setup, you can get the code from the 2FA app.</p>
                         <form class="mt-4" action="2fa-login.php" method="post">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group faad">
                                        <label class="text-dark" for="uname">Verification Code:</label>
                                          <fieldset name='number-code' data-number-code-form>
    <input type="number"  min='0' max='9' name='number[]' data-number-code-input='0' required />
    <input type="number" min='0' max='9' name='number[]' data-number-code-input='1' required />
    <input type="number" min='0' max='9' name='number[]' data-number-code-input='2' required />
    <input type="number" min='0' max='9' name='number[]' data-number-code-input='3' required />
    <input type="number" min='0' max='9' name='number[]' data-number-code-input='4' required />
    <input type="number" min='0' max='9' name='number[]' data-number-code-input='5' required />
  </fieldset>
                                    </div>
                                </div>
                                <div class="col-lg-12 text-center">
									<button type="submit" name="fasubm" class="btn btn-block btn-dark" >Submit</button>
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
// Elements
const numberCodeForm = document.querySelector('[data-number-code-form]');
const numberCodeInputs = [...numberCodeForm.querySelectorAll('[data-number-code-input]')];

// Event callbacks
const handleInput = ({target}) => {
  if (!target.value.length) { return target.value = null; }
  
  const inputLength = target.value.length;
  let currentIndex = Number(target.dataset.numberCodeInput);
  
  if (inputLength > 1) {
    const inputValues = target.value.split('');
    
    inputValues.forEach((value, valueIndex) => {
      const nextValueIndex = currentIndex + valueIndex;
      
      if (nextValueIndex >= numberCodeInputs.length) { return; }
      
      numberCodeInputs[nextValueIndex].value = value;
    });
    
    currentIndex += inputValues.length - 2;
  }
 
  const nextIndex = currentIndex + 1;
  
  if(nextIndex < numberCodeInputs.length) {
    numberCodeInputs[nextIndex].focus();
  }
}

const handleKeyDown = e => {
  const {code, target} = e;
  
  const currentIndex = Number(target.dataset.numberCodeInput);
  const previousIndex = currentIndex - 1;
  const nextIndex = currentIndex + 1;
  
  const hasPreviousIndex = previousIndex >= 0;
  const hasNextIndex = nextIndex <= numberCodeInputs.length - 1
  
  switch(code) {
    case 'ArrowLeft':
    case 'ArrowUp':
      if (hasPreviousIndex) {
        numberCodeInputs[previousIndex].focus();
      }
      e.preventDefault();
      break;
      
    case 'ArrowRight':
    case 'ArrowDown':
      if (hasNextIndex) {
        numberCodeInputs[nextIndex].focus();
      }
      e.preventDefault();
      break;
    case 'Backspace':
      if (!e.target.value.length && hasPreviousIndex) {
        numberCodeInputs[previousIndex].value = null;
        numberCodeInputs[previousIndex].focus();
      }
      break;
    default:
      break;
  }
}

// Event listeners
numberCodeForm.addEventListener('input', handleInput);
numberCodeForm.addEventListener('keydown', handleKeyDown);
</script>
	
	
</body>

</html>