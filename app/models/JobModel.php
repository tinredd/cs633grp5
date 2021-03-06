<?php
$job_id=(isset($_REQUEST['job_id']) && $_REQUEST['job_id']>0)?$_REQUEST['job_id']:0;

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

	if (!isset($postA['sort'])) $postA['sort']='job_title';
	if (!isset($postA['office_id'])) $postA['office_id']=0;
	if (!isset($postA['skill_id'])) $postA['skill_id']=0;
	if (!isset($postA['ppp'])) $postA['ppp']=0;
	if (!isset($postA['job_title'])) $postA['job_title']='';
	if (!isset($postA['status'])) $postA['status']=null;
	if (!isset($postA['degree'])) $postA['degree']='';
	if (!isset($postA['salary'])) $postA['salary']='';
	if (!isset($postA['years_experience'])) $postA['years_experience']='';

	$ppp=(isset($postA['ppp'])) ? $postA['ppp'] : 0;
	$pg=(isset($postA['pg'])) ? $postA['pg'] : 1;

	$andA=array();

	$sql="SELECT *,GROUP_CONCAT(skill_name) AS skillset,GROUP_CONCAT(E.skill_id) AS skillids,O.office_name,O.contact_name, O.contact_email,
	CONCAT('$',FORMAT(salary,2)) AS salary, 
	IF (U.status=1,'Active','Inactive') AS status 
	FROM job U
	LEFT JOIN job_skill E ON E.job_id=U.job_id
	LEFT JOIN skill S ON S.skill_id=E.skill_id
	LEFT JOIN office O ON O.office_id=U.office_id";

	$skillA=array();
	if (!is_array($postA['skill_id']) && $postA['skill_id']>0) $skillA[]=$postA['skill_id'];
	if (is_array($postA['skill_id']) && count($postA['skill_id'])>0) {
		foreach ($postA['skill_id'] as $skill_id) if ($skill_id>0) $skillA[]=$skill_id;
	}

	$officeA=array();
	if (!is_array($postA['office_id']) && $postA['office_id']>0) $officeA[]=$postA['office_id'];
	if (is_array($postA['office_id']) && count($postA['office_id'])>0) {
		foreach ($postA['office_id'] as $office_id) if ($office_id>0) $officeA[]=$office_id;
	}

	$degreeA=array();
	if (!is_array($postA['degree']) && $postA['degree']>0) $degreeA[]=$postA['degree'];
	if (is_array($postA['degree']) && count($postA['degree'])>0) {
		foreach ($postA['degree'] as $degree) if ($degree>0) $degreeA[]=$degree;
	}

//	Compose filters...
	if (count($officeA)>0) $andA[]="U.office_id IN(".implode(',',$officeA).")";
	if (count($degreeA)>0) $andA[]="U.degree IN(".implode(',',$degreeA).")";
	if (isset($postA['status']) && $postA['status']>-1) $andA[]="U.status=".$postA['status'];
	if (isset($postA['job_title']) && strlen(trim($postA['job_title']))>0) $andA[]="job_title LIKE '".$postA['job_title']."%'";

	if (is_array($skillA) && count($skillA)>0) $andA[]="U.job_id IN (SELECT job_id FROM job_skill A WHERE A.skill_id IN (".implode(',',$skillA)."))";
	else $andA[]="(S.skill_id IN(SELECT skill_id FROM skill WHERE added_employee_id IN ({$_SESSION['employee_id']},0) AND skill_status>=1) OR S.skill_id IS NULL)";

	if (is_array($postA['salary'])) $andA[]="salary BETWEEN {$postA['salary'][0]} AND {$postA['salary'][1]}";
	if (is_array($postA['years_experience'])) $andA[]="years_experience BETWEEN {$postA['years_experience'][0]} AND {$postA['years_experience'][1]}";

//	if there is at least one filter, add a 'WHERE' clause to the query
	if (count($andA)>0) $sql.=" WHERE ".implode(' AND ',$andA);
	$sql.=" GROUP BY E.job_id";

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