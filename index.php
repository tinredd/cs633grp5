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
        <div><a href="/account.php?t=4">Add employee</a></div>
    </div>
</div>
<?php } ?>
<div class="portalpane">
    <div>
        <div class="inline portalheaderimage">&#9733;</div>
        <div class="inline">Jobs Management</div>
    </div>
    <div>
        <?php if ($_SESSION['user_type']==1) echo '<div><a href="/job.php">Jobs</a></div>';?>
        <div><a href="/jobsearch.php">Search jobs</a></div>
    </div>
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