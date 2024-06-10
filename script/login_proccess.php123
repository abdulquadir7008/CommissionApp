<?php 
session_start();
ob_start();
include( "../includes/dbcon.php" ); 
$username = $_POST[ 'username' ];
$password_1 = $_POST[ 'password' ];
$password = hash('sha512',$password_1);
$sess=session_id();
//$SixDigitRandomNumber = rand(100000,999999);
if($username!='' || $password!='')
{	
$qry="SELECT * FROM qr_users where (username = '$username' or useremail='$username') and password = '$password' and status='1'";
$result=mysqli_query($conn,$qry);
if(mysqli_num_rows($result) == 1) {
$member = mysqli_fetch_assoc($result);
if($member['2fa_switch']=='inactive'){	
$id=$_SESSION['id'] = $member['id'];
}
$member_id=$member['id'];
$fullname=$member['fullname'];
$f_email=$member['useremail'];
//mysqli_query($conn,"UPDATE qr_users set session_id='$sess',2fa='$SixDigitRandomNumber' where id='$member_id'");
mysqli_query($conn,"UPDATE qr_users set session_id='$sess' where id='$member_id'");
//$logcheck_delete="delete from login_check where sess='$sess' and ip='$ip'";
//mysqli_query($link,$logcheck_delete);
if($member['2fa_switch']=='inactive'){
	header('Location:../index.php');
	ob_end_flush();
}
else{	
$errmsg_arr[] = '<div class="alert alert-success alert-dismissable show-hidden-menu"> <a href="#" class="close hidden-menu" data-dismiss="alert" aria-label="close">×</a> The QR Code for this secret (to scan with the Google Authenticator App: \n".</div>';
$errflag = true;
$_SESSION['famesage'] = $errmsg_arr;
session_write_close();
header( 'Location: ../2fa-login.php?log='.$sess);
ob_end_flush();
}
}
else 
{	
//$query="insert into login_check(ip,sess)values('$ip','$sess')"; 
//mysqli_query($link,$query);
$errmsg_arr[] = '<div class="alert alert-danger alert-dismissable show-hidden-menu"> <a  class="close hidden-menu" >×</a> <strong>Error!</strong> Please enter your valid email and password</div>';
$errflag = true;
$_SESSION['loginerror'] = $errmsg_arr;
//session_write_close();
header('Location:../index.php');
ob_end_flush();
}

}
else 
{
	
//$query="insert into login_check(ip,sess)values('$ip','$sess')";
//mysqli_query($link,$query);
	
$errmsg_arr[] = '<div class="alert alert-danger alert-dismissable show-hidden-menu"> <a class="close hidden-menu" onclick="funcShow();">×</a> <strong>Error!</strong> This email/password combination is incorrect</div>';
$errflag = true;
$_SESSION['loginerror'] = $errmsg_arr;
//session_write_close();
header('Location:../index.php');
ob_end_flush();
}
?>