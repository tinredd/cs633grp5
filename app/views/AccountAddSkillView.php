<?php
//	Add skill
	$skillA=array();
	$rs_rows=$mysqli->query("SELECT E.*,S.skill_name FROM employee_skill E LEFT JOIN skill S ON S.skill_id=E.skill_id WHERE employee_id={$_SESSION['employee_id']} ORDER BY skill_name");
	while ($row=$rs_rows->fetch_assoc()) {
		$skillA[$row['skill_id']]=$row['skill_name'];
	}
	include($_SERVER['DOCUMENT_ROOT'].'/app/models/EmployeeModel.php');
?>
<form name="account" action="" method="post">
	<input name="action" value="addskill2" type="hidden" />
	<input name="t" value="<?=$tab;?>" type="hidden" />

	<div class="form_row">
		<div>Skill(s):</div>
		<div><?php


	foreach (array_keys($skillA) as $skill_id) echo '<input name="skill_id[]" type="hidden" value="'.$skill_id.'" />';
	foreach (getSkills() as $i=>$skill) {

		echo '<div class="inline specialselectmult';
		if (in_array($skill['skill_id'],array_keys($skillA)))  echo '-selected';
		echo '" id="skill_id_'.$skill['skill_id'].'">'.stripslashes($skill['skill_name']).'</div>';
	}
		?></div>
	</div>

	<div class="form_row">
		<div>&nbsp;</div>
		<div><input name="submt" type="submit" value="Save Skills" /></div>
	</div>
</form>