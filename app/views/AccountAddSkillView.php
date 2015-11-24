<?php
//	Add skill
	$skillA=array();
	$rs_rows=$mysqli->query("SELECT E.*,S.skill_name FROM employee_skill E LEFT JOIN skill S ON S.skill_id=E.skill_id WHERE employee_id={$_SESSION['employee_id']} ORDER BY skill_name");
	while ($row=$rs_rows->fetch_assoc()) {
		$skillA[$row['skill_id']]=$row['skill_name'];
	}
?>
<form name="account" action="" method="post">
	<input name="action" value="addskill2" type="hidden" />
	<input name="t" value="<?=$tab;?>" type="hidden" />

	<div class="form_row">
		<div>Skill(s):</div>
		<div>
			<div>
				<div id="allmyskill">
					<div id="newskills"></div>
					<div class="inactive italic" id="noskills">
		<?php 
		if (count($skillA)==0) {
			echo '(no skills defined)</div>';
		} else {
			echo '</div>';
			foreach ($skillA as $skill_id=>$skill_name) {
				echo '<div style="margin-bottom:5px;">';
				echo '<div class="tag" id="newskilltag_'.$skill_id.'">';
				echo stripslashes($skill_name);
				echo '</div>';
				echo '<a href="javascript:void(0)" id="newskilllink_'.$skill_id.'" class="button removeskill" style="margin:-10px 0 0 5px;">&times;</a>';
				echo '<input name="skill_id[]" type="hidden" value="'.$skill_id.'" id="myskill_'.$skill_id.'" class="myskill" />';
				echo '</div>';
			}
		}
		?>		</div>
				<div>
					<a href="javascript:void(0)" class="button" id="myskill" style="margin-top:5px;">Add New Skill</a>
				</div>
			</div>
		</div>
	</div>

	<div class="form_row">
		<div>&nbsp;</div>
		<div><input name="submt" type="submit" value="Save Skills" /></div>
	</div>
</form>