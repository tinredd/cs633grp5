<?php
function form($employee_id=0,$errorStr='',$action='add') {
	$returnStr='';
	$errorsA=listErrors($errorStr);
	if (count($errorsA)>0) $returnStr.='<div class="error">Please correct the errors in the highlighted fields</div>';

	$row=getEmployee($employee_id);
	$returnStr.='
	
<form name="account" action="" method="post">
	<input name="action" value="'.$action.'2" type="hidden" />
	<input name="employee_id" value="'.$employee_id.'" type="hidden" />

	<div class="form_row">
		<div><span class="required">*</span> Employee ID:</div>
		<div><input name="employee_id" type="text" value="';
	$returnStr.=stripslashes($row['employee_id']).'"';
	if (in_array('employee_id',$errorsA)) $returnStr.=' class="error"';
	$returnStr.='maxlength="7" /></div>
	</div>

	<div class="form_row">
		<div><span class="required">*</span> First name:</div>
		<div><input name="first_name" type="text" value="';
	$returnStr.=stripslashes($row['first_name']).'"';
	if (in_array('first_name',$errorsA)) $returnStr.=' class="error"';
	$returnStr.=' /></div>
	</div>

	<div class="form_row">
		<div><span class="required">*</span> Last name:</div>
		<div><input name="last_name" type="text" value="';
	$returnStr.=stripslashes($row['last_name']).'"';
	if (in_array('last_name',$errorsA)) $returnStr.=' class="error"';
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
		<div><span class="required">*</span> Internal email address:</div>
		<div><input name="email_address" type="text" value="';
	$returnStr.=stripslashes($row['email_address']).'"';
	if (in_array('email_address',$errorsA)) $returnStr.=' class="error"';
	$returnStr.=' /></div>
	</div>

	<div class="form_row">
		<div>Office phone:</div>
		<div><input name="office_phone" type="text" value="';
	$returnStr.=stripslashes($row['office_phone']).'"';
	if (in_array('office_phone',$errorsA)) $returnStr.=' class="error"';
	$returnStr.=' /></div>
	</div>

	<div class="form_row">
		<div>Job title:</div>
		<div><input name="job_title" type="text" value="';
	$returnStr.=stripslashes($row['job_title']).'"';
	if (in_array('job_title',$errorsA)) $returnStr.=' class="error"';
	$returnStr.=' /></div>
	</div>

	<div class="form_row">
		<div><span class="required">*</span> Hire date:</div>
		<div><input name="hire_date" type="date" value="';
	if (strtotime($row['hire_date'])) $returnStr.=date('n/d/Y',strtotime($row['hire_date']));
	$returnStr.='"';
	if (in_array('hire_date',$errorsA)) $returnStr.=' class="error"';
	$returnStr.=' /></div>
	</div>

	<div class="form_row">
		<div>Skill(s):</div>
		<div>
			<div>';
	if (strlen(trim($row['skillset']))>0) $returnStr.=stripslashes($row['skillset']);
	else $returnStr.='<span class="italic inactive">(no skills entered)</span>';
	$returnStr.='</div>
		</div>
	</div>';

	if ($action=='modify') {
		$returnStr.='
	<div class="form_row">
		<div>Notes:</div>
		<div>
			<div>';
			if (strlen(trim($row['notes']))>0) $returnStr.=stripslashes(nl2br($row['notes']));
			else $returnStr.='<span class="italic inactive">(no notes entered)</span>';
		$returnStr.='
			</div>
		</div>
	</div>';
	}
	$returnStr.='
	<div class="form_row">
		<div>Status:</div>
		<div>
			<select name="status"';
	if (in_array('status',$errorsA)) $returnStr.=' class="error"';
	$returnStr.='>
				<option value="1">Active</option>
				<option value="0"';
	if (strlen(trim($row['status']))>0 && $row['status']=='0')$returnStr.= ' selected';
	$returnStr.='>Inactive</option>
			</select></div>
	</div>

	<div class="form_row">
		<div>&nbsp;</div>
		<div><input name="submt" type="submit" value="Update" /></div>
	</div>';

	$returnStr.='
</form>';

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
			<div>Hired date:</div>
			<div>
				<input name="start_date" type="date" style="width:6em;" value="'.date('n/j/Y',$startDate).'" /> to 
				<input name="end_date" type="date" style="width:6em;" value="'.date('n/j/Y',$endDate).'" />
			</div>
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
			<th><a href="javascript:checkAll(\'employee_id[]\')">Select</a></th>';

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

	foreach (getListing($postA) as $employee_id=>$row) {
		$returnStr.='<tr';
		if ($row['status']=='Inactive') $returnStr.=' class="inactive"';
		$returnStr.='>';
		$returnStr.='
			<td class="center"><input name="employee_id[]" type="checkbox" value="'.$row['employee_id'].'" /></td>';

		$key=0;
		foreach ($columns as $field=>$label) {
			$returnStr.='
			<td>';
			if ($key==0) $returnStr.='<a href="?action=modify&amp;employee_id='.$row['employee_id'].'">';
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
