<?php
$employee_id=(isset($_REQUEST['employee_id']) && $_REQUEST['employee_id']>0)?$_REQUEST['employee_id']:0;

function listErrors($str) {
	$errorsA=unserialize(base64_decode(urlencode($str)));
	if (!is_array($errorsA)) $errorsA=array();
}

function getEmployee ($employee_id) {
	global $mysqli;

	$sql="SELECT U.*,GROUP_CONCAT(S.skill_name) AS skillset 
	FROM user U 
	LEFT JOIN employee_skill E ON E.employee_id=U.employee_id 
	LEFT JOIN skill S ON S.skill_id=E.skill_id 
	WHERE U.employee_id=$employee_id
	GROUP BY E.employee_id
	ORDER BY S.skill_name";

	return $mysqli->fetch_row($sql);
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

function getDates($postA) {
	if (!isset($postA['start_date'])) {
		$startDate=mktime(0,0,0,date('n'),date('j')+1,date('Y')-10);
		$endDate=mktime(0,0,0,date('n'),date('j'),date('Y'));
	} else {
		$startDate=strtotime($postA['start_date']);
		$endDate=strtotime($postA['end_date']);
	}

	return array($startDate,$endDate);
}

//	Is this person a location contact?
function isContact($email='') {
	global $mysqli;

	$sql="SELECT COUNT(*) FROM office WHERE contact_email='{$_SESSION['email_address']}'";
	return ($mysqli->fetch_value($sql)==1) ? true : false;
}	

//	*******************************************
//	Get a listing of employees
function getListing($postA) {
	global $mysqli,$dir;

	$results=array();
	list($startDate,$endDate)=getDates($postA);

	$andA=array();

	$sql="SELECT U.*, GROUP_CONCAT(skill_name) AS skillset,O.office_name, 
	IF (U.status=1,'Active','Inactive') AS status,
	DATE_FORMAT(hire_date,'%c/%e/%Y') AS hire_date
	FROM user U 
	LEFT JOIN employee_skill E ON E.employee_id=U.employee_id
	LEFT JOIN skill S ON S.skill_id=E.skill_id
	LEFT JOIN office O ON O.office_id=U.office_id";

	$andA[]="user_type=2";
	$andA[]="((hire_date>='".date('Y-m-d',$startDate)."' AND hire_date<='".date('Y-m-d',$endDate)."') OR hire_date IS NULL)";
	if ($postA['office_id']>0) $andA[]="U.office_id=".$postA['office_id'];
	if ($postA['skill_id']>0) $andA[]="S.skill_id= ".$postA['skill_id'];
	if (strlen(trim($postA['job_title']))>0) $andA[]="job_title LIKE '".$postA['job_title']."%'";

	$whereA[]=implode(' AND ',$andA);
	if ($contact==1) $whereA[]="U.employee_id={$_SESSION['employee_id']}";

	if (count($andA)>0) $sql.=" WHERE (".implode(' OR ',$whereA).")";
	$sql.=" GROUP BY U.employee_id";
	if (strlen(trim($postA['sort']))>0) {
		$sql.=" ORDER BY {$postA['sort']} $dir";
	}

	$rs_row=$mysqli->query($sql);
	while ($rs=$rs_row->fetch_assoc()) {
		$results[$rs['employee_id']]=$rs;
	}

	return $results;
}
?>