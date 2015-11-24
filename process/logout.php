<?php
session_start();

if ($_SESSION['employee_id']>0) session_destroy();

header('Location: /index.php');
