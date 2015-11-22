<?php
$title="Match Jobs";
$bcA['/job.php']='List Jobs';

$job_id=($_REQUEST['job_id']>0)?$_REQUEST['job_id']:0;
if ($job_id==0) { header('Location: /job.php'); exit(); }

include($_SERVER['DOCUMENT_ROOT'].'/includes/header.php');

//  Get job's information...
$sql="SELECT J.*, J.status AS job_status,
    O.office_name,O.city,O.state,
    GROUP_CONCAT(S.skill_name SEPARATOR ', ') AS skillset 
    FROM job J
    LEFT JOIN office O ON O.office_id=J.office_id
    LEFT JOIN job_skill SJ ON SJ.job_id=J.job_id
    LEFT JOIN skill S ON S.skill_id=SJ.skill_id 
    WHERE J.job_id=$job_id";
$row=$mysqli->fetch_row($sql);


?>
<div>Algorithm...</div>
<ul>
<li>One point if the location matches and at least one skill matches</li>
<li>One point for each skills that matches</li>
<li>If there are no skills for the job, the point is awarded for employees with a matching location</li>
<li style="text-decoration:line-through;">One point for each year seniority if either (the employee has one skill in common with the job) or (the job has no skills)</li>
<li>The employees will be shown in reverse order of points</li>
</ul>

<div>Ranking order:</div>
<div><ol>
    <li># of matching skills</li>
    <li>Matching location</li>
    <li># years seniority</li>
</div>

<?php
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
?>
<div class="section_title">Job Details</div>
<div class="form_row">
    <div>Job title:</div>
    <div>
        <div><a href="/job.php?action=modify&amp;job_id=<?=$row['job_id'];?>"><?=stripslashes($row['job_title']);?></a></div>
    </div>
</div>

<div class="form_row">
    <div>Office name:</div>
    <div>
        <div><?=stripslashes($row['office_name'].' - '.$row['office_id'].' ('.$row['city'].', '.$row['state'].')');?></div>
    </div>
</div>

<div class="form_row">
    <div>Salary:</div>
    <div>
        <div><?php
        if ($row['salary']>0) echo '$'.number_format($row['salary'],2);
        else echo '<span class="inactive italic">(none defined)</span>';
        ?></div>
    </div>
</div>

<div class="form_row">
    <div>Experience required:</div>
    <div>
        <div><?php
        if (strlen(trim($row['years_experience']))>0) echo round($row['years_experience'],1).' years';
        else echo '<span class="inactive italic">(none defined)</span>';
        ?></div>
    </div>
</div>

<div class="form_row">
    <div>Education required:</div>
    <div>
        <div><?php
        if (strlen(trim($row['degree']))>0) echo stripslashes($row['degree']);
        else echo '<span class="inactive italic">(none defined)</span>';
        ?></div>
    </div>
</div>

<div class="form_row">
    <div>Skill(s):</div>
    <div>
        <div><?php
        if (strlen(trim($row['skillset']))>0) echo stripslashes($row['skillset']);
        else echo '<span class="inactive italic">(none defined)</span>';
        ?></div>
    </div>
</div>

<div class="form_row" style="margin:10px 25%; width:50%; border-bottom:dotted 2px #AAA;"></div>

<div class="section_title">Matching employees <span class="bold">(<?=count($empA);?> result<?php if (count($empA)!=1) echo 's';?>)</span></div>

<?php foreach ($empA as $rank=>$emp) {
?>
<div class="form_row">
    <div>Ranking:</div>
    <div>
        <div>#<?=($rank+1);?></div>
    </div>
</div>

<div class="form_row">
    <div>Employee name:</div>
    <div>
        <div><a href="/employee.php?action=modify&amp;employee_id=<?=$emp['employee_id'];?>"><?=stripslashes($emp['first_name'].' '.$emp['last_name']);?></a></div>
    </div>
</div>

<div class="form_row">
    <div>Office name:</div>
    <div>
        <div><?=stripslashes($emp['office_name'].' - '.$emp['office_id'].' ('.$emp['city'].', '.$emp['state'].')');?></div>
    </div>
</div>

<div class="form_row">
    <div>Email:</div>
    <div>
        <div><a href="mailto:<?=$emp['email_address'];?>"><?=$emp['email_address'];?></a></div>
        <div><a href="javascript:void(0)" class="button" onClick="alert('This does nothing yet. Must code it!');">Contact this employee</a></div>
    </div>
</div>

<div class="form_row">
    <div>Hire date:</div>
    <div>
        <div><?=date('n/j/Y',strtotime($emp['hire_date']));?> (<?=$emp['years'];?> years)</div>
    </div>
</div>

<div class="form_row">
    <div>Skill(s):</div>
    <div>
        <div><?php
        if (count($emp['skillset'])>0) echo stripslashes(implode(', ',$emp['skillset']));
        else echo '<span class="inactive italic">(none defined)</span>';
        ?></div>
    </div>
</div>
<?php
    if ($rank<count($empA)-1) echo '
        <div class="form_row" style="margin:10px 25%; width:50%; border-bottom:dotted 1px #AAA;">
        </div>';
} ?>

<?php
}
include($_SERVER['DOCUMENT_ROOT'].'/includes/footer.php');
?>