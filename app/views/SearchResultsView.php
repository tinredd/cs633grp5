<?php
	$jobsA=getListing($_POST);

	$jobA=array();
	foreach ($jobsA as $job) {
		$points=0;
		if ($job['office_id']==$empl['office_id']) $points++;
		$commonskillset=array_intersect(explode(',',$empl['skillids']),explode(',',$job['skillids']));
		$points+=count($commonskillset);

		if ((count(explode(',',$job['skillids']))==0 || count($commonskillset)>0) && $job['office_id']==$empl['office_id']) $points++;

		$pointsA[]=$points;
		$salaryA[]=$job['salary'];
		$degreeA[]=$job['degree'];
		$yearsA[]=$job['years_experience'];

		$jobA[$job['job_id']]=$job;
		$jobA[$job['job_id']]['skillids']=explode(',',$job['skillids']);
		$jobA[$job['job_id']]['skillset']=explode(',',$job['skillset']);
		$jobA[$job['job_id']]['points']=$points;

	}

	array_multisort($pointsA,SORT_DESC,$salaryA,SORT_DESC,$yearsA,SORT_ASC,$degreeA,SORT_ASC,$jobA);
?>
<div class="section_title">Matching Jobs <span>(<?=count($jobA);?>)</span></div>

<?php foreach ($jobA as $rank=>$row) { ?>

<div class="form_row title_row">
    <div>
        <div><?=($rank+1);?></div>
    </div>
    <div>
        <div><a href="javascript:void(0)"><?=stripslashes($row['job_title']);?></a></div>
    </div>
</div>

<div class="form_row">
    <div>Office name:</div>
    <div>
        <div><?=stripslashes($row['office_name'].' - '.$row['office_id'].' ('.$row['city'].', '.$row['state'].')');?></div>
    </div>
</div>

<div class="form_row">
    <div>Office contact:</div>
    <div>
		<div><?=stripslashes($row['contact_name']);?></div>
		<div><a href="mailto:<?=$row['contact_email'];?>" class="button">Contact HR Department</a></div>
    </div>
</div>

<div class="form_row">
    <div>Salary:</div>
    <div>
        <div><?php;
        if (strlen($row['salary'])>0) echo $row['salary'];
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
        if (count($row['skillset'])>0) echo stripslashes(implode(', ',$row['skillset']));
        else echo '<span class="inactive italic">(none defined)</span>';
        ?></div>
    </div>
</div>
<?php
	}
    if ($rank<count($empA)-1) echo '
        <div class="form_row" style="margin:10px 25%; width:50%; border-bottom:dotted 1px #AAA;">
        </div>';
