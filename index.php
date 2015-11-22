<?php
include($_SERVER['DOCUMENT_ROOT'].'/includes/header.php');

//  If the user is logged in as an employee, display the appropriate portal
if ($_SESSION['employee_id']>0 && $_SESSION['user_type']==2) {
?>
<div class="portalpane"><a href="/account.php">My Account</a></div>
<div class="portalpane"><a href="/account.php?t=4">My Skills</a></div>
<div class="portalpane">My Resume</div>
<?php
//  If the user is logged in as an HR rep, display the appropriate portal
} elseif ($_SESSION['employee_id']>0 && $_SESSION['user_type']==1) {
?>
<div class="portalpane">
    <div><a href="/account.php">My Account</a></div>
    <div>&nbsp;</div>
</div>
<div class="portalpane">
    <div><a href="/employee.php">Employees</a></div>
    <div><a href="/account.php?t=4">My skills</a></div>
</div>
<div class="portalpane">
    <div><a href="/job.php">Jobs</a></div>
    <div>&nbsp;</div>
</div>
<?php
//  Display the login screen
} else {
?>
<form name="login" action="/process/login.php" method="post">
    <div class="field_label">Email address:</div>
    <div><input name="email_address" type="text" value="" /></div>
    <div class="field_label">Password:</div>
    <div><input name="password" type="password" value="" /></div>
    <div><input name="submt" type="submit" value="Login" /></div>
</form>
<?php
}

include($_SERVER['DOCUMENT_ROOT'].'/includes/footer.php');
?>