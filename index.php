<?php
include($_SERVER['DOCUMENT_ROOT'].'/includes/header.php');

//  If the user is logged in as an employee, display the appropriate portal
if ($_SESSION['employee_id']>0) {

?>
<div class="standard">Please use the links below to begin your experience.</div>
<div class="portalpane">
    <div>
        <div class="inline portalheaderimage">i</div>
        <div class="inline">My Information</div>
    </div>
    <ul>
        <li><a href="/account.php">My account</a></li>
        <li><a href="/account.php?t=4">My skills</a></li>
    </ul>
</div>
<?php if ($_SESSION['user_type']==1) { ?>
<div class="portalpane">
    <div>
        <div class="inline portalheaderimage">&#9733;</div>
        <div class="inline">Employee Management</div>
    </div>
    <ul>
        <li><a href="/employee.php">Employees</a></li>
        <li><a href="/employee.php?action=add">Add employee</a></li>
    </ul>
</div>
<?php } ?>
<div class="portalpane">
    <div>
        <div class="inline portalheaderimage">&#9873;</div>
        <div class="inline">Jobs Management</div>
    </div>
    <ul>
        <?php 
        if ($_SESSION['user_type']==1) {
            echo '<li><a href="/job.php">Jobs</a></li>';
            echo '<li><a href="/job.php?action=add">Add job</a></li>';
        }
        ?><li><a href="/jobsearch.php">Search jobs</a></li>
    </ul>
</div>
<?php

//  Display the login screen
} else {
    include($_SERVER['DOCUMENT_ROOT'].'/app/views/LoginView.php');
}

include($_SERVER['DOCUMENT_ROOT'].'/includes/footer.php');
?>