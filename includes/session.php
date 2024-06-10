<?php
session_start();

if(!isset($_SESSION['ID']) || (trim($_SESSION['ID']) == '')){
	header('location:index.php');
	exit();
}

$session_id = $_SESSION['ID']; 
$session_id = $_SESSION['USERNAME']; 

?>