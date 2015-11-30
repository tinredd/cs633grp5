<?php
//	Get office name
	$office=$mysqli->fetch_row("SELECT office_id,office_name,city,state FROM office WHERE office_id={$_SESSION['office_id']}");

	$errorsA=unserialize(base64_decode(urlencode($_REQUEST['e'])));
	if (!is_array($errorsA)) $errorsA=array();

	if (count($errorsA)>0) echo '<div class="error">Please correct the errors in the highlighted fields</div>';
	if ($_REQUEST['msg']=='updated') echo '<div class="success">Account information updated successfully</div>';
?>

<form name="account" action="" method="post">
	<input name="action" value="update2" type="hidden" />
	<input name="t" value="<?php echo $tab;?>" type="hidden" />

	<div class="form_row">
		<div>Last updated:</div>
		<div>
			<div>
			<?php if (strtotime($_SESSION['last_updated'])) echo '<span class="bold">'.date('n/j/Y \a\t g:i a', strtotime($_SESSION['last_updated'])).'</span>';
			else echo '<span class="italic inactive">(no date entered)</span>';
			?>
			</div>
		</div>
	</div>

	<div class="form_row">
		<div>Employee ID:</div>
		<div>
			<div><?php echo stripslashes($_SESSION['employee_id']);?></div>
			<div><a href="?t=3&amp;f=<?php echo urlencode(base64_encode('employee_id'));?>" class="button">Contact HR for updates</a></div>
		</div>
	</div>

	<div class="form_row">
		<div>First name:</div>
		<div>
			<div><?php echo stripslashes($_SESSION['first_name']);?></div>
			<div><a href="?t=3&amp;f=<?php echo urlencode(base64_encode('first_name'));?>" class="button">Contact HR for updates</a></div>
		</div>
	</div>

	<div class="form_row">
		<div>Last name:</div>
		<div>
			<div><?php echo stripslashes($_SESSION['last_name']);?></div>
			<div><a href="?t=3&amp;f=<?php echo urlencode(base64_encode('last_name'));?>" class="button">Contact HR for updates</a></div>
		</div>
	</div>

	<div class="form_row">
		<div>Office location:</div>
		<div>
			<div><?php echo stripslashes($office['office_name'].' - '.$office['office_id'].' ('.$office['city'].', '.$office['state'].')');?></div>
			<div><a href="?t=3&amp;f=<?php echo urlencode(base64_encode('office_id'));?>" class="button">Contact HR for updates</a></div>
		</div>
	</div>

	<div class="form_row">
		<div>Internal email address:</div>
		<div>
			<div><?php echo $_SESSION['email_address'];?></div>
			<div><a href="?t=3&amp;f=<?php echo urlencode(base64_encode('email_address'));?>" class="button">Contact HR for updates</a></div>
		</div>
	</div>

	<div class="form_row">
		<div>Hire date:</div>
		<div>
			<div><?php if (strtotime($_SESSION['hire_date'])) echo date('n/j/Y', strtotime($_SESSION['hire_date']));
			else echo '<span class="italic inactive">(no date entered)</span>';
			?></div>
			<div><a href="?t=3&amp;f=<?php echo urlencode(base64_encode('hire_date'));?>" class="button">Contact HR for updates</a></div>
		</div>
	</div>

	<div class="form_row">
		<div>Password:</div>
		<div>
			<div><span class="italic inactive">(removed from view)</span></div>
			<div><a href="?t=2" class="button">Update password</a></div>
		</div>
	</div>

	<div class="form_row">
		<div>Contact preferences:</div>
		<div>
			<input name="hr_contact" value="<?php echo $_SESSION['hr_contact'];?>" type="hidden" />
			<input name="employee_contact" value="<?php echo $_SESSION['employee_contact'];?>" type="hidden" />

			<div class="specialselect<?php if ($_SESSION['hr_contact']==1) echo '-selected';?> standard" id="hr_contact_1">Allow HR to contact me about jobs</div>
			<div class="specialselect<?php if ($_SESSION['employee_contact']==1) echo '-selected';?> standard" id="employee_contact_1">Allow other employees to contact me</div>
		</div>
	</div>

	<div class="form_row">
		<div>Office phone:</div>
		<div><input name="office_phone" type="text" value="<?php echo stripslashes($_SESSION['office_phone']);?>" /></div>
	</div>

	<div class="form_row">
		<div>Job title:</div>
		<div><input name="job_title" type="text" value="<?php echo stripslashes($_SESSION['job_title']);?>" /></div>
	</div>

	<div class="form_row">
		<div>Notes:</div>
		<div><textarea name="notes"><?php echo stripslashes($_SESSION['notes']);?></textarea></div>
	</div>

	<div class="form_row">
		<div>&nbsp;</div>
		<div><input name="submt" type="submit" value="Update" /></div>
	</div>
</form>