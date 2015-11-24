<?php

if (in_array($action,array('add2','modify2'))) {
	$errorsA=array();

//	Get errors (no error checking necessary)...
	if (strlen(trim($_POST['job_title']))==0) $errorsA[]='job_title';
	if (!($_POST['office_id']>0)) $errorsA[]='office_id';
	if (strlen(trim($_POST['salary']))==0 && !is_numeric($_POST['salary'])) $errorsA[]='salary';
	if (count($_POST['skill_id'])==0) $errorsA[]='skill_id';

	if (count($errorsA)>0) {
		$eStr='action='.substr($action,0,-1);
		if ($action=='modify2') $eStr.='&job_id='.$_POST['job_id'];
		$eStr.='&e='.urlencode(base64_encode(serialize($errorsA)));
	}
	else $eStr='msg=updated';

	if (count($errorsA)==0) {
	//	Compile fields to insert into DB...
		$fieldsA=array();
		$fieldsA['job_title']="'".addslashes(strip_tags($_POST['job_title']))."'";
		$fieldsA['office_id']=$_POST['office_id'];
		$fieldsA['status']=$_POST['status'];
		$fieldsA['years_experience']=$_POST['years_experience'];
		$fieldsA['salary']="'".addslashes(strip_tags($_POST['salary']))."'";
		$fieldsA['degree']="'".addslashes(strip_tags($_POST['degree']))."'";
		$fieldsA['notes']="'".addslashes(strip_tags($_POST['notes']))."'";

	//	Update DB record...
		$updatesA=array();
		foreach ($fieldsA as $key=>$value) $updatesA[]="$key='".$value."'";

		if ($action=='add2') $job_id=$mysqli->insert('job',$fieldsA);
		elseif ($action=='modify2') {
			$mysqli->update('job',$_POST['job_id'],$fieldsA);
			$job_id=$_POST['job_id'];
		}

		$skillset=explode(',',$mysqli->fetch_value("SELECT GROUP_CONCAT(skill_id) FROM job_skill WHERE job_id=$job_id"));

		$deleteA=array_diff($skillset,$_POST['skill_id']);
		$addA=array_diff($_POST['skill_id'],$skillset);

		if (count($deleteA)>0) {
			$sql="DELETE FROM job_skill WHERE job_id=$job_id AND skill_id IN (".implode(',',$deleteA).")";
			$result=$mysqli->query($sql);
		}

		foreach ($addA as $skill_id) {
			$sql="INSERT INTO job_skill SET job_id=$job_id, skill_id=$skill_id";
			$result=$mysqli->query($sql);
		}
	}

//	Redirect...
	header('Location: /job.php?'.$eStr); exit();

} elseif (in_array($action,array('activate2','inactivate2'))) {
	if (!is_array($_POST['job_id'])) $jobA=array(0);
	else $jobA=$_POST['job_id'];

	$status=($action=='inactivate2')?0:1;

	$sql="UPDATE job SET status=$status WHERE job_id IN (".implode(',',$jobA).")";
	$result=$mysqli->query($sql);

	header('Location: /job.php'); exit();

}
