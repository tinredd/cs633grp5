<?php
function form($employee_id=0,$errorStr='',$action='add') {
	$returnStr='';
	$errorsA=listErrors($errorStr);
	if (count($errorsA)>0) $returnStr.='<div class="error">Please correct the errors in the highlighted fields!</div>';

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
		<div>';
	$returnStr.='<input name="office_id" type="hidden" value="'.$row['office_id'].'" />';
	foreach (getOffices() as $office) {

		$returnStr.='<div class="inline specialselect';
		if ($office['office_id']==$row['office_id'])  $returnStr.='-selected';
		$returnStr.='" id="office_id_'.$office['office_id'].'">'.stripslashes($office['office_name']).'</div>';
	}
	$returnStr.=
	'
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
		<div><input name="hire_date" class="datepicker" type="text" value="';
	if (strtotime($row['hire_date'])) $returnStr.=date('n/d/Y',strtotime($row['hire_date']));
	$returnStr.='"';
	if (in_array('hire_date',$errorsA)) $returnStr.=' class="error"';
	$returnStr.=' /></div>
	</div>';

	if ($action=='modify') {
		$returnStr.='
	<div class="form_row">
		<div>Skill(s):</div>
		<div>
			<div>';
		if (strlen(trim($row['skillset']))>0) $returnStr.=stripslashes($row['skillset']);
		else $returnStr.='<span class="italic inactive">(no skills entered)</span>';
	$returnStr.='</div>
		</div>
	</div>';

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

	if (!isset($postA['sort'])) $postA['sort']='';
	if (!isset($postA['office_id'])) $postA['office_id']=0;
	if (!isset($postA['skill_id'])) $postA['skill_id']=0;
	if (!isset($postA['ppp'])) $postA['ppp']=0;

	$returnStr='';
	$returnStr.='<form name="filter" action="" method="POST">
	<input name="sort" type="hidden" value="'.$postA['sort'].'" />
	<input name="dir" type="hidden" value="'.$dir.'" />

	<div class="filterbox">
		<div>
			<div class="filterlabel">Title</div>
			<div><input name="job_title" type="text" /></div>
		</div>

		<div>
			<div class="filterlabel">Hired date</div>
			<div>
				<input name="start_date" class="datepicker" type="text" style="width:6em;" value="'.date('n/j/Y',$startDate).'" /><span class="filterlabel"> to </span> 
				<input name="end_date" class="datepicker" type="text" style="width:6em;" value="'.date('n/j/Y',$endDate).'" />
			</div>
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
	list($startDate,$endDate)=getDates($postA);
	$returnStr='';
	$returnStr.=filterform($postA,$dir);

	if (!isset($postA['sort'])) $postA['sort']='';
	if (!isset($postA['office_id'])) $postA['office_id']=0;
	if (!isset($postA['skill_id'])) $postA['skill_id']=0;
	if (!isset($postA['ppp'])) $postA['ppp']=0;

	$pg=(isset($postA['pg']) && $postA['pg']>1) ? $postA['pg'] : 1;
	$ppp=(isset($postA['ppp']) && $postA['ppp']>0) ? $postA['ppp'] : 0;
	$start=($pg-1)*$ppp + 1;
	$end=$start+($ppp-1);

	if ($end>count(getListing($postA))) $end=count(getListing($postA));
	if ($end==0) $end=count(getListing($postA));

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
			<th class="label" colspan="'.(count($columns)+1).'">Employees '.$start;
			if ($start!=$end) $returnStr.=' - '.$end;
			$returnStr.=' of '.count(getListing($postA)).'</th>
		</tr>
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
			<td class="inactive italic" colspan='.(count($columns)+1).'>(none)</td>
		</tr>';
	}

	foreach (getListing($postA,0) as $employee_id=>$row) {
		$returnStr.='<tr';
		if ($row['status']=='Inactive') $returnStr.=' class="inactive"';
		$returnStr.='>';
		$returnStr.='
			<td class="center"><input name="employee_id[]" type="checkbox" value="'.$row['employee_id'].'" /></td>';

		$key=0;
		foreach ($columns as $field=>$label) {
			$returnStr.='
			<td>';
			if ($key==0) $returnStr.='<a href="employee.php?action=modify&amp;employee_id='.$row['employee_id'].'">';
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
	$returnStr.=pagination($postA,$postA['ppp']);
	$returnStr.=actionform();
	$returnStr.='
</form>';

	return $returnStr;
}
