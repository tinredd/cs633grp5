<?php
session_start();
if (!isset($includes) || $includes===true) include($_SERVER['DOCUMENT_ROOT'].'/includes/includes.php');

$mysqli=new Database();
?>
<!doctype html>
<html>
	<head>
		<title><?=TITLE;?></title>
		<link rel="stylesheet" href="/css/main.css">
	</head>

	<body>
		<div class="container">