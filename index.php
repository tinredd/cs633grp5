<?php
include($_SERVER['DOCUMENT_ROOT'].'/includes/header.php');

//  If the user is logged in as an employee, display the appropriate portal
if ($_SESSION['employee_id']>0) {

?>
<div class="portalpane">
    <div>
        <div class="inline portalheaderimage">i</div>
        <div class="inline">My Information</div>
    </div>
    <div>
        <div><a href="/account.php">My account</a></div>
        <div><a href="/account.php?t=4">My skills</a></div>
        <div>&nbsp;</div>
    </div>
</div>
<?php if ($_SESSION['user_type']==1) { ?>
<div class="portalpane">
    <div>
        <div class="inline portalheaderimage">&#9873;</div>
        <div class="inline">Employee Management</div>
    </div>
    <div>
        <div><a href="/employee.php">Employees</a></div>
        <div><a href="/employee.php?action=add">Add employee</a></div>
    </div>
</div>
<?php } ?>
<div class="portalpane">
    <div>
        <div class="inline portalheaderimage">&#9733;</div>
        <div class="inline">Jobs Management</div>
    </div>
    <div>
        <?php 
        if ($_SESSION['user_type']==1) {
            echo '<div><a href="/job.php">Jobs</a></div>';
            echo '<div><a href="/job.php?action=add">Add Job</a></div>';
        }
        ?><div><a href="/jobsearch.php">Search jobs</a></div>
    </div>
</div>
<?php

//  Display the login screen
} else {
    include($_SERVER['DOCUMENT_ROOT'].'/app/views/LoginView.php');
}

include($_SERVER['DOCUMENT_ROOT'].'/includes/footer.php');
?>