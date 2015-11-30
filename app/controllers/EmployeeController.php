<?php

if (in_array($action,array('add2','modify2'))) {
	$errorsA=array();

//	Get errors (no error checking necessary)...
	if (strlen(trim($employee_id))==0) $errorsA[]='employee_id';
	if (!is_numeric($employee_id) && strlen(trim($employee_id))!=7) $errorsA[]='employee_id';
	if (strlen(trim($_POST['first_name']))==0) $errorsA[]='first_name';
	if (strlen(trim($_POST['last_name']))==0) $errorsA[]='last_name';
	if (!($_POST['office_id']>0)) $errorsA[]='office_id';
	if (!strtotime($_POST['hire_date'])) $errorsA[]='hire_date';
	if (strlen(trim($_POST['email_address']))==0) $errorsA[]='email_address';
	if (!validEmail($_POST['email_address'])) $errorsA[]='email_address';

	if (count($errorsA)>0) {
		$eStr='action='.substr($action,0,-1);
		if ($action=='modify2') $eStr.='&employee_id='.$employee_id;
		$eStr.='&e='.urlencode(base64_encode(serialize($errorsA)));
	}
	else $eStr='msg=updated';

	if (count($errorsA)==0) {
	//	Compile fields to insert into DB...
		$fieldsA=array();
		$fieldsA['employee_id']="'".addslashes(strip_tags($employee_id))."'";
		$fieldsA['first_name']="'".addslashes(strip_tags($_POST['first_name']))."'";
		$fieldsA['last_name']="'".addslashes(strip_tags($_POST['last_name']))."'";
		$fieldsA['office_id']=$_POST['office_id'];
		$fieldsA['hire_date']="'".date('Y-m-d',strtotime($_POST['hire_date']))."'";
		$fieldsA['email_address']="'".addslashes(strip_tags($_POST['email_address']))."'";
		$fieldsA['office_phone']="'".addslashes(strip_tags($_POST['office_phone']))."'";
		$fieldsA['job_title']="'".addslashes(strip_tags($_POST['job_title']))."'";
		$fieldsA['status']=$_POST['status'];
		if ($action=='add2') $fieldsA['password']="AES_ENCRYPT('cs633grp5','".AES_KEY."')";
		$fieldsA['last_updated']="'".date('Y-m-d H:i:s')."'";

	//	Update DB record...
		$updatesA=array();
		foreach ($fieldsA as $key=>$value) $updatesA[]="$key=$value";

		if ($action=='add2') $sql="INSERT INTO user SET ".implode(',',$updatesA);
		elseif ($action=='modify2') $sql="UPDATE user SET ".implode(',',$updatesA)." WHERE employee_id={$employee_id} LIMIT 1";
		$result=$mysqli->query($sql);
	}

//	Redirect...
	header('Location: '.APPURL.'employee.php?'.$eStr); exit();

} elseif (in_array($action,array('activate2','inactivate2'))) {
	if (!is_array($employee_id)) $employeeA=array(0);
	else $employeeA=$employee_id;

	$status=($action=='inactivate2')?0:1;

	$sql="UPDATE user SET status=$status WHERE employee_id IN (".implode(',',$employeeA).")";
	$result=$mysqli->query($sql);

	header('Location: '.APPURL.'employee.php'); exit();
}