<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'].'/includes/includes.php');

$sql="SELECT * FROM user WHERE email_address='".$_POST['email_address']."' AND password=AES_ENCRYPT('".$_POST['password']."','".AES_KEY."')";
$rs_row=$mysqli->query($sql);

if ($rs_row->num_rows==0) {
	session_destroy();
	header('Location: /index.php?e=1'); exit();
} else {
	$row=$rs_row->fetch_assoc();
	foreach ($row as $key=>$value) if (!in_array($key,array('password'))) $_SESSION[$key]=$value;

//	HR Representative
	header('Location: /index.php'); exit();

}
