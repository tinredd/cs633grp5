<?php

require_once('includes/bootstrap.php');

require_once(DOC_ROOT.'/includes/header.php');
         
//  If the user is logged in as an employee, display the appropriate portal
if (!empty($_SESSION['employee_id'])) {
    require_once(DOC_ROOT.'/app/views/UserPortalView.php');
//  Display the login screen
} else {
    require_once(DOC_ROOT.'/app/views/LoginView.php');
}

require_once(DOC_ROOT.'/includes/footer.php');