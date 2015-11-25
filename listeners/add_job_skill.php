<?php
//	Add a skill to a job
session_start();
include($_SERVER['DOCUMENT_ROOT'].'/includes/includes.php');
include($_SERVER['DOCUMENT_ROOT'].'/app/models/EmployeeModel.php');


$errorsA=array();
$count=$mysqli->fetch_value("SELECT COUNT(*) FROM skill WHERE skill_name LIKE '".addslashes($_POST['skill_name'])."'");

//	Check to see if skill is null or already added...
if (strlen(trim($_POST['skill_name']))==0) $errorsA[]='Enter a skill!';
elseif ($count>0) $errorsA[]='Already added!';

$checkedA=array();
if (strlen(trim($_POST['checked_skill_id']))>0) $checkedA=explode('|',$_POST['checked_skill_id']);

if (count($errorsA)==0) {
	$added_skill_id=$mysqli->insert('skill',array('skill_name'=>"'".addslashes($_POST['skill_name'])."'"));
	$checkedA[]=$added_skill_id;

	foreach ($checkedA as $skill_id) {
		echo '<input name="skill_id[]" type="hidden" value="'.$skill_id.'" />';
	}

	foreach (getSkills() as $skill) {
		echo '<div class="inline specialselectmult';
		if (in_array($skill['skill_id'],$checkedA))  echo '-selected';
		echo '">'.stripslashes($skill['skill_name']).'</div>';
	}
}
else echo '<div class="plainerror">'.implode('<br/>',$errorsA).'</div>';