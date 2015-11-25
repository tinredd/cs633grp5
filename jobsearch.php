<?php
$title='Job Search';
include_once($_SERVER['DOCUMENT_ROOT'].'/includes/header.php');


include($_SERVER['DOCUMENT_ROOT'].'/app/models/Model.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/app/models/JobModel.php');

$empl=getEmployee($_SESSION['employee_id']);

$action=(isset($_REQUEST['action']))?$_REQUEST['action']:null;
if ($action=='employeesearch2') {
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
<div class="section_title">Matching jobs <span class="bold">(<?=count($jobA);?> result<?php if (count($jobA)!=1) echo 's';?>)</span></div>

<?php
	foreach ($jobA as $rank=>$row) {
?>
<div class="form_row">
    <div>Ranking:</div>
    <div>
        <div>#<?=($rank+1);?></div>
    </div>
</div>

<div class="form_row">
    <div>Job title:</div>
    <div>
        <div><?=stripslashes($row['job_title']);?></div>
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

} elseif (is_null($action)) {

?>  
<script>
$(function() {
	$("#years_experience_slider").slider({
		range: true,
		min: 0,
		max: 25,
		values: [1,5],
		slide: function( event, ui ) {
			$("#years_experience").val(ui.values[0] + " - " + ui.values[1]);
			$('[name="years_experience[0]"]').val(ui.values[0]);
			$('[name="years_experience[1]"]').val(ui.values[1]);
		}
	});
	$("#years_experience").val($("#years_experience_slider").slider("values", 0) +
	" - " + $("#years_experience_slider").slider("values", 1) );
	
	$("#salary_slider").slider({
		range: true,
		min: 5000,
		max: 150000,
		step: 500,
		values: [25000,70000],
		slide: function( event, ui ) {
			$("#salary").val('$'+ui.values[0] + " - $" + ui.values[1]);
			$('[name="salary[0]"]').val(ui.values[0]);
			$('[name="salary[1]"]').val(ui.values[1]);
		}
	});
	$("#salary").val('$'+$("#salary_slider").slider("values", 0) +
	" - $" + $("#salary_slider").slider("values", 1) );
});
</script>

<div>Welcome to the job search page! Please use the filters below to see a list of internal jobs. Your skills and location are pre-selected.</div>

<form name="generic" action="" method="POST" style="margin-top:20px;">
	<input name="action" type="hidden" value="employeesearch2" />
	<input name="status" type="hidden" value="1" />
	<div class="form_row">
		<div>Title:</div>
		<div><input name="title" value="" type="text" /></div>
	</div>

	<div class="form_row">
		<div>Office location:</div>
		<div><?php
		echo '<input name="office_id" value="'.$empl['office_id'].'" type="hidden" />';
		foreach (getOffices() as $row) {
			echo '<div class="inline specialselectmult';
			if ($row['office_id']==$empl['office_id']) echo '-selected';
			echo '" id="office_id_'.$row['office_id'].'">'.stripslashes($row['office_name']).'</div>';
		}
		?></div>
	</div>

	<div class="form_row">
		<div>Required education:</div>
		<div><?php
		foreach ($degreesA as $key=>$degree) {
			echo '<div class="inline specialselectmult" id="degree_'.$key.'">'.stripslashes($degree).'</div>';
		}
		?></div>
	</div>

	<div class="form_row">
		<div>Salary:</div>
		<div>
			<div class="standard">
				<input type="text" id="salary" readonly style="border:0; color:#f6931f; font-weight:bold;">
				<input type="hidden" name="salary[0]" value="25000" />
				<input type="hidden" name="salary[1]" value="70000" />
			</div>
			<div class="standard" id="salary_slider"></div>
		</div>
	</div>

	<div class="form_row">
		<div>Years of experience:</div>
		<div>
			<div class="standard">
				<input type="text" id="years_experience" readonly style="border:0; color:#f6931f; font-weight:bold;" />
				<input type="hidden" name="years_experience[0]" value="1" />
				<input type="hidden" name="years_experience[1]" value="5" />
			</div>
			<div class="standard" id="years_experience_slider"></div>
		</div>
	</div>

	<div class="form_row">
		<div>Skill(s):</div>
		<div><?php
		foreach (explode(',',$empl['skillids']) as $skill_id) {
			echo '<input name="skill_id[]" value="'.$skill_id.'" type="hidden" />';
		}
		foreach (getSkills() as $row) {
			echo '<div class="inline specialselectmult';
			if (in_array($row['skill_id'],explode(',',$empl['skillids']))) echo '-selected';
			echo '" id="skill_id_'.$row['skill_id'].'">'.stripslashes($row['skill_name']).'</div>';
		}
		?></div>
	</div>

	<div class="form_row">
		<div>&nbsp;</div>
		<div><a href="javascript:void(0)" onClick="$(this).parents('form').eq(0).submit();" class="button"><img src="/images/icons/go_search.png" alt="S"/>Search</a></div>
	</div>
</form>
<?php
}
include($_SERVER['DOCUMENT_ROOT'].'/includes/footer.php');