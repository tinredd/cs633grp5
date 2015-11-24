<?php
function form($job_id=0,$errorStr='',$action='add') {
	global $degreesA;

	$returnStr='';
	$errorsA=listErrors($errorStr);
	if (count($errorsA)>0) $returnStr.='<div class="error">Please correct the errors in the highlighted fields</div>';

	$row=getJob($job_id);
	$returnStr.='
	<form name="account" action="" method="post">
	<input name="action" value="'.$action.';2" type="hidden" />
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
		<div>
			<select name="degree"';
	if (in_array('degree',$errorsA)) $returnStr.=' class="error"';
	$returnStr.='>';
	foreach ($degreesA as $value) {
		$returnStr.='<option value="'.$value.'"';
		if ($value==$row['degree']) $returnStr.=' selected';
		$returnStr.='>'.$value.'</option>';
	}

	$returnStr.='
			</select>
		</div>
	</div>

	<div class="form_row">
		<div>Salary:</div>
		<div><input name="salary" type="text" value="'.stripslashes($row['salary']).'"';
	if (in_array('salary',$errorsA)) $returnStr.=' class="error"';
	$returnStr.=' /></div>
	</div>

	<div class="form_row">
		<div><span class="required">*</span> Office location:</div>
		<div>
			<select name="office_id"';
	if (in_array('office_id',$errorsA)) $returnStr.=' class="error"';
	$returnStr.='>';

	foreach (getOffices() as $office) {
		$returnStr.='<option value="'.$office['office_id'].'"';
		if ($office['office_id']==$row['office_id']) $returnStr.=' selected';
		$returnStr.='>'.stripslashes($office['office_name']).'</option>';
	}
	$returnStr.='
			</select>
		</div>
	</div>

	<div class="form_row">
		<div><span class="required">*</span> Years experience:</div>
		<div>
			<select name="years_experience"';
	if (in_array('years_experience',$errorsA)) $returnStr.=' class="error"';
	$returnStr.='>';

	for ($i=0; $i<=20; $i+=0.5) {
		$returnStr.='<option value="'.$i.'"';
		if ($i==$row['years_experience']) $returnStr.=' selected';
		$returnStr.='>'.$i.'</option>';
	}
	$returnStr.='
			</select>
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
			<div>
				<div id="skillcheckblock">
					<div style="width:100%;" id="allskills">
						<div style="width:100%;">';

	foreach (getSkills() as $i=>$skill) {
		if ($i%3==2) $returnStr.='</div><div style="width:100%;">';

		$returnStr.='
		<div style="display:inline-block; width:33%;">
			<label>
				<input name="skill_id[]" type="checkbox" value="'.$skill['skill_id'].'"';
			if (in_array('skill_id',$errorsA)) $returnStr.=' class="error"';
			if (in_array($skill['skill_id'],explode(',',$row['skillids']))) $returnStr.=' checked';
			$returnStr.=' />&nbsp;'.stripslashes($skill['skill_name']).'
			</label>
		</div>';
	}

	$returnStr.='
						</div>
					</div>
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
			<a href="?action=add" class="button">+&nbsp;Add Employee</a>
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
			<div>Title:</div>
			<div><input name="job_title" type="text" /></div>
		</div>

		<div>
			<div>Office:</div>
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
			<div>Skill:</div>
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
			<div>Status:</div>
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
			<input name="submt" type="submit" value="Search" />
		</div>
	</div>
</form>';

	return $returnStr;
}


function tabularize($postA,$dir,$columns=array()) {
	list($startDate,$endDate)=getDates($postA);
	$returnStr='';
	$returnStr.=filterform($postA,$dir);

	$returnStr.='

<form name="generic" action="" method="POST">
	<table>
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
	</tr>
	<tbody>';
	if (count(getListing($postA))==0) {
			$returnStr.='
		<tr>
			<td class="inactive italic" colspan=11>(none)</td>
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
			else $returnStr.=stripslashes($row[$field]);
			if ($key==0) $returnStr.='</a>';
			$returnStr.='</td>';

			$key++;
		}

			$returnStr.='
		</tr>';
	}
	$returnStr.='
		</tbody>
	</table>';
	$returnStr.=actionform();
	$returnStr.='
</form>';

	return $returnStr;
}
