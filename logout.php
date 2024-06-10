<?php
session_start();
ob_start();
include( "includes/dbcon.php" );
$id=$_SESSION['id'];
mysqli_query($conn,"UPDATE qr_users set 2fa='0',session_id='' where id='$id'");
session_destroy();
unset($_SESSION['id']);
header('location:index.php');
ob_end_flush();
exit();
?>;