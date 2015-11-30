<?php

function js ($value1=0, $value2=0) {
	$returnStr='';
	if (!is_numeric($value1)) $value1=0;
	if (!is_numeric($value2)) $value2=0;

	$returnStr.='
<script>
$(function() {
	$("#years_experience_slider").slider({
		min: 0,
		max: 25,
		step: 0.5,
		value: '.$value1.',
		slide: function(event, ui) {
			$("#years_experience").val(ui.value+" years");
			$(\'[name="years_experience"]\').val(ui.value);
		}
	});
	$("#years_experience").val($("#years_experience_slider").slider("values",0)+" years");
	
	$("#salary_slider").slider({
		min: 5000,
		max: 150000,
		step: 500,
		value: '.$value2.',
		slide: function(event, ui) {
			$("#salary").val("$"+ui.value);
			$(\'[name="salary"]\').val(ui.value);
		}
	});
	$("#salary").val("$"+$("#salary_slider").slider("values",0));
});
</script>';

	return $returnStr;
}
function form($job_id=0,$errorStr='',$action='add') {
	global $degreesA;

	$returnStr='';
	$errorsA=listErrors($errorStr);
	if (count($errorsA)>0) $returnStr.='<div class="error">Please correct the errors in the highlighted fields!</div>';

	$row=getJob($job_id);
	$returnStr.=js($row['years_experience'],$row['salary']);
	$returnStr.='
	<form name="account" action="" method="post">
	<input name="action" value="'.$action.'2" type="hidden" />
	<input name="job_id" value="'.$job_id.'" type="hidden" />

	<div class="form_row">
		<div><span class="required">*</span> Job title:</div>
		<div><input name="job_title" type="text" value="'.stripslashes($row['job_title']).'"';

	if (in_array('job_title',$errorsA)) $returnStr.=' class="error"';
	$returnStr.='
	/></div>
	</div>

	<div class="form_row">
		<div><span class="required">*</span> Degree:</div>
		<div>';

	$returnStr.='<input name="degree" type="hidden" value="'.$row['degree'].'" />';
	foreach ($degreesA as $key=>$value) {

		$returnStr.='<div class="inline specialselect';
		if ($row['degree']==$key)  $returnStr.='-selected';
		$returnStr.='" id="degree_'.$key.'">'.stripslashes($value).'</div>';
	}

	$returnStr.='
		</div>
	</div>

	<div class="form_row">
		<div>Salary:</div>
		<div>
			<div class="standard">
				<input type="text" id="salary" readonly style="border:0; color:#76B33C; font-weight:bold;" />
				<input type="hidden" name="salary" value="'.$row['salary'].'" />
			</div>
			<div class="standard" id="salary_slider"></div>
		</div>
	</div>

	<div class="form_row">
		<div><span class="required">*</span> Office location:</div>
		<div>';

	$returnStr.='<input name="office_id" type="hidden" value="'.$row['office_id'].'" />';
	foreach (getOffices() as $office) {

		$returnStr.='<div class="inline specialselect';
		if ($office['office_id']==$row['office_id'])  $returnStr.='-selected';
		$returnStr.='" id="office_id_'.$office['office_id'].'">'.stripslashes($office['office_name']).'</div>';
	}
	$returnStr.='
		</div>
	</div>

	<div class="form_row">
		<div><span class="required">*</span> Years experience:</div>
		<div>
			<div class="standard">
				<input type="text" id="years_experience" readonly style="border:0; color:#76B33C; font-weight:bold;" />
				<input type="hidden" name="years_experience" value="'.$row['years_experience'].'" />
			</div>
			<div class="standard" id="years_experience_slider"></div>
		</div>
	</div>

	<div class="form_row">
		<div>Notes:</div>
		<div>
			<div><textarea name="notes" style="width:75%;">'.stripslashes($row['notes']);
	$returnStr.='</textarea></div>
		</div>
	</div>

	<div class="form_row">
		<div>Skills:</div>
		<div>
			<div id="skillcheckblock">
				<div class="standard" id="allskills">';

	foreach (explode(',',$row['skillids']) as $skill_id) $returnStr.='<input name="skill_id[]" type="hidden" value="'.$skill_id.'" />';
	foreach (getSkills() as $i=>$skill) {

		$returnStr.='<div class="inline specialselectmult';
		if (in_array($skill['skill_id'],explode(',',$row['skillids'])))  $returnStr.='-selected';
		$returnStr.='" id="skill_id_'.$skill['skill_id'].'">'.stripslashes($skill['skill_name']).'</div>';
	}

	$returnStr.='
				</div>

				<div id="skillfields"></div>
				
				<div><a href="javascript:void(0)" class="button" id="newskill" style="margin-top: 10px;">Add new skill</a></div>
			</div>
		</div>
	</div>

	<div class="form_row">
		<div>Status:</div>
		<div>
			<select name="status"';
	if (in_array('status',$errorsA)) $returnStr.=' class="error"';
	$returnStr.='>
				<option value="1">Active</option>
				<option value="0"';
	if (strlen(trim($row['status']))>0 && $row['status']=='0') $returnStr.=' selected';
	$returnStr.='
			>Inactive</option>
			</select></div>
	</div>

	<div class="form_row">
		<div>&nbsp;</div>
		<div><input name="submt" type="submit" value="Update" /></div>
	</div>
</form>
	';
	return $returnStr;
}

function actionform() {
	$returnStr='';

	$returnStr.='
	<div class="standard" style="width:98%;">
		<div class="inline" style="width:40%;">
			<select name="action">
				<option value="0">(select an action)</option>
				<option value="inactivate2">Inactivate selected</option>
				<option value="activate2">Activate selected</option>
			</select>&nbsp;
			<input name="submt" type="submit" value="Update" class="button" style="margin-top:-10px;" />
		</div>
		<div class="inline right" style="float:right;">
			<a href="?action=add" class="button">+&nbsp;Add Job</a>
		</div>
	</div>';

	return $returnStr;	
}

function filterform($postA,$dir) {
	list($startDate,$endDate)=getDates($postA);

	$returnStr='';
	$returnStr.='<form name="filter" action="" method="POST">
	<input name="sort" type="hidden" value="'.$postA['sort'].'" />
	<input name="dir" type="hidden" value="'.$dir.'" />

	<div class="filterbox">
		<div>
			<div class="filterlabel">Title</div>
			<div><input name="job_title" type="text" value="'.stripslashes($postA['job_title']).'" /></div>
		</div>

		<div>
			<div class="filterlabel">Office</div>
			<div>
				<select name="office_id">
					<option value="0">(all offices)</option>';

					foreach (getOffices() as $row) {
						$returnStr.='<option value="'.$row['office_id'].'"';
						if ($row['office_id']==$postA['office_id']) $returnStr.=' selected';
						$returnStr.='>'.stripslashes($row['office_name']).'</option>';
					}

	$returnStr.='
				</select>
			</div>
		</div>

		<div>
			<div class="filterlabel">Skill</div>
			<div>
				<select name="skill_id">
					<option value="0">(all skills)</option>';

					foreach (getSkills() as $row) {
						$returnStr.='<option value="'.$row['skill_id'].'"';
						if ($row['skill_id']==$postA['skill_id']) $returnStr.=' selected';
						$returnStr.='>'.stripslashes($row['skill_name']).'</option>';
					}

	$returnStr.='
				</select>
			</div>
		</div>

		<div>
			<div class="filterlabel">Status</div>
			<div>
				<select name="status">
					<option value="-1"';
					if ($postA['status']==-1) $returnStr.=' selected';
					$returnStr.='>(all)</option>
					<option value="1"';
					if ($postA['status']==1) $returnStr.=' selected';
					$returnStr.='Active</option>
					<option value="0"';
					if ($postA['status']==0) $returnStr.=' selected';
					$returnStr.='Inactive</option>
				</select>
			</div>
		</div>

		<div>
			<div class="filterlabel">Results per page</div>
			<div>
				<select name="ppp">
					<option value="0"'.(($postA['ppp']==0)?' selected':'').'>(all results)</option>
					<option value="1"'.(($postA['ppp']==1)?' selected':'').'>1</option>
					<option value="2"'.(($postA['ppp']==2)?' selected':'').'>2</option>
					<option value="5"'.(($postA['ppp']==5)?' selected':'').'>5</option>
					<option value="10"'.(($postA['ppp']==10)?' selected':'').'>10</option>
					<option value="20"'.(($postA['ppp']==20)?' selected':'').'>20</option>
					<option value="25"'.(($postA['ppp']==25)?' selected':'').'>25</option>
					<option value="50"'.(($postA['ppp']==50)?' selected':'').'>50</option>
				</select>
			</div>
		</div>

		<div>
			<input name="submt" type="submit" value="Search" />
		</div>
	</div>
</form>';

	return $returnStr;
}


function tabularize($postA,$dir,$columns=array()) {
	global $degreesA;

	list($startDate,$endDate)=getDates($postA);
	$returnStr='';
	$returnStr.=filterform($postA,$dir);

	$pg=(isset($postA['pg']) && $postA['pg']>1) ? $postA['pg'] : 1;
	$ppp=(isset($postA['ppp']) && $postA['ppp']>0) ? $postA['ppp'] : 0;
	$start=($pg-1)*$ppp + 1;
	$end=$start+($ppp-1);

	if ($end>count(getListing($postA))) $end=count(getListing($postA));
	if ($end==0) $end=count(getListing($postA));

	$pg=($postA['pg']>0) ? $postA['pg'] : 1;

	$returnStr.='

<form name="generic" action="" method="POST">';
	foreach ($postA as $key=>$value) {
		if (is_array($value)) {
			foreach ($value as $v) $returnStr.='<input name="'.$key.'[]" type="hidden" value="'.$v.'" />';
		} else {
			$returnStr.='<input name="'.$key.'" type="hidden" value="'.$value.'" />';
		}
	}
	$returnStr.='<input name="pg" type="hidden" value="'.$pg.'" />';

	$returnStr.='
	<table>
		<tr>
			<th class="label" colspan="'.(count($columns)+2).'">Jobs '.$start.' - '.$end.' of '.count(getListing($postA)).'</th>
		</tr>
		<tr>
			<th><a href="javascript:checkAll(\'job_id[]\')">Select</a></th>';

	foreach ($columns as $field=>$label) {
		$returnStr.='
		<th>
			<a href="javascript:void(0)" class="sort" id="sort_'.$field.'">';
		$returnStr.=stripslashes($label);
		$returnStr.='</a>
		</th>';
	}
	$returnStr.='
			<th>Action(s):</th>';

	$returnStr.='
	</tr>
	<tbody>';
	if (count(getListing($postA))==0) {
			$returnStr.='
		<tr>
			<td class="inactive italic" colspan='.(count($columns)+2).'>(none)</td>
		</tr>';
	}

	foreach (getListing($postA) as $job_id=>$row) {
		$returnStr.='<tr';
		if ($row['status']=='Inactive') $returnStr.=' class="inactive"';
		$returnStr.='>';
		$returnStr.='
			<td class="center"><input name="job_id[]" type="checkbox" value="'.$row['job_id'].'" /></td>';

		$key=0;
		foreach ($columns as $field=>$label) {
			$returnStr.='
			<td>';
			if ($key==0) $returnStr.='<a href="?action=modify&amp;job_id='.$row['job_id'].'">';
			if (strlen(trim($row[$field]))==0) $returnStr.='- -';
			elseif ($field=='degree') $returnStr.=stripslashes($degreesA[$row[$field]]);
			else $returnStr.=stripslashes($row[$field]);
			if ($key==0) $returnStr.='</a>';
			$returnStr.='</td>';

			$key++;
		}
		if ($row['status']=='Active') {
			$returnStr.='
			<td class="center"><a href="<?php echo APPURL ?>jobmatch.php?job_id='.$job_id.'" class="button">Match Employees</a></td>';
		}

			$returnStr.='
		</tr>';
	}
	$returnStr.='
		</tbody>
	</table>';
	$returnStr.=pagination ($postA);
	$returnStr.=actionform();
	$returnStr.='
</form>';

	return $returnStr;
}
