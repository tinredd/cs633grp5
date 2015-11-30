<?php
    session_start();

    // include our 'include' file once 
    if (!isset($includes) || $includes===true) require_once('includes/bootstrap.php');

    // make sure we have an employee id - if not, redirect to the login page 
    $urlParts = explode('/',$_SERVER['SCRIPT_NAME']);
    $pageName = end($urlParts);
    if ($pageName!='index.php' && !isset($_SESSION['employee_id'])) {
	    header('Location: '.APPURL.'index.php'); exit();
    }
?>

<!doctype html>
<html>
	<head>
        <base href="<?php echo APPURL ?>" />    
		<title><?php echo TITLE; if (strlen(trim($title))>0) echo " :: $title";?></title>
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css" />
		<link rel="stylesheet" href="public/css/main.css" />
		<link rel="stylesheet" href="public/css/form.css" />
		<link rel="stylesheet" href="public/css/nav.css" />
		<link rel="stylesheet" href="public/css/table.css" />
		<link href='https://fonts.googleapis.com/css?family=Lato:100,300,400' rel='stylesheet' type='text/css' />
		<link rel="shortcut icon" type="image/x-icon" href="public/images/logo/Career_Hub_ico.ico"/>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
		<script src="public/js/main.js"></script>
	</head>

	<body>
		<div class="logo"></div>
		<?php if (!empty($_SESSION['employee_id'])) { ?>
		<div class="topbar">
		<?php 
			echo '<div class="logout">
			Hello <span class="bold">'.stripslashes($_SESSION['first_name']).'</span>! &nbsp;&nbsp;<a href="'.APPURL.'account.php">My Account</a>&nbsp;&nbsp;
			<a href="logout.php" onclick="return confirm(\'Are you sure you wish to log out?\');">Logout</a>
			</div>';
		?>
			<div class="breadcrumbs">
		<?php 
			//	Add the "Home" breadcrumb if we are not on the home page...
				if ($pageName!='index.php') echo '<div class="bc"><a href="<?php echo APPURL ?>index.php">Home</a></div>';

			//	Adding breadcrumbs
				if (isset($bcA) && is_array($bcA)) {
					foreach ($bcA as $urlStr=>$text) echo '<div class="bc">&nbsp;&nbsp;&raquo;&nbsp;&nbsp;<a href="'.$urlStr.'">'.stripslashes($text).'</a></div>';
				}
				if (strlen(trim($title))>0) echo '<div class="bc bold">&nbsp;&nbsp;&raquo;&nbsp;&nbsp;<span>'.stripslashes($title).'</span></div>';
		?>
			</div>
		</div>
		<?php } ?>
		<div class="container">