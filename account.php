<?php
session_start();

require_once('includes/bootstrap.php');

//	These are the fields to be updated by HR...
$ufieldsA=array(
	'employee_id'=>'Employee ID',
	'first_name'=>'First name',
	'last_name'=>'Last name',
	'office_id'=>'Office location',
	'email_address'=>'Email address',
	'hire_date'=>'Hire date'
);

require_once(DOC_ROOT.'/app/controllers/AccountController.php');


if ($_REQUEST['t']>1) $tab=$_REQUEST['t'];
else $tab=1;

switch($tab) {
	case 1:
	$title="Update Account";
	break;

	case 2:
	$title="Update Password";
	break;

	case 3:
	$title="Contact HR";
	break;

	case 4:
	$title="My Skills";
	break;

	default: 
	$title="Update Account";
	break;
}

require_once(DOC_ROOT.'/includes/header.php');
?>
<ul class="tabs">
	<li><a href="?t=1"<?php if ($tab==1) echo ' class="active"';?>>Account Information</a></li>
	<li><a href="?t=2"<?php if ($tab==2) echo ' class="active"';?>>Update Password</a></li>
	<li><a href="?t=3"<?php if ($tab==3) echo ' class="active"';?>>Contact HR</a></li>
	<li><a href="?t=4"<?php if ($tab==4) echo ' class="active"';?>>My Skills</a></li>
</ul>
<?php

$errorsA=unserialize(base64_decode(urlencode($_REQUEST['e'])));
if (!is_array($errorsA)) $errorsA=array();

if (count($errorsA)>0) echo '<div class="error">Please correct the errors in the highlighted fields</div>';

if ($tab==2) {
	require_once(DOC_ROOT.'/app/views/AccountUpdatePwdView.php');

} elseif ($tab==3) {
	require_once(DOC_ROOT.'/app/views/AccountContactHrView.php');

} elseif ($tab==4) {
	require_once(DOC_ROOT.'/app/views/AccountAddSkillView.php');

} else {
	require_once(DOC_ROOT.'/app/views/AccountFormView.php');

}

require_once(DOC_ROOT.'/includes/footer.php');