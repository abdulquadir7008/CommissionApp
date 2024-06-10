<?php
session_start();
ob_start();
include( "../includes/dbcon.php" );
if (isset($_REQUEST['submit']))
{
$user_email =$_POST['useremail'];
$rs_search = mysqli_query($conn,"select * from qr_users where useremail='$user_email'");
$user_count = mysqli_num_rows($rs_search);
if ($user_count != 0)
{
$kd_uniq = uniqid();
mysqli_query($conn,"UPDATE qr_users set uniqid='$kd_uniq' where useremail='$user_email'");
$org_sql_activation=mysqli_query($conn,"select * from qr_users where useremail='$user_email'"); 
$list_activation=mysqli_fetch_array($org_sql_activation);
$succ_contact_person = $list_activation['fullname'];
	
$message ="<p><strong>Hi $succ_contact_person,</strong></p>
  <p>Thank you choosing for reset Password in <strong>OG App</strong></p>
  <p>Please go through the below link RESET your Password.</p>
  <p><a href='$domain_url/new_password.php?reset=$kd_uniq' target='_blank'>Reset Password</a></p>
  <p>Thank you. This is an automated response. PLEASE DO NOT REPLY.</p><br><p><strong>With Gratitude,<br><br>OpenGov</strong></p>";

/* SMTP SendInBlue start*/
	  
require_once('../vendor/autoload.php');

$config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', '');

$apiInstance = new SendinBlue\Client\Api\SMTPApi(
    new GuzzleHttp\Client(),
    $config
);
$sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail();
$sendSmtpEmail['subject'] = 'Password Reset';
$sendSmtpEmail['htmlContent'] = $message;
$sendSmtpEmail['sender'] = array('name' => 'OG App', 'email' => 'noreply@ogapp.opengovasia.com');
$sendSmtpEmail['to'] = array(
    array('email' => $user_email, 'name' => $succ_contact_person)
);
$sendSmtpEmail['replyTo'] = array('email' => 'noreply@ogapp.opengovasia.com', 'name' => 'OG App');
$sendSmtpEmail['headers'] = array('Some-Custom-Name' => 'unique-id-1234');
$sendSmtpEmail['params'] = array('parameter' => 'My param value', 'subject' => 'New Subject');

try {
    $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
    print_r($result);
	$errmsg_arr = array();
$errflag = false;
$errmsg_arr[] = '<div class="alert alert-success alert-dismissable"> <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a> <strong>Success!</strong> Thank you. Please go to your register email for Reset Link.</div>';
$errflag = true;
$_SESSION['resetarror'] = $errmsg_arr;
session_write_close();
header('Location: ' . $_SERVER['HTTP_REFERER']);
ob_end_flush();

} catch (Exception $e) {
    echo 'Exception when calling SMTPApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
	
}
	  
	/* SMTP SendInBlue End*/	

} 
else if($_POST['email']=='')
{
$errmsg_arr = array();
$errflag = false;
$errmsg_arr[] = '<div class="alert alert-danger alert-dismissable"> <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a> <strong>Waaring!</strong> Please give your correct email address</div>';
$errflag = true;
$_SESSION['resetarror'] = $errmsg_arr;
session_write_close();
}
else
{
$errmsg_arr = array();
$errflag = false;
$errmsg_arr[] = '<div class="alert alert-danger alert-dismissable"> <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a> <strong>Error Message!</strong> Account with given email does not exist</div>';
$errflag = true;
$_SESSION['resetarror'] = $errmsg_arr;
session_write_close();
}
mysqli_close($conn);
header('Location: ' . $_SERVER['HTTP_REFERER']);
ob_end_flush();
}


if(isset($_REQUEST['updatepassword'])){
  	$pass = $_REQUEST['password'];
	$password=hash('sha512',$pass);
  	$uniqid = $_REQUEST['uniqid'];
	mysqli_query($conn,"UPDATE qr_users set password='$password', uniqid='' where uniqid='$uniqid'");

  $errmsg_arr = array();
  $errflag = false;
  $errmsg_arr[] = '<div class="alert alert-success alert-dismissable"> <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a> <strong>Success!</strong> Password Change Successfully.</div>';
  $errflag = true;
  $_SESSION['passwordch'] = $errmsg_arr;
  session_write_close();
  mysqli_close($conn);
  header('Location: ../index.php');
  ob_end_flush();
}
?>