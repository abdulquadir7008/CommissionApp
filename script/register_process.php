<?php 
session_start();
ob_start(); 
include( "../includes/dbcon.php" );
$sess = session_id();
$username = $_REQUEST[ 'username' ];
$useremail = $_REQUEST[ 'useremail' ];
$user_role = $_REQUEST[ 'user_role' ];
$userrole_sql = mysqli_query( $conn, "select * from ev_roles WHERE role_id='$user_role'" );
$userlistrole = mysqli_fetch_array( $userrole_sql );
$user_role_name=$userlistrole['role_name'];
$parts = explode('@', $useremail);
$namePart = $parts[1];
//$empl_email_verify_sql = mysqli_query( $link, "select * from email_verify WHERE social_email='$namePart'" );
//$eml_verify_list = mysqli_fetch_array( $empl_email_verify_sql );
if($namePart!='opengovasia.com'){
	$user_email='';
	$errmsg_arr[] = '<div class="alert alert-danger alert-dismissable"> <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a> <strong>Error!</strong> Please enter the Only OpenGov Business Email ID.</div>';
	$errflag = true;
	$_SESSION['register_success'] = $errmsg_arr;
	session_write_close();
	header('Location: ' . $_SERVER['HTTP_REFERER']);
	ob_end_flush();
}

$fullname = $_REQUEST[ 'fullname' ];
$pass = $_REQUEST[ 'password' ];
$password = hash( 'sha512', $pass );
if ( $useremail != '' && isset( $_REQUEST[ 'regsub' ] ) && $username !='' && $namePart=='opengovasia.com') {
  $sql = "select * from qr_users where useremail='$useremail' or username='$username' ";
  $result = mysqli_query( $conn, $sql );
  if ( mysqli_num_rows( $result ) == 0 ) {
	  
	$query000="insert into qr_users(username,fullname,password,useremail,role,status,uniqid,2fa,session_id,created_date)
values('$username','$fullname','$password','$useremail','$user_role','0','','0','$sess',now())"; 
mysqli_query($conn,$query000);

	 $query000="insert into ev_user_role(username,role,status,created_date)
values('$username','$user_role_name','pending',now())"; 
mysqli_query($conn,$query000);
	  
	  
	  $msg ="<div style='background:#f3f3f3; padding:40px; text-align:center;'>
  <div style='width:700px; padding:20px; background:#fff; margin:auto; font-family:Arial, Helvetica, sans-serif; font-size:14px;'>
    <div align='center'><img src='$domain_url/images/opengov.jpg' width='100'></div>
    <p><strong>Hi,</strong></p>
    <div>Thank you create account in <strong>OG App</strong></div>
    <div style='background:#09F; padding:10px 20px; font-weight:bold; text-align:center; font-size:15px; font-weight:bold; width:270px; margin:20px auto;'><a href='$domain_url/verify.php?temp=$sess&&email=$useremail' style=' display:block;color:#fff; text-decoration:none;'>Click here to Verify Your Account</a>
	</div>
	<p>OR</p>
	<P><strong>Copy here: $domain_url/verify.php?temp=$sess&&email=$useremail</strong></p>
  </div>
</div>";

	/* SMTP SendInBlue start*/
	  
require_once('../vendor/autoload.php');

$config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', '');

$apiInstance = new SendinBlue\Client\Api\SMTPApi(
    new GuzzleHttp\Client(),
    $config
);
$sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail();
$sendSmtpEmail['subject'] = 'Thank you Register in OGApp';
$sendSmtpEmail['htmlContent'] = $msg;
$sendSmtpEmail['sender'] = array('name' => 'OG App', 'email' => 'noreply@ogapp.opengovasia.com');
$sendSmtpEmail['to'] = array(
    array('email' => $useremail, 'name' => $fullname)
);
$sendSmtpEmail['replyTo'] = array('email' => 'noreply@ogapp.opengovasia.com', 'name' => 'Register user');
$sendSmtpEmail['headers'] = array('Some-Custom-Name' => 'unique-id-1234');
$sendSmtpEmail['params'] = array('parameter' => 'My param value', 'subject' => 'New Subject');

try {
    $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
    print_r($result);
	$errmsg_arr[] = '<div class="alert alert-success alert-dismissable"> <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a> <strong>Sucess!</strong> Thank you for register. Please go through your register email and verify it.</div>';
      $errflag = true;
      $_SESSION[ 'register_success' ] = $errmsg_arr;
      session_write_close();
      header( 'Location: ../register.php?succ='.$sess );
	  ob_end_flush();
} catch (Exception $e) {
    echo 'Exception when calling SMTPApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
	
}
	  
	/* SMTP SendInBlue End*/
	  


  } else {
    $errmsg_arr[] = '<div class="alert alert-danger alert-dismissable"> <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a> <strong>Error!</strong> This Username and email is already use.</div>';
    $errflag = true;
    $_SESSION[ 'register_success' ] = $errmsg_arr;
    session_write_close();
    header( 'Location: ../register.php' );
	ob_end_flush();
  }
} else {
  $errmsg_arr[] = '<div class="alert alert-danger alert-dismissable"> <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a> <strong>Error!</strong> Please filled the Register form.</div>';
  $errflag = true;
  $_SESSION[ 'register_success' ] = $errmsg_arr;
  session_write_close();
  header( 'Location: ../register.php' );
  ob_end_flush();
}
?>