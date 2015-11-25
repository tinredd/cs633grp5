<?php
$title="Match Jobs";
$bcA['/job.php']='List Jobs';

$job_id=($_REQUEST['job_id']>0)?$_REQUEST['job_id']:0;
if ($job_id==0) { header('Location: /job.php'); exit(); }

include($_SERVER['DOCUMENT_ROOT'].'/includes/header.php');
include($_SERVER['DOCUMENT_ROOT'].'/app/models/Model.php');

//  Get job's information...
$row=getJob($job_id);

if ($row['job_status']==0) echo '<div class="error">This job is no longer active! <a href="/job.php">Search again</a></div>';
else {

//  Get the job's skills...
    $job_skillA=explode(',',$mysqli->fetch_value("SELECT GROUP_CONCAT(skill_id) FROM job_skill WHERE job_id=$job_id"));
    if (count($job_skillA)==0) $job_skillA=array();

    if (count($job_skillA)>0) {
        $sql="SELECT U.*,O.office_name,O.city,O.state,
        GROUP_CONCAT(S.skill_name) AS skillset, 
        GROUP_CONCAT(E.skill_id) AS skills 
        FROM employee_skill E 
        LEFT JOIN user U ON U.employee_id=E.employee_id
        LEFT JOIN office O ON O.office_id=U.office_id 
        LEFT JOIN skill S ON S.skill_id=E.skill_id 
        WHERE E.skill_id IN (".implode(',',$job_skillA).") AND U.status=1
        GROUP BY E.employee_id
        ORDER BY hire_date ASC";
    } else {
        $sql="SELECT U.*,O.office_name,O.city,O.state, 
        GROUP_CONCAT(S.skill_name) AS skillset, 
        GROUP_CONCAT(E.skill_id) AS skills 
        FROM user U 
        LEFT JOIN employee_skill E ON E.employee_id=U.employee_id 
        LEFT JOIN office O ON O.office_id=U.office_id 
        LEFT JOIN skill S ON S.skill_id=E.skill_id 
        WHERE office_id={$row['office_id']} AND U.status=1
        GROUP BY E.employee_id
        ORDER BY hire_date ASC";
    }

    $empA=$pointsA=$yearsA=array();
    $rs_row=$mysqli->query($sql);

    while ($emp=$rs_row->fetch_assoc()) {
        $points=0;

        $commonskillset=array_intersect(explode(',',$emp['skills']),$job_skillA);
        $points+=count($commonskillset);

        if ((count($job_skillA)==0 || count($commonskillset)>0) && $row['office_id']==$emp['office_id']) $points++;

        $years=round((strtotime('today')-strtotime($emp['hire_date']))/(365*86400),1);

        $pointsA[]=$points;
        $yearsA[]=$years;

        $empA[$emp['employee_id']]=$emp;
        $empA[$emp['employee_id']]['points']=$points;
        $empA[$emp['employee_id']]['skills']=explode(',',$emp['skills']);
        $empA[$emp['employee_id']]['skillset']=explode(',',$emp['skillset']);
        $empA[$emp['employee_id']]['years']=$years;

        unset($empA[$emp['employee_id']]['password']);
    }

    array_multisort($pointsA,SORT_DESC,$yearsA,SORT_DESC,$empA);
    include($_SERVER['DOCUMENT_ROOT'].'/app/views/JobMatchJobView.php');

    echo '<div class="form_row" style="margin:10px 25%; width:50%; border-bottom:dotted 2px #AAA;"></div>';

    include($_SERVER['DOCUMENT_ROOT'].'/app/views/JobMatchResultsView.php');
}

include($_SERVER['DOCUMENT_ROOT'].'/includes/footer.php');