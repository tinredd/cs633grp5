<?php
session_start();

// initialize our application
require_once('includes/bootstrap.php');
  
// verify that the user exists
$sql="SELECT * FROM user WHERE email_address='".$_POST['email_address']."' AND password=AES_ENCRYPT('".$_POST['password']."','".AES_KEY."')";
$rs_row=$mysqli->query($sql);

if ($rs_row->num_rows==0) {
    session_destroy();
	header('Location: '.APPURL.'index.php?e=1'); exit();
} else {
	$row=$rs_row->fetch_assoc();
	foreach ($row as $key=>$value) if (!in_array($key,array('password'))) $_SESSION[$key]=$value;

    //	HR Representative
	header('Location: '.APPURL.'index.php'); exit();

}
