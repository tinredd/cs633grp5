<?php
session_start();

if ($_SESSION['user_id']>0) session_destroy();

header('Location: /index.php');
?>