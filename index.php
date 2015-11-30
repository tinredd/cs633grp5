<?php
include($_SERVER['DOCUMENT_ROOT'].'/includes/header.php');

//  If the user is logged in as an employee, display the appropriate portal
if ($_SESSION['employee_id']>0) {
    include($_SERVER['DOCUMENT_ROOT'].'/app/views/UserPortalView.php');
//  Display the login screen
} else {
    include($_SERVER['DOCUMENT_ROOT'].'/app/views/LoginView.php');
}

include($_SERVER['DOCUMENT_ROOT'].'/includes/footer.php');