<?php
session_start();

// initialize our application
require_once('includes/bootstrap.php');

if ($_SESSION['employee_id']>0) session_destroy();

header('Location: '.APPURL.'index.php');
