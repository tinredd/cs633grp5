<?php
if (isset($_POST['action'])) $action=$_POST['action'];
else $action=null;

if (isset($_POST['t'])) $tab=$_POST['t'];
else $tab=0;

if ($action=='update2' && $tab==1) {
//	Get errors (no error checking necessary)...

	$hr_contact=(isset($_POST['hr_contact']) && $_POST['hr_contact']>0) ? 1 : 0;
	$employee_contact=(isset($_POST['employee_contact']) && $_POST['employee_contact']>0) ? 1 : 0;

//	Compile fields to insert into DB...
	$fieldsA=array();
	$fieldsA['office_phone']=addslashes(strip_tags($_POST['office_phone']));
	$fieldsA['job_title']=addslashes(strip_tags($_POST['job_title']));
	$fieldsA['notes']=addslashes(strip_tags($_POST['notes']));
	$fieldsA['hr_contact']=$hr_contact;
	$fieldsA['employee_contact']=$employee_contact;
	$fieldsA['last_updated']=date('Y-m-d H:i:s');

//	Update DB record...
	$updatesA=array();
	foreach ($fieldsA as $key=>$value) $updatesA[]="$key='".$value."'";

	$sql="UPDATE user SET ".implode(',',$updatesA)." WHERE employee_id={$_SESSION['employee_id']} LIMIT 1";
	$result=$mysqli->query($sql);

//	Assign SESSION elements...
	foreach ($fieldsA as $key=>$value) $_SESSION[$key]=$value;

//	Redirect...
	header('Location: '.APPURL.'account.php?msg=updated'); exit();

} elseif ($action=='pw2' && $tab==2) {
//	Get errors...
	$errorsA=array();
	$match=$mysqli->fetch_value("SELECT COUNT(*) FROM user WHERE password=AES_ENCRYPT('".$_POST['password1']."','".AES_KEY."') AND employee_id={$_SESSION['employee_id']}");

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

		$sql="UPDATE user SET ".implode(',',$updatesA)." WHERE employee_id={$_SESSION['employee_id']} LIMIT 1";
		$result=$mysqli->query($sql);
	}

//	Redirect...
	header('Location: '.APPURL.'account.php?t=2'.$eStr); exit();

//	Contact HR...
} elseif ($action=='hr2' && $tab==3) {
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
	header('Location: '.APPURL.'account.php?t=3'.$eStr.$fStr); exit();

} elseif ($action=='addskill2' && $tab==4) {
	$sql="SELECT GROUP_CONCAT(E.skill_id) FROM employee_skill E LEFT JOIN skill S ON S.skill_id=E.skill_id WHERE employee_id={$_SESSION['employee_id']} AND added_employee_id=0 AND skill_status=1";
	$skillset=explode(',',$mysqli->fetch_value($sql));

	if ((isset($_POST['skill_id']) && count($_POST['skill_id'])==0) || !isset($_POST['skill_id'])) {
		$sql="DELETE FROM employee_skill WHERE employee_id={$_SESSION['employee_id']}";
		$result=$mysqli->query($sql);
	} else {
		$deleteA=$addA=array();

		foreach ($skillset as $skill_id) {
			if (isset($_POST['skill_id']) && !in_array($skill_id,$_POST['skill_id']) && $skill_id>0) $deleteA[]=$skill_id;
		}

		if (isset($_POST['skill_id'])) {
			foreach ($_POST['skill_id'] as $skill_id) {
				if (!in_array($skill_id,$skillset) && $skill_id>0) $addA[]=$skill_id;
			}
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

//	Personal skills
	$sql="SELECT GROUP_CONCAT(E.skill_id) FROM employee_skill E LEFT JOIN skill S ON S.skill_id=E.skill_id WHERE employee_id={$_SESSION['employee_id']} AND added_employee_id={$_SESSION['employee_id']} AND skill_status=2";
	$skillset=explode(',',$mysqli->fetch_value($sql));

	if (isset($_POST['my_skill_id']) && count($_POST['my_skill_id'])==0) {
		$sql="DELETE FROM employee_skill WHERE employee_id={$_SESSION['employee_id']}";
		$result=$mysqli->query($sql);
	} else {
		$deleteA=$addA=array();

		foreach ($skillset as $skill_id) {
			if (isset($_POST['my_skill_id']) && is_array($_POST['my_skill_id']) && !in_array($skill_id,$_POST['my_skill_id']) && $skill_id>0) $deleteA[]=$skill_id;
		}

		if (isset($_POST['my_skill_id'])) {
			foreach ($_POST['my_skill_id'] as $skill_id) {
				if (!in_array($skill_id,$skillset) && $skill_id>0) $addA[]=$skill_id;
			}
		}

		if (count($deleteA)>0) {
			$sql="DELETE FROM employee_skill WHERE employee_id={$_SESSION['employee_id']} AND skill_id IN (".implode(',',$deleteA).")";
			$result=$mysqli->query($sql);

			$sql="DELETE FROM skill WHERE skill_id IN (".implode(',',$deleteA).")";
			$result=$mysqli->query($sql);
		}

		foreach ($addA as $skill_id) {
			$sql="INSERT INTO employee_skill SET employee_id={$_SESSION['employee_id']}, skill_id=$skill_id";
			$result=$mysqli->query($sql);
		}
	}
	header('Location: '.APPURL.'account.php?t=4'); exit();
}
