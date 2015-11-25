<?php
//	Add a skill to a job
session_start();
include($_SERVER['DOCUMENT_ROOT'].'/includes/includes.php');
include($_SERVER['DOCUMENT_ROOT'].'/app/models/EmployeeModel.php');

$errorsA=array();
if ($_POST['a']==1) $sql="SELECT COUNT(*) FROM skill WHERE skill_name LIKE '".addslashes($_POST['skill_name'])."' AND added_employee_id=0";
elseif ($_POST['a']==2) $sql="SELECT COUNT(*) FROM skill WHERE skill_name LIKE '".addslashes($_POST['skill_name'])."' AND added_employee_id IN (0,{$_SESSION['employee_id']})";
$count=$mysqli->fetch_value($sql);

//	Check to see if skill is null or already added...
if (strlen(trim($_POST['skill_name']))==0) $errorsA[]='Enter a skill!';
elseif ($count>0) $errorsA[]='Already added!';

$checkedA=array();
if (strlen(trim($_POST['checked_skill_id']))>0) $checkedA=explode('|',$_POST['checked_skill_id']);

if (count($errorsA)==0) {
	$fieldsA['skill_name']="'".addslashes($_POST['skill_name'])."'";
	if ($_POST['a']==2) {
		$fieldsA['added_employee_id']=$_SESSION['employee_id'];
		$fieldsA['skill_status']=2;
	}

	$added_skill_id=$mysqli->insert('skill',$fieldsA);
	$checkedA[]=$added_skill_id;

	foreach ($checkedA as $skill_id) {
		echo '<input name="'.(($_POST['a']==2)?'my_':'').'skill_id[]" type="hidden" value="'.$skill_id.'" />';
	}

	if ($_POST['a']==1) $skA=getSkills();
	elseif ($_POST['a']==2) $skA=getMySkills();

	foreach ($skA as $skill) {
		echo '<div class="inline specialselectmult';
		if (in_array($skill['skill_id'],$checkedA))  echo '-selected';
		echo '">'.stripslashes($skill['skill_name']).'</div>';
	}
}
else echo '<div class="plainerror">'.implode('<br/>',$errorsA).'</div>';