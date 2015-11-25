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

<div class="standard">Welcome to the employee search page! Please use the filters below to see a list of other employees. Your skills and location are pre-selected.</div>

<form name="generic" action="" method="POST" style="margin-top:20px;">
	<input name="action" type="hidden" value="employeesearch2" />
	<input name="status" type="hidden" value="1" />
	<div class="form_row">
		<div>Job title:</div>
		<div><input name="title" value="" type="text" /></div>
	</div>

	<div class="form_row">
		<div>Office location:</div>
		<div><?php
		echo '<input name="office_id[]" value="'.$empl['office_id'].'" type="hidden" />';
		foreach (getOffices() as $row) {
			echo '<div class="inline specialselectmult';
			if ($row['office_id']==$empl['office_id']) echo '-selected';
			echo '" id="office_id_'.$row['office_id'].'">'.stripslashes($row['office_name']).'</div>';
		}
		?></div>
	</div>

	<div class="form_row">
		<div>Skill(s):</div>
		<div><?php
		$myskillsA=array();
		foreach (getMySkills() as $skill) $myskillsA[]=$skill['skill_id'];

		foreach (explode(',',$empl['skillids']) as $skill_id) {
			if (!in_array($skill_id, $myskillsA)) {
				echo '<input name="skill_id[]" value="'.$skill_id.'" type="hidden" />';
			}
		}
		foreach (getSkills() as $row) {
			echo '<div class="inline specialselectmult';
			if (in_array($row['skill_id'],explode(',',$empl['skillids']))) echo '-selected';
			echo '" id="skill_id_'.$row['skill_id'].'">'.stripslashes($row['skill_name']).'</div>';
		}
		?></div>
	</div>

	<div class="form_row" style="margin-top:0.5em;">
		<div>&nbsp;</div>
		<div><a href="javascript:void(0)" onClick="$(this).parents('form').eq(0).submit();" class="button"><img src="/images/icons/go_search.png" alt="S"/>Search</a></div>
	</div>
</form>