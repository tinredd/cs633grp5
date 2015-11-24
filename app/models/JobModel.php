<?php
$job_id=(isset($_REQUEST['job_id']) && $_REQUEST['job_id']>0)?$_REQUEST['job_id']:0;

function listErrors($str) {
	$errorsA=unserialize(base64_decode(urlencode($str)));
	if (!is_array($errorsA)) $errorsA=array();
}

function getJob ($job_id) {
	global $mysqli;

	$sql="SELECT U.*,GROUP_CONCAT(S.skill_name) AS skillset,
	GROUP_CONCAT(S.skill_id) AS skillids 
	FROM job U 
	LEFT JOIN job_skill E ON E.job_id=U.job_id 
	LEFT JOIN skill S ON S.skill_id=E.skill_id 
	WHERE U.job_id=$job_id 
	GROUP BY E.job_id
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
	CONCAT('$',FORMAT(salary,2)) AS salary, 
	IF (U.status=1,'Active','Inactive') AS status 
	FROM job U
	LEFT JOIN job_skill E ON E.job_id=U.job_id
	LEFT JOIN skill S ON S.skill_id=E.skill_id
	LEFT JOIN office O ON O.office_id=U.office_id";

//	Compose filters...
	if ($postA['office_id']>0) $andA[]="U.office_id=".$postA['office_id'];
	if ($postA['skill_id']>0) $andA[]="E.skill_id= ".$postA['skill_id'];
	if (strlen(trim($postA['job_title']))>0) $andA[]="job_title LIKE '".$postA['job_title']."%'";

//	if there is at least one filter, add a 'WHERE' clause to the query
	if (count($andA)>0) $sql.=" WHERE ".implode(' AND ',$andA);
	$sql.=" GROUP BY U.job_id";

//	Use an ORDER BY if there is a 'sort' field	
	if (strlen(trim($postA['sort']))>0) {
		$sql.=" ORDER BY {$postA['sort']} $dir";
	}

	$rs_row=$mysqli->query($sql);
	while ($rs=$rs_row->fetch_assoc()) {
		$results[$rs['job_id']]=$rs;
	}

	return $results;
}
?>