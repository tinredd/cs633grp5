<?php
$employee_id=(isset($_REQUEST['employee_id']) && $_REQUEST['employee_id']>0)?$_REQUEST['employee_id']:0;

function listErrors($str) {
	$errorsA=unserialize(base64_decode(urldecode($str)));
	if (!is_array($errorsA)) $errorsA=array();

	return $errorsA;
}

function getOffices() {
	global $mysqli;

	$sql="SELECT * FROM office ORDER BY office_name";
	return $mysqli->fetch_rows($sql);
}

function getSkills() {
	global $mysqli;

	$sql="SELECT * FROM skill WHERE skill_status=1 ORDER BY skill_name";
	return $mysqli->fetch_rows($sql);
}

function getMySkills() {
	global $mysqli;

	$sql="SELECT * FROM skill WHERE skill_status=2 AND added_employee_id={$_SESSION['employee_id']} ORDER BY skill_name";
	return $mysqli->fetch_rows($sql);
}

function getDates($postA) {
	if (!isset($postA['start_date'])) {
		$startDate=mktime(0,0,0,date('n'),date('j')+1,date('Y')-30);
		$endDate=mktime(0,0,0,date('n'),date('j'),date('Y'));
	} else {
		$startDate=strtotime($postA['start_date']);
		$endDate=strtotime($postA['end_date']);
	}

	return array($startDate,$endDate);
}

//	Is this person a location contact?
function isContact() {
	global $mysqli;

	$sql="SELECT COUNT(*) FROM office WHERE contact_email='{$_SESSION['email_address']}'";
	return ($mysqli->fetch_value($sql)==1) ? true : false;
}	

//	*******************************************
//	Get a listing of employees
function getListing($postA,$allFlag=1) {
	global $mysqli,$dir;

	$results=array();
	list($startDate,$endDate)=getDates($postA);

	if (!isset($postA['sort'])) $postA['sort']='last_name';
	if (!isset($postA['office_id'])) $postA['office_id']=0;
	if (!isset($postA['skill_id'])) $postA['skill_id']=0;
	if (!isset($postA['ppp'])) $postA['ppp']=0;

	$ppp=(isset($postA['ppp']) && $postA['ppp']>0) ? $postA['ppp'] : 0;
	$pg=(isset($postA['pg']) && $postA['pg']>1) ? $postA['pg'] : 1;
	$start=($pg-1)*$ppp;
	$end=$start+($ppp-1);

	$andA=array();

	$sql="SELECT U.*,GROUP_CONCAT(skill_name) AS skillset,GROUP_CONCAT(added_employee_id) AS who,O.office_name,O.contact_name, O.contact_email,O.city,O.state, 
	IF (U.status=1,'Active','Inactive') AS status,
	DATE_FORMAT(hire_date,'%c/%e/%Y') AS hire_date
	FROM user U 
	LEFT JOIN employee_skill E ON E.employee_id=U.employee_id
	LEFT JOIN skill S ON S.skill_id=E.skill_id
	LEFT JOIN office O ON O.office_id=U.office_id";

	$andA[]="user_type=2";
	if (isset($postA['start_date'])) $andA[]="((hire_date>='".date('Y-m-d',$startDate)."' AND hire_date<='".date('Y-m-d',$endDate)."') OR hire_date IS NULL)";

	$skillA=array();
	if (!is_array($postA['skill_id']) && $postA['skill_id']>0) $skillA[]=$postA['skill_id'];
	if (is_array($postA['skill_id']) && count($postA['skill_id'])>0) {
		foreach ($postA['skill_id'] as $skill_id) if ($skill_id>0) $skillA[]=$skill_id;
	}

	$officeA=array();
	if (isset($postA['office_id']) && !is_array($postA['office_id']) && $postA['office_id']>0) $officeA[]=$postA['office_id'];
	if (isset($postA['office_id']) && is_array($postA['office_id']) && count($postA['office_id'])>0) {
		foreach ($postA['office_id'] as $office_id) if ($office_id>0) $officeA[]=$office_id;
	}

	if (isset($postA['job_title']) && strlen(trim($postA['job_title']))>0) $andA[]="job_title LIKE '".$postA['job_title']."%'";
	if (count($officeA)>0) $andA[]="U.office_id IN(".implode(',',$officeA).")";

	if (is_array($skillA) && count($skillA)>0) $andA[]="U.employee_id IN (SELECT employee_id FROM employee_skill A WHERE A.skill_id IN (".implode(',',$skillA)."))";
	else $andA[]="(S.skill_id IN(SELECT skill_id FROM skill WHERE added_employee_id IN ({$_SESSION['employee_id']},0) AND skill_status>=1) OR S.skill_id IS NULL)";

	$whereA[]=implode(' AND ',$andA);
	if (isContact()) $whereA[]="U.employee_id={$_SESSION['employee_id']}";

	if (count($andA)>0) $sql.=" WHERE (".implode(' OR ',$whereA).")";
	$sql.=" GROUP BY U.employee_id";
	if (isset($postA['sort']) && strlen(trim($postA['sort']))>0) {
		$sql.=" ORDER BY {$postA['sort']} $dir";
	}

	if ($ppp>0 && $allFlag==0) $sql.=" LIMIT $start,$ppp";

	$rs_row=$mysqli->query($sql);
	while ($rs=$rs_row->fetch_assoc()) {
		$results[$rs['employee_id']]=$rs;
	}

	return $results;
}
?>