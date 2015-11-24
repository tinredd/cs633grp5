<?php
$title='Job Search';
include($_SERVER['DOCUMENT_ROOT'].'/includes/header.php');
include($_SERVER['DOCUMENT_ROOT'].'/app/models/EmployeeModel.php');

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
		}
	});
	$("#salary").val('$'+$("#salary_slider").slider("values", 0) +
	" - $" + $("#salary_slider").slider("values", 1) );
});
</script>
<div>Welcome to the job search page! Please use the filters below to see a list of internal jobs.</div>

<form name="generic" action="" method="POST" style="margin-top:20px;">
	<div class="form_row">
		<div>Title:</div>
		<div><input name="title" value="" type="text" /></div>
	</div>

	<div class="form_row">
		<div>Office location:</div>
		<div><?php
		foreach (getOffices() as $row) {
			echo '<div class="inline specialselect">'.stripslashes($row['office_name']).'</div>';
		}
		?></div>
	</div>

	<div class="form_row">
		<div>Required education:</div>
		<div><?php
		foreach ($degreesA as $degree) {
			echo '<div class="inline specialselect">'.stripslashes($degree).'</div>';
		}
		?></div>
	</div>

	<div class="form_row">
		<div>Salary:</div>
		<div>
			<div class="standard">
				<input type="text" id="salary" name="salary" readonly style="border:0; color:#f6931f; font-weight:bold;">
			</div>
			<div class="standard" id="salary_slider"></div>
		</div>
	</div>

	<div class="form_row">
		<div>Years of experience:</div>
		<div>
			<div class="standard">
				<input type="text" id="years_experience" name="years_experience" readonly style="border:0; color:#f6931f; font-weight:bold;">
			</div>
			<div class="standard" id="years_experience_slider"></div>
		</div>
	</div>

	<div class="form_row">
		<div>Skill:</div>
		<div><?php
		foreach (getSkills() as $row) {
			echo '<div class="inline specialselect">'.stripslashes($row['skill_name']).'</div>';
		}
		?></div>
	</div>

	<div class="form_row">
		<div>&nbsp;</div>
		<div><a href="javascript:$(this).submit()" class="button"><img src="/images/icons/go_search.png" alt="S"/>Search</a></div>
	</div>
</form>
<?php
include($_SERVER['DOCUMENT_ROOT'].'/includes/footer.php');