<?php
session_start();

$uri=explode('?',$_SERVER['REQUEST_URI']);
$url=array_shift($uri);

if (!isset($includes) || $includes===true) include($_SERVER['DOCUMENT_ROOT'].'/includes/includes.php');

if ($url!='/index.php' && !isset($_SESSION['employee_id'])) {
	header('Location: /index.php'); exit();
}
?>
<!doctype html>
<html>
	<head>
		<title><?php echo TITLE; if (strlen(trim($title))>0) echo " :: $title";?></title>
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css" />
		<link rel="stylesheet" href="/css/main.css" />
		<link rel="stylesheet" href="/css/form.css" />
		<link rel="stylesheet" href="/css/nav.css" />
		<link rel="stylesheet" href="/css/table.css" />
		<link href='https://fonts.googleapis.com/css?family=Lato:100,300,400' rel='stylesheet' type='text/css' />
		<link rel="shortcut icon" type="image/x-icon" href="/images/logo/Career_Hub_ico.ico"/>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
		<script src="/js/main.js"></script>
	</head>

	<body>
		<div class="logo"></div>
		<div class="topbar">
		<?php 
			if ($_SESSION['employee_id']>0) {
				echo '<div class="logout">
				Hello <span class="bold">'.stripslashes($_SESSION['first_name']).'</span>! &nbsp;|&nbsp;<a href="/account.php">My Account</a>&nbsp;|&nbsp;
				<a href="/process/logout.php" onclick="return confirm(\'Are you sure you wish to log out?\');">Logout</a>
				</div>';
			}?>
			<div class="breadcrumbs">
				<?php 
			//	Add the "Home" breadcrumb if we are not on the home page...
				if ($url!='/index.php') echo '<div style="display:inline-block;"><a href="/index.php">Home</a></div>';

			//	Adding breadcrumbs
				if (isset($bcA) && is_array($bcA)) {
					foreach ($bcA as $urlStr=>$text) echo '<div style="display:inline-block;">&nbsp;&raquo;&nbsp;<a href="'.$urlStr.'">'.stripslashes($text).'</a></div>';
				}
				if (strlen(trim($title))>0) echo '<div style="display:inline-block;" class="bold">&nbsp;&raquo;&nbsp;'.stripslashes($title).'</div>';
				?>
			</div>
		</div>
		<div class="container">