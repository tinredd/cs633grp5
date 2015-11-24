<?php
session_start();

$includes=false;
include($_SERVER['DOCUMENT_ROOT'].'/includes/includes.php');

//	These are the fields to be updated by HR...
$ufieldsA=array(
	'employee_id'=>'Employee ID',
	'first_name'=>'First name',
	'last_name'=>'Last name',
	'office_id'=>'Office location',
	'email_address'=>'Email address',
	'hire_date'=>'Hire date'
);

if ($_POST['action']=='update2' && $_POST['t']==1) {
//	Get errors (no error checking necessary)...


//	Compile fields to insert into DB...
	$fieldsA=array();
	$fieldsA['office_phone']=addslashes(strip_tags($_POST['office_phone']));
	$fieldsA['job_title']=addslashes(strip_tags($_POST['job_title']));
	$fieldsA['notes']=addslashes(strip_tags($_POST['notes']));
	$fieldsA['last_updated']=date('Y-m-d H:i:s');

//	Update DB record...
	$updatesA=array();
	foreach ($fieldsA as $key=>$value) $updatesA[]="$key='".$value."'";

	$sql="UPDATE user SET ".implode(',',$updatesA)." WHERE user_id={$_SESSION['user_id']} LIMIT 1";
	$result=$mysqli->query($sql);

//	Assign SESSION elements...
	foreach ($fieldsA as $key=>$value) $_SESSION[$key]=$value;

//	Redirect...
	header('Location: /account.php?msg=updated'); exit();

} elseif ($_POST['action']=='pw2' && $_POST['t']==2) {
//	Get errors...
	$errorsA=array();
	$match=$mysqli->fetch_value("SELECT COUNT(*) FROM user WHERE password=AES_ENCRYPT('".$_POST['password1']."','".AES_KEY."') AND user_id={$_SESSION['user_id']}");

	if ($_POST['password1']!=$_POST['password2']) $errorsA[]='password2';
	if (!validPassword($_POST['password1'])) $errorsA[]='password1';
	if ($match==0) $errorsA[]='password';

	if (count($errorsA)>0) $eStr='&e='.urlencode(base64_encode(serialize($errorsA)));

	if (count($errorsA)==0) {
	//	Compile fields to insert into DB...
		$fieldsA=array();
		$fieldsA['password']="AES_ENCRYPT('".$_POST['password1']."','".AES_KEY."')";
		$fieldsA['last_updated']="'".date('Y-m-d H:i:s')."'";

	//	Update DB record...
		$updatesA=array();
		foreach ($fieldsA as $key=>$value) $updatesA[]="$key=$value";

		$sql="UPDATE user SET ".implode(',',$updatesA)." WHERE user_id={$_SESSION['user_id']} LIMIT 1";
		$result=$mysqli->query($sql);
	}

//	Redirect...
	header('Location: /account.php?t=2'.$eStr); exit();

//	Contact HR...
} elseif ($_POST['action']=='hr2' && $_POST['t']==3) {
//	Get errors...
	$errorsA=array();
	$to=$mysqli->fetch_value("SELECT contact_email FROM office WHERE office_id={$_SESSION['office_id']}");

	if (strlen(trim($_POST['message']))==0) $errorsA[]='message';
	if (strlen(trim($_POST['subject']))==0 && strlen(trim($_POST['subjecta']))==0) $errorsA[]='subject';

	$messageTop="New message posted on ".TITLE."

";
	if (strlen(trim($_POST['field']))>0) $messageTop.="Information update request from:

Name: ".stripslashes($_SESSION['last_name'].', '.$_SESSION['first_name'])."
Employee ID: ".$_SESSION['employee_id']."
Update field: ".$ufieldsA[$_POST['field']]."

";

//	define variables...
	$from=$_SESSION['email_address'];
	if (strlen(trim($_REQUEST['f']))>0) $subject=strip_tags($_POST['subjecta']);
	else $subject=strip_tags($_POST['subject']);
	$message=$messageTop.strip_tags($_POST['message']);
	$headers='From: '.$from. "\r\n" .
'Reply-To: '.$from . "\r\n" .
'X-Mailer: PHP/' . phpversion();

//	Send email message to HR...
	mail($to, $subject, $message, $headers);

//	Redirect...
	if (count($errorsA)>0) $eStr='&e='.urlencode(base64_encode(serialize($errorsA)));
	if (strlen(trim($_REQUEST['f']))>0) $fStr='&f='.$_REQUEST['f'];
	header('Location: /account.php?t=3'.$eStr.$fStr); exit();

} elseif ($_POST['action']=='addskill2' && $_POST['t']==4) {
	$skillset=explode(',',$mysqli->fetch_value("SELECT GROUP_CONCAT(skill_id) FROM employee_skill WHERE employee_id={$_SESSION['employee_id']}"));

	if (count($_POST['skill_id'])==0) {
		$sql="DELETE FROM employee_skill WHERE employee_id={$_SESSION['employee_id']}";
		$result=$mysqli->query($sql);
	} else {
		$deleteA=$addA=array();

		foreach ($skillset as $skill_id) {
			if (!in_array($skill_id,$_POST['skill_id']) && $skill_id>0) $deleteA[]=$skill_id;
		}
		foreach ($_POST['skill_id'] as $skill_id) {
			if (!in_array($skill_id,$skillset) && $skill_id>0) $addA[]=$skill_id;
		}

		if (count($deleteA)>0) {
			$sql="DELETE FROM employee_skill WHERE employee_id={$_SESSION['employee_id']} AND skill_id IN (".implode(',',$deleteA).")";
			$result=$mysqli->query($sql);
		}

		foreach ($addA as $skill_id) {
			$sql="INSERT INTO employee_skill SET employee_id={$_SESSION['employee_id']}, skill_id=$skill_id";
			$result=$mysqli->query($sql);
		}
	}
	header('Location: /account.php?t=4'.$eStr.$fStr); exit();
}

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

include($_SERVER['DOCUMENT_ROOT'].'/includes/header.php');
?>
<ul class="tabs">
	<li><a href="?t=1"<?php if ($tab==1) echo ' class="active"';?>>Account Information</a></li>
	<li><a href="?t=2"<?php if ($tab==2) echo ' class="active"';?>>Update Password</a></li>
	<li><a href="?t=3"<?php if ($tab==3) echo ' class="active"';?>>Contact HR</a></li>
	<li><a href="?t=4"<?php if ($tab==4) echo ' class="active"';?>>My Skills</a></li>
</ul>
<?php
//	Update Password
if ($tab==2) {
	$errorsA=unserialize(base64_decode(urlencode($_REQUEST['e'])));
	if (!is_array($errorsA)) $errorsA=array();

	if (count($errorsA)>0) echo '<div class="error">Please correct the errors in the highlighted fields</div>';

?>
<form name="account" action="" method="post">
	<input name="action" value="pw2" type="hidden" />
	<input name="t" value="<?=$tab;?>" type="hidden" />

	<div class="form_row">
		<div>Current password:</div>
		<div><input name="password" type="password" value=""<?php if (in_array('password',$errorsA)) echo ' class="error"';?> /></div>
	</div>

	<div class="form_row">
		<div>New password:</div>
		<div><input name="password1" type="password" value=""<?php if (in_array('password1',$errorsA)) echo ' class="error"';?> /></div>
	</div>

	<div class="form_row">
		<div>Confirm new password:</div>
		<div><input name="password2" type="password" value=""<?php if (in_array('password2',$errorsA)) echo ' class="error"';?> /></div>
	</div>

	<div class="form_row">
		<div>&nbsp;</div>
		<div><input name="submt" type="submit" value="Update" /></div>
	</div>
</form>

<?php
//	Contact HR
} elseif ($tab==3) {
	$errorsA=unserialize(base64_decode(urlencode($_REQUEST['e'])));
	if (!is_array($errorsA)) $errorsA=array();

	$field=base64_decode(urldecode($_REQUEST['f']));
	if (!in_array($field,array_keys($ufieldsA))) $field='';

	if (count($errorsA)>0) echo '<div class="error">Please correct the errors in the highlighted fields</div>';
	$office=$mysqli->fetch_row("SELECT * FROM office WHERE office_id={$_SESSION['office_id']}");
?>
<form name="account" action="" method="post">
	<input name="action" value="hr2" type="hidden" />
	<input name="t" value="<?=$tab;?>" type="hidden" />
	<input name="f" value="<?=$_REQUEST['f'];?>" type="hidden" />
	<input name="employee_id" value="<?=$_SESSION['employee_id'];?>" type="hidden" />

	<div class="form_row">
		<div>Office contact:</div>
		<div>
			<div><?=stripslashes($office['contact_name']);?></div>
		</div>
	</div>

	<div class="form_row">
		<div>Contact email address:</div>
		<div>
			<div><?=stripslashes($office['contact_email']);?></div>
		</div>
	</div>

	<div class="form_row">
		<div>Contact phone:</div>
		<div>
			<div><?=stripslashes($office['contact_phone']);?></div>
		</div>
	</div>
	<?php
	if (strlen(trim($field))>0) {
	?>
	<div class="form_row">
		<div>Update Field:</div>
		<div>
			<select name="field" id="field" onchange="updateToHr($(this).val());"<?php if (in_array('field',$errorsA)) echo ' class="error"';?>>
			<?php
			foreach ($ufieldsA as $key=>$value) {
				echo '<option value="'.$key.'"';
				if ($key==$field) echo ' selected';
				echo '>'.$value.'</option>';
			}
			?>
			</select>
		</div>
	</div>

	<input name="subjecta" type="hidden" value="<?php if (strlen(trim($field))>0) echo 'Please update my '.$ufieldsA[$field];?>" />
	<?php
	}
	?>

	<div class="form_row">
		<div>Subject:</div>
		<div>
			<input name="subject" type="text"<?php if (strlen(trim($field))>0) echo ' disabled';?> value="<?php if (strlen(trim($field))>0) echo 'Please update my '.$ufieldsA[$field];?>"<?php if (in_array('subject',$errorsA)) echo ' class="error"';?> />
		</div>
	</div>

	<div class="form_row">
		<div>Message:</div>
		<div><textarea name="message"<?php if (in_array('message',$errorsA)) echo ' class="error"';?>><?php
if (strlen(trim($field))>0) echo 'Please update my '.$ufieldsA[$field].' to: [ENTER NEW VALUE HERE].

Your assistance is very much appreciated!';
		?></textarea></div>
	</div>

	<div class="form_row">
		<div>&nbsp;</div>
		<div><input name="submt" type="submit" value="Send" /></div>
	</div>
</form>
<?php
//	Add Skills
} elseif ($tab==4) {
	$skillA=array();
	$rs_rows=$mysqli->query("SELECT E.*,S.skill_name FROM employee_skill E LEFT JOIN skill S ON S.skill_id=E.skill_id WHERE employee_id={$_SESSION['employee_id']} ORDER BY skill_name");
	while ($row=$rs_rows->fetch_assoc()) {
		$skillA[$row['skill_id']]=$row['skill_name'];
	}
?>
<form name="account" action="" method="post">
	<input name="action" value="addskill2" type="hidden" />
	<input name="t" value="<?=$tab;?>" type="hidden" />

	<div class="form_row">
		<div>Skill(s):</div>
		<div>
			<div>
				<div id="allmyskill">
					<div id="newskills"></div>
					<div class="inactive italic" id="noskills">
		<?php 
		if (count($skillA)==0) {
			echo '(no skills defined)</div>';
		} else {
			echo '</div>';
			foreach ($skillA as $skill_id=>$skill_name) {
				echo '<div style="margin-bottom:5px;">';
				echo '<div class="tag" id="newskilltag_'.$skill_id.'">';
				echo stripslashes($skill_name);
				echo '</div>';
				echo '<a href="javascript:void(0)" id="newskilllink_'.$skill_id.'" class="button removeskill" style="margin:-10px 0 0 5px;">&times;</a>';
				echo '<input name="skill_id[]" type="hidden" value="'.$skill_id.'" id="myskill_'.$skill_id.'" class="myskill" />';
				echo '</div>';
			}
		}
		?>		</div>
				<div>
					<a href="javascript:void(0)" class="button" id="myskill" style="margin-top:5px;">Add New Skill</a>
				</div>
			</div>
		</div>
	</div>

	<div class="form_row">
		<div>&nbsp;</div>
		<div><input name="submt" type="submit" value="Save Skills" /></div>
	</div>
</form>
<?
//	Main page
} else {
//	Get office name
	$office=$mysqli->fetch_row("SELECT office_id,office_name,city,state FROM office WHERE office_id={$_SESSION['office_id']}");

	$errorsA=unserialize(base64_decode(urlencode($_REQUEST['e'])));
	if (!is_array($errorsA)) $errorsA=array();

	if (count($errorsA)>0) echo '<div class="error">Please correct the errors in the highlighted fields</div>';
	if ($_REQUEST['msg']=='updated') echo '<div class="success">Account information updated successfully</div>';
?>
<form name="account" action="" method="post">
	<input name="action" value="update2" type="hidden" />
	<input name="t" value="<?=$tab;?>" type="hidden" />

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
			<div><?=stripslashes($_SESSION['employee_id']);?></div>
			<div><a href="?t=3&amp;f=<?=urlencode(base64_encode('employee_id'));?>" class="button">Contact HR for updates</a></div>
		</div>
	</div>

	<div class="form_row">
		<div>First name:</div>
		<div>
			<div><?=stripslashes($_SESSION['first_name']);?></div>
			<div><a href="?t=3&amp;f=<?=urlencode(base64_encode('first_name'));?>" class="button">Contact HR for updates</a></div>
		</div>
	</div>

	<div class="form_row">
		<div>Last name:</div>
		<div>
			<div><?=stripslashes($_SESSION['last_name']);?></div>
			<div><a href="?t=3&amp;f=<?=urlencode(base64_encode('last_name'));?>" class="button">Contact HR for updates</a></div>
		</div>
	</div>

	<div class="form_row">
		<div>Office location:</div>
		<div>
			<div><?=stripslashes($office['office_name'].' - '.$office['office_id'].' ('.$office['city'].', '.$office['state'].')');?></div>
			<div><a href="?t=3&amp;f=<?=urlencode(base64_encode('office_id'));?>" class="button">Contact HR for updates</a></div>
		</div>
	</div>

	<div class="form_row">
		<div>Internal email address:</div>
		<div>
			<div><?=$_SESSION['email_address'];?></div>
			<div><a href="?t=3&amp;f=<?=urlencode(base64_encode('email_address'));?>" class="button">Contact HR for updates</a></div>
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
		<div>Office phone:</div>
		<div><input name="office_phone" type="text" value="<?=stripslashes($_SESSION['office_phone']);?>" /></div>
	</div>

	<div class="form_row">
		<div>Job title:</div>
		<div><input name="job_title" type="text" value="<?=stripslashes($_SESSION['job_title']);?>" /></div>
	</div>

	<div class="form_row">
		<div>Hire date:</div>
		<div>
			<div><?php if (strtotime($_SESSION['hire_date'])) echo date('n/j/Y', strtotime($_SESSION['hire_date']));
			else echo '<span class="italic inactive">(no date entered)</span>';
			?></div>
			<div><a href="?t=3&amp;f=<?=urlencode(base64_encode('hire_date'));?>" class="button">Contact HR for updates</a></div>
		</div>
	</div>

	<div class="form_row">
		<div>Notes:</div>
		<div><textarea name="notes"><?=stripslashes($_SESSION['notes']);?></textarea></div>
	</div>

	<div class="form_row">
		<div>&nbsp;</div>
		<div><input name="submt" type="submit" value="Update" /></div>
	</div>
</form>

<?php
}


include($_SERVER['DOCUMENT_ROOT'].'/includes/footer.php');