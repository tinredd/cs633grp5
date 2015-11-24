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
<div class="formholder inline">
    <div class="standard">
        <div class="inline bold" style="color:#76B33C;">Login</div>
        <div class="inline floatright">Use your internal email address to log in</div>
    </div>
    <div class="standard">
        <form name="login" action="/process/login.php" method="post">
            <div class="form_row">
                <div class="field_label">Email address:</div>
                <div><input name="email_address" type="text" value="" /></div>
            </div>
            <div class="form_row">
                <div class="field_label">Password:</div>
                <div><input name="password" type="password" value="" /></div>
            </div>
            <div class="form_row">
                <div>&nbsp;</div>
                <div><input name="submt" type="submit" value="Login" /></div>
            </div>
        </form>
    </div>
</div>

<div class="formholder inline">
    <div class="standard">
        <div class="inline bold" style="color:#76B33C;">Need access?</div>
        <div class="inline floatright">Contact your HR representative</div>
    </div>
    <div class="standard">
<?php
$sql="SELECT * FROM office WHERE status=1 ORDER BY office_name";
$result=$mysqli->query($sql);

while ($row=$result->fetch_assoc()) {
    echo '
    <div class="standard">
        <div class="inline" style="width:50%;">'.stripslashes($row['office_name']).':</div>
        <div class="inline">
            <a href="mailto:'.stripslashes($row['contact_email']).'">'.stripslashes($row['contact_name']).'</a>
        </div>
    </div>';
}
?>
    </div>
    <div class="standard">Click on your HR representative's name to send them an email.</div>
</div>
<?php
}

include($_SERVER['DOCUMENT_ROOT'].'/includes/footer.php');
?>