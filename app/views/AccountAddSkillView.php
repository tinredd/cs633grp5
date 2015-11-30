<?php
//	Add skill
	$skillA=$myskillA=array();
	$sql="SELECT E.*,S.skill_name FROM employee_skill E LEFT JOIN skill S ON S.skill_id=E.skill_id WHERE E.employee_id={$_SESSION['employee_id']} AND S.skill_status=1 ORDER BY skill_name";

	$rs_rows=$mysqli->query($sql);
	while ($row=$rs_rows->fetch_assoc()) {
		$skillA[$row['skill_id']]=$row['skill_name'];
	}
	require_once(DOC_ROOT.'/app/models/EmployeeModel.php');

//	Get personal skills...
	$sql="SELECT * FROM employee_skill E LEFT JOIN skill S ON S.skill_id=E.skill_id WHERE E.employee_id={$_SESSION['employee_id']} AND S.added_employee_id={$_SESSION['employee_id']} AND skill_status=2";

	$rs_rows=$mysqli->query($sql);
	while ($row=$rs_rows->fetch_assoc()) {
		$myskillA[$row['skill_id']]=$row['skill_name'];
	}
?>
<form name="account" action="" method="post">
	<input name="action" value="addskill2" type="hidden" />
	<input name="t" value="<?php echo $tab;?>" type="hidden" />

	<div class="form_row">
		<div>Skill(s):</div>
		<div><?php
	if (count(getSkills())==0) echo '<span class="italic inactive">(no skills added)</span>';
	foreach (array_keys($skillA) as $skill_id) echo '<input name="skill_id[]" type="hidden" value="'.$skill_id.'" />';
	foreach (getSkills() as $i=>$skill) {
		echo '<div class="inline specialselectmult';
		if (in_array($skill['skill_id'],array_keys($skillA)))  echo '-selected';
		echo '" id="skill_id_'.$skill['skill_id'].'">'.stripslashes($skill['skill_name']).'</div>';
	}
		?>
		</div>
	</div>

	<div class="form_row">
		<div>Special skill(s):</div>
		<div>
			<div id="skillcheckblock">
				<div class="standard" id="myallskills">
					<div class="standard"><?php
					if (count(getMySkills())==0) echo '<span class="italic inactive" id="noskills">(no skills added)</span>';
					foreach (array_keys($myskillA) as $skill_id) echo '<input name="my_skill_id[]" type="hidden" value="'.$skill_id.'" />';
					foreach (getMySkills() as $i=>$skill) {
						echo '<div class="inline specialselectmult';
						if (in_array($skill['skill_id'],array_keys($myskillA)))  echo '-selected';
						echo '" id="my_skill_id_'.$skill['skill_id'].'">'.stripslashes($skill['skill_name']).'</div>';
					}
					?></div>
				</div>

				<div class="standard">
					<div id="skillfields"></div>
						
					<div><a href="javascript:void(0)" class="button" id="newskillm" style="margin-top: 10px;">+ Add new skill</a></div>
				</div>
			</div>
		</div>
	</div>

	<div class="form_row">
		<div>&nbsp;</div>
		<div><input name="submt" type="submit" value="Save Skills" /></div>
	</div>
</form>