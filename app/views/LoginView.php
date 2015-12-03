<?php
if (isset($_REQUEST['e']) && $_REQUEST['e']==1) echo '<div class="error">Email address and/or password is incorrect! Please contact your HR representative for login credentials.</div>';
?>

<div class="standard" style="font-size:1em;">
    <div class="formholder inline">
        <div class="standard">
            <div class="inline bold" style="color:#76B33C;">Login</div>
            <div class="inline floatright">Use your internal email address to log in</div>
        </div>
        <div class="standard">
            <form name="login" action="<?php echo APPURL ?>login.php" method="post">
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
</div>
