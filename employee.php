<?php
$includes=false;
include($_SERVER['DOCUMENT_ROOT'].'/includes/includes.php');
if (in_array($_POST['action'],array('add2','modify2'))) {
	$errorsA=array();

//	Get errors (no error checking necessary)...
	if (strlen(trim($_POST['employee_id']))==0) $errorsA[]='employee_id';
	if (!is_numeric($_POST['employee_id']) && strlen(trim($_POST['employee_id']))!=7) $errorsA[]='employee_id';
	if (strlen(trim($_POST['first_name']))==0) $errorsA[]='first_name';
	if (strlen(trim($_POST['last_name']))==0) $errorsA[]='last_name';
	if (!($_POST['office_id']>0)) $errorsA[]='office_id';
	if (!strtotime($_POST['hire_date'])) $errorsA[]='hire_date';
	if (strlen(trim($_POST['email_address']))==0) $errorsA[]='email_address';
	if (!validEmail($_POST['email_address'])) $errorsA[]='email_address';

	if (count($errorsA)>0) {
		$eStr='action='.substr($_POST['action'],0,-1);
		if ($_POST['action']=='modify2') $eStr.='&employee_id='.$_POST['employee_id'];
		$eStr.='&e='.urlencode(base64_encode(serialize($errorsA)));
	}
	else $eStr='msg=updated';

	if (count($errorsA)==0) {
	//	Compile fields to insert into DB...
		$fieldsA=array();
		$fieldsA['employee_id']="'".addslashes(strip_tags($_POST['employee_id']))."'";
		$fieldsA['first_name']="'".addslashes(strip_tags($_POST['first_name']))."'";
		$fieldsA['last_name']="'".addslashes(strip_tags($_POST['last_name']))."'";
		$fieldsA['office_id']=$_POST['office_id'];
		$fieldsA['hire_date']="'".date('Y-m-d',strtotime($_POST['hire_date']))."'";
		$fieldsA['email_address']="'".addslashes(strip_tags($_POST['email_address']))."'";
		$fieldsA['office_phone']="'".addslashes(strip_tags($_POST['office_phone']))."'";
		$fieldsA['job_title']="'".addslashes(strip_tags($_POST['job_title']))."'";
		$fieldsA['status']=$_POST['status'];
		if ($_POST['action']=='add2') $fieldsA['password']="AES_ENCRYPT('cs633grp5','".AES_KEY."')";
		$fieldsA['last_updated']="'".date('Y-m-d H:i:s')."'";

	//	Update DB record...
		$updatesA=array();
		foreach ($fieldsA as $key=>$value) $updatesA[]="$key=$value";

		if ($_POST['action']=='add2') $sql="INSERT INTO user SET ".implode(',',$updatesA);
		elseif ($_POST['action']=='modify2') $sql="UPDATE user SET ".implode(',',$updatesA)." WHERE employee_id={$_POST['employee_id']} LIMIT 1";
		$result=$mysqli->query($sql);
	}

//	Redirect...
	header('Location: /employee.php?'.$eStr); exit();

} elseif (in_array($_POST['action'],array('activate2','inactivate2'))) {
	if (!is_array($_POST['employee_id'])) $employeeA=array(0);
	else $employeeA=$_POST['employee_id'];

	$status=($_POST['action']=='inactivate2')?0:1;

	$sql="UPDATE user SET status=$status WHERE employee_id IN (".implode(',',$employeeA).")";
	$result=$mysqli->query($sql);

	header('Location: /employee.php'); exit();
}

if ($_REQUEST['action']=='add') $title="Add an Employee";
elseif ($_REQUEST['action']=='modify') $title="Update Employee";
else $title="List Employees";

if (in_array($_REQUEST['action'],array('add','modify'))) $bcA['/employee.php']='List Employees';

include($_SERVER['DOCUMENT_ROOT'].'/includes/header.php');

if (in_array($_REQUEST['action'],array('add','modify'))) {
	$employee_id=($_REQUEST['employee_id']>0)?$_REQUEST['employee_id']:0;

	$sql="SELECT U.*,GROUP_CONCAT(S.skill_name) AS skillset 
	FROM user U 
	LEFT JOIN employee_skill E ON E.employee_id=U.employee_id 
	LEFT JOIN skill S ON S.skill_id=E.skill_id 
	WHERE U.employee_id=$employee_id
	GROUP BY E.employee_id
	ORDER BY S.skill_name";

	$row=$mysqli->fetch_row($sql);

	$sql="SELECT * FROM office ORDER BY office_name";
	$officesA=$mysqli->fetch_rows($sql);

	$errorsA=unserialize(base64_decode(urlencode($_REQUEST['e'])));
	if (!is_array($errorsA)) $errorsA=array();

	if (count($errorsA)>0) echo '<div class="error">Please correct the errors in the highlighted fields</div>';
?>
<form name="account" action="" method="post">
	<input name="action" value="<?=$_REQUEST['action'];?>2" type="hidden" />
	<input name="employee_id" value="<?=$employee_id;?>" type="hidden" />

	<div class="form_row">
		<div><span class="required">*</span> Employee ID:</div>
		<div><input name="employee_id" type="text" value="<?=stripslashes($row['employee_id']);?>"<?php if (in_array('employee_id',$errorsA)) echo ' class="error"';?> maxlength="7" /></div>
	</div>

	<div class="form_row">
		<div><span class="required">*</span> First name:</div>
		<div><input name="first_name" type="text" value="<?=stripslashes($row['first_name']);?>"<?php if (in_array('first_name',$errorsA)) echo ' class="error"';?> /></div>
	</div>

	<div class="form_row">
		<div><span class="required">*</span> Last name:</div>
		<div><input name="last_name" type="text" value="<?=stripslashes($row['last_name']);?>"<?php if (in_array('last_name',$errorsA)) echo ' class="error"';?> /></div>
	</div>

	<div class="form_row">
		<div><span class="required">*</span> Office location:</div>
		<div>
			<select name="office_id"<?php if (in_array('office_id',$errorsA)) echo ' class="error"';?>>
			<?php
			foreach ($officesA as $office) {
				echo '<option value="'.$office['office_id'].'"';
				if ($office['office_id']==$row['office_id']) echo ' selected';
				echo '>'.stripslashes($office['office_name']).'</option>';
			}
			?>
			</select>
		</div>
	</div>

	<div class="form_row">
		<div><span class="required">*</span> Internal email address:</div>
		<div><input name="email_address" type="text" value="<?=stripslashes($row['email_address']);?>"<?php if (in_array('email_address',$errorsA)) echo ' class="error"';?> /></div>
	</div>

	<div class="form_row">
		<div>Office phone:</div>
		<div><input name="office_phone" type="text" value="<?=stripslashes($row['office_phone']);?>"<?php if (in_array('office_phone',$errorsA)) echo ' class="error"';?> /></div>
	</div>

	<div class="form_row">
		<div>Job title:</div>
		<div><input name="job_title" type="text" value="<?=stripslashes($row['job_title']);?>"<?php if (in_array('job_title',$errorsA)) echo ' class="error"';?> /></div>
	</div>

	<div class="form_row">
		<div><span class="required">*</span> Hire date:</div>
		<div><input name="hire_date" type="date" value="<?php
		if (strtotime($row['hire_date'])) echo date('n/d/Y', strtotime($row['hire_date']));
		else echo '';
		?>"<?php if (in_array('hire_date',$errorsA)) echo ' class="error"';?> /></div>
	</div>

	<div class="form_row">
		<div>Skill(s):</div>
		<div>
			<div><?php
			if (strlen(trim($row['skillset']))>0) echo stripslashes($row['skillset']);
			else echo '<span class="italic inactive">(no skills entered)</span>';
			?></div>
		</div>
	</div>

	<?php if ($_REQUEST['action']=='modify') { ?>
	<div class="form_row">
		<div>Notes:</div>
		<div>
			<div><?php
			if (strlen(trim($row['notes']))>0) echo stripslashes(nl2br($row['notes']));
			else echo '<span class="italic inactive">(no notes entered)</span>';
			?></div>
		</div>
	</div>
	<? } ?>

	<div class="form_row">
		<div>Status:</div>
		<div>
			<select name="status"<?php if (in_array('status',$errorsA)) echo ' class="error"';?>>
				<option value="1">Active</option>
				<option value="0"<?php if (strlen(trim($row['status']))>0 && $row['status']=='0') echo ' selected';?>>Inactive</option>
			</select></div>
	</div>

	<div class="form_row">
		<div>&nbsp;</div>
		<div><input name="submt" type="submit" value="Update" /></div>
	</div>
</form>

<?php
} elseif (!isset($_REQUEST['action'])) {
	if (!isset($_POST['start_date'])) {
		$startDate=mktime(0,0,0,date('n'),date('j')+1,date('Y')-10);
		$endDate=mktime(0,0,0,date('n'),date('j'),date('Y'));
	} else {
		$startDate=strtotime($_POST['start_date']);
		$endDate=strtotime($_POST['end_date']);
	}

//	Is this person a location contact?
	$sql="SELECT COUNT(*) FROM office WHERE contact_email='{$_SESSION['email_address']}'";
	$contact=$mysqli->fetch_value($sql);	

	if (strlen(trim($_POST['dir']))>0) $dir=$_POST['dir'];
	else $dir='ASC';

	$andA=array();

	$sql="SELECT U.*, GROUP_CONCAT(skill_name) AS skillset,O.office_name FROM user U 
	LEFT JOIN employee_skill E ON E.employee_id=U.employee_id
	LEFT JOIN skill S ON S.skill_id=E.skill_id
	LEFT JOIN office O ON O.office_id=U.office_id";

	$andA[]="user_type=2";
	$andA[]="((hire_date>='".date('Y-m-d',$startDate)."' AND hire_date<='".date('Y-m-d',$endDate)."') OR hire_date IS NULL)";
	if ($_POST['office_id']>0) $andA[]="U.office_id=".$_POST['office_id'];
	if ($_POST['skill_id']>0) $andA[]="S.skill_id= ".$_POST['skill_id'];
	if (strlen(trim($_POST['job_title']))>0) $andA[]="job_title LIKE '".$_POST['job_title']."%'";

	$whereA[]=implode(' AND ',$andA);
	if ($contact==1) $whereA[]="U.employee_id={$_SESSION['employee_id']}";

	if (count($andA)>0) $sql.=" WHERE (".implode(' OR ',$whereA).")";
	$sql.=" GROUP BY U.employee_id";
	if (strlen(trim($_POST['sort']))>0) {
		$sql.=" ORDER BY {$_POST['sort']} $dir";
	}
	$rs_row=$mysqli->query($sql);

	$sql="SELECT * FROM office ORDER BY office_name";
	$officesA=$mysqli->fetch_rows($sql);

	$sql="SELECT * FROM skill WHERE skill_status=1 ORDER BY skill_name";
	$skillsA=$mysqli->fetch_rows($sql);
?>
<form name="filter" action="" method="POST">
	<input name="sort" type="hidden" value="<?=$_POST['sort'];?>" />
	<input name="dir" type="hidden" value="<?=$dir;?>" />

	<div class="filterbox">
		<div>
			<div>Title:</div>
			<div><input name="job_title" type="text" /></div>
		</div>

		<div>
			<div>Hired date:</div>
			<div>
				<input name="start_date" type="date" style="width:6em;" value="<?=date('n/j/Y',$startDate);?>" /> to 
				<input name="end_date" type="date" style="width:6em;" value="<?=date('n/j/Y',$endDate);?>" />
			</div>
		</div>

		<div>
			<div>Office:</div>
			<div>
				<select name="office_id">
					<option value="0">(all offices)</option>
					<?php
					foreach ($officesA as $row) {
						echo '<option value="'.$row['office_id'].'"';
						if ($row['office_id']==$_POST['office_id']) echo ' selected';
						echo '>'.stripslashes($row['office_name']).'</option>';
					}
					?>
				</select>
			</div>
		</div>

		<div>
			<div>Skill:</div>
			<div>
				<select name="skill_id">
					<option value="0">(all skills)</option>
					<?php
					foreach ($skillsA as $row) {
						echo '<option value="'.$row['skill_id'].'"';
						if ($row['skill_id']==$_POST['skill_id']) echo ' selected';
						echo '>'.stripslashes($row['skill_name']).'</option>';
					}
					?>
				</select>
			</div>
		</div>

		<div>
			<input name="submt" type="submit" value="Search" />
		</div>
	</div>
</form>

<form name="generic" action="" method="POST">
	<table>
		<tr>
			<th><a href="javascript:checkAll('employee_id[]')">Select</a></th>
			<th><a href="javascript:void(0)" class="sort" id="sort_last_name">Last name:</a></th>
			<th><a href="javascript:void(0)" class="sort" id="sort_first_name">First name:</a></th>
			<th><a href="javascript:void(0)" class="sort" id="sort_employee_id">Employee ID:</a></th>
			<th><a href="javascript:void(0)" class="sort" id="sort_office_name">Office:</a></th>
			<th><a href="javascript:void(0)" class="sort" id="sort_hire_date">Hire date:</a></th>
			<th><a href="javascript:void(0)" class="sort" id="sort_email_address">Email address:</a></th>
			<th><a href="javascript:void(0)" class="sort" id="sort_office_phone">Office phone:</a></th>
			<th><a href="javascript:void(0)" class="sort" id="sort_job_title">Job title:</a></th>
			<th><a href="javascript:void(0)" class="sort" id="sort_skillset">Skill(s):</a></th>
			<th><a href="javascript:void(0)" class="sort" id="sort_status">Status:</a></th>
		</tr>
		<tbody>
	<?php
		if ($rs_row->num_rows==0) {
			echo '<tr>
				<td class="inactive italic" colspan=11>(none)</td>
				</tr>';
		}

		while ($row=$rs_row->fetch_assoc()) {
	?>
			<tr<?php if ($row['status']==0) echo ' class="inactive"';?>>
				<td class="center"><input name="employee_id[]" type="checkbox" value="<?=$row['employee_id'];?>" /></td>
				<td>
					<a href="?action=modify&amp;employee_id=<?=$row['employee_id'];?>">
						<?=stripslashes($row['last_name']);?>
					</a>
				</td>
				<td><?=stripslashes($row['first_name']);?></td>
				<td><?=stripslashes($row['employee_id']);?></td>
				<td><?=stripslashes($row['office_name']);?></td>
				<td><?php
				if (!strtotime($row['hire_date'])) echo '<span class="inactive italic">(none)</span>';
				else echo date('n/j/Y',strtotime($row['hire_date']));
				?></td>
				<td><?=stripslashes($row['email_address']);?></td>
				<td><?php
				if(strlen(trim($row['office_phone']))==0) echo '- -';
				else echo $row['office_phone'];
				?></td>
				<td><?php
				if (strlen(trim($row['job_title']))>0) echo stripslashes($row['job_title']);
				else echo '- -';
				?></td>
				<td><?php 
				if (strlen(trim($row['skillset']))==0) echo '<span class="inactive italic">(none)</span>';
				else echo stripslashes($row['skillset']);
				?></td>
				<td><?php if ($row['status']==1) echo 'Active'; else echo 'Inactive';?></td>
			</tr>
	<?php
		}
	?>
		</tbody>
	</table>
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
	</div>
</form>
<?php
}


include($_SERVER['DOCUMENT_ROOT'].'/includes/footer.php');
?>