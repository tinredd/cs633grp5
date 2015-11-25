<?php

function getEmployee ($employee_id) {
	global $mysqli;

	$sql="SELECT U.*,GROUP_CONCAT(S.skill_name) AS skillset,GROUP_CONCAT(E.skill_id) AS skillids 
	FROM user U 
	LEFT JOIN employee_skill E ON E.employee_id=U.employee_id 
	LEFT JOIN skill S ON S.skill_id=E.skill_id 
	WHERE U.employee_id=$employee_id
	GROUP BY E.employee_id
	ORDER BY S.skill_name";

	return $mysqli->fetch_row($sql);
}

function getJob ($job_id) {
	global $mysqli;

	$sql="SELECT U.*,GROUP_CONCAT(S.skill_name) AS skillset,GROUP_CONCAT(E.skill_id) AS skillids,
	GROUP_CONCAT(S.skill_id) AS skillids 
	FROM job U 
	LEFT JOIN job_skill E ON E.job_id=U.job_id 
	LEFT JOIN skill S ON S.skill_id=E.skill_id 
	WHERE U.job_id=$job_id 
	GROUP BY E.job_id
	ORDER BY S.skill_name";

	return $mysqli->fetch_row($sql);
}

function pagination ($postA) {
	$returnStr='';

	$ppp=(isset($postA['ppp']) && $postA['ppp']>0) ? $postA['ppp'] : 0;
	$pg=(isset($postA['pg']) && $postA['pg']>1) ? $postA['pg'] : 1;

	$total=count(getListing($postA));

	if ($ppp>0) $numpages=ceil($total/$ppp);
	else $numpages=1;

	$returnStr.='<div class="standard pagination">';
	for ($i=1; $i<=$numpages; $i++) {
		if ($i!=$pg) $returnStr.='
		<div class="pagenumber">
			<a href="javascript:void(0)" class="page">'.$i.'</a>
		</div>';
		else $returnStr.='
		<div class="pagenumber">
			<span>'.$i.'</span>
		</div>';
	}
	$returnStr.='</div>';

	return $returnStr;
}