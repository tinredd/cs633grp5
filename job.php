<?php
$includes=false;
include($_SERVER['DOCUMENT_ROOT'].'/includes/includes.php');
if (in_array($_POST['action'],array('add2','modify2'))) {
	$errorsA=array();

//	Get errors (no error checking necessary)...
	if (strlen(trim($_POST['job_title']))==0) $errorsA[]='job_title';
	if (!($_POST['office_id']>0)) $errorsA[]='office_id';
	if (strlen(trim($_POST['salary']))==0 && !is_numeric($_POST['salary'])) $errorsA[]='salary';
	if (count($_POST['skill_id'])==0) $errorsA[]='skill_id';

	if (count($errorsA)>0) {
		$eStr='action='.substr($_POST['action'],0,-1);
		if ($_POST['action']=='modify2') $eStr.='&job_id='.$_POST['job_id'];
		$eStr.='&e='.urlencode(base64_encode(serialize($errorsA)));
	}
	else $eStr='msg=updated';

	if (count($errorsA)==0) {
	//	Compile fields to insert into DB...
		$fieldsA=array();
		$fieldsA['job_title']="'".addslashes(strip_tags($_POST['job_title']))."'";
		$fieldsA['office_id']=$_POST['office_id'];
		$fieldsA['status']=$_POST['status'];
		$fieldsA['years_experience']=$_POST['years_experience'];
		$fieldsA['salary']="'".addslashes(strip_tags($_POST['salary']))."'";
		$fieldsA['degree']="'".addslashes(strip_tags($_POST['degree']))."'";
		$fieldsA['notes']="'".addslashes(strip_tags($_POST['notes']))."'";

	//	Update DB record...
		$updatesA=array();
		foreach ($fieldsA as $key=>$value) $updatesA[]="$key='".$value."'";

		if ($_POST['action']=='add2') $job_id=$mysqli->insert('job',$fieldsA);
		elseif ($_POST['action']=='modify2') {
			$mysqli->update('job',$_POST['job_id'],$fieldsA);
			$job_id=$_POST['job_id'];
		}

		$skillset=explode(',',$mysqli->fetch_value("SELECT GROUP_CONCAT(skill_id) FROM job_skill WHERE job_id=$job_id"));

		$deleteA=array_diff($skillset,$_POST['skill_id']);
		$addA=array_diff($_POST['skill_id'],$skillset);

		if (count($deleteA)>0) {
			$sql="DELETE FROM job_skill WHERE job_id=$job_id AND skill_id IN (".implode(',',$deleteA).")";
			$result=$mysqli->query($sql);
		}

		foreach ($addA as $skill_id) {
			$sql="INSERT INTO job_skill SET job_id=$job_id, skill_id=$skill_id";
			$result=$mysqli->query($sql);
		}
	}

//	Redirect...
	header('Location: /job.php?'.$eStr); exit();

} elseif ($_POST['action']=='delete2') {

}

if ($_REQUEST['action']=='add') $title="Add a Job";
elseif ($_REQUEST['action']=='modify') $title="Update Job";
else $title="List Jobs";

if (in_array($_REQUEST['action'],array('add','modify'))) $bcA['/job.php']='List Jobs';

include($_SERVER['DOCUMENT_ROOT'].'/includes/header.php');

if (in_array($_REQUEST['action'],array('add','modify'))) {
	$job_id=($_REQUEST['job_id']>0)?$_REQUEST['job_id']:0;
	$row=$mysqli->fetch_row("SELECT * FROM job WHERE job_id=$job_id");
	$job_skillsA=explode(',',$mysqli->fetch_value("SELECT GROUP_CONCAT(skill_id) FROM job_skill WHERE job_id=$job_id"));

	$sql="SELECT * FROM office ORDER BY office_name";
	$officesA=$mysqli->fetch_rows($sql);

	$errorsA=unserialize(base64_decode(urlencode($_REQUEST['e'])));
	if (!is_array($errorsA)) $errorsA=array();

	if (count($errorsA)>0) echo '<div class="error">Please correct the errors in the highlighted fields</div>';

	$sql="SELECT * FROM skill WHERE skill_status=1 ORDER BY skill_name";
	$skillsA=$mysqli->fetch_rows($sql);
?>
<form name="account" action="" method="post">
	<input name="action" value="<?=$_REQUEST['action'];?>2" type="hidden" />
	<input name="job_id" value="<?=$job_id;?>" type="hidden" />

	<div class="form_row">
		<div><span class="required">*</span> Job title:</div>
		<div><input name="job_title" type="text" value="<?=stripslashes($row['job_title']);?>"<?php if (in_array('job_title',$errorsA)) echo ' class="error"';?> /></div>
	</div>

	<div class="form_row">
		<div><span class="required">*</span> Degree:</div>
		<div>
			<select name="degree"<?php if (in_array('degree',$errorsA)) echo ' class="error"';?>>
			<?php
			foreach ($degreesA as $value) {
				echo '<option value="'.$value.'"';
				if ($value==$row['degree']) echo ' selected';
				echo '>'.$value.'</option>';
			}
			?>
			</select>
		</div>
	</div>

	<div class="form_row">
		<div>Salary:</div>
		<div><input name="salary" type="text" value="<?=stripslashes($row['salary']);?>"<?php if (in_array('salary',$errorsA)) echo ' class="error"';?> /></div>
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
		<div><span class="required">*</span> Years experience:</div>
		<div>
			<select name="years_experience"<?php if (in_array('years_experience',$errorsA)) echo ' class="error"';?>>
			<?php
			for ($i=0; $i<=20; $i++) {
				echo '<option value="'.$i.'"';
				if ($i==$row['years_experience']) echo ' selected';
				echo '>'.$i.'</option>';
			}
			?>
			</select>
		</div>
	</div>

	<div class="form_row">
		<div>Notes:</div>
		<div>
			<div><textarea name="notes" style="width:75%;"><?=stripslashes($row['notes']);?></textarea></div>
		</div>
	</div>

	<div class="form_row">
		<div>Skills:</div>
		<div>
			<div>
				<div id="skillcheckblock">
					<div style="width:100%;" id="allskills">
						<div style="width:100%;">
			<?php
			foreach ($skillsA as $i=>$skill) {
				if ($i%3==2) echo '</div><div style="width:100%;">';

				echo '
				<div style="display:inline-block; width:33%;">
					<label>
						<input name="skill_id[]" type="checkbox" value="'.$skill['skill_id'].'"';
					if (in_array('skill_id',$errorsA)) echo ' class="error"';
					if (in_array($skill['skill_id'],$job_skillsA)) echo ' checked';
					echo ' />&nbsp;'.stripslashes($skill['skill_name']).'
					</label>
				</div>';
			}
			?>			
						</div>
					</div>
				</div>

				<div id="skillfields"></div>
				
				<div><a href="javascript:void(0)" class="button" id="newskill">Add new skill</a></div>
			</div>
		</div>
	</div>

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

	if (strlen(trim($_POST['dir']))>0) $dir=$_POST['dir'];
	else $dir='ASC';

	if (isset($_POST['status']) && $_POST['status']<1) $status=$_POST['status'];
	else $status=1;

	$andA=array();

	$sql="SELECT U.*, GROUP_CONCAT(skill_name) AS skillset,O.office_name FROM job U 
	LEFT JOIN job_skill E ON E.job_id=U.job_id
	LEFT JOIN skill S ON S.skill_id=E.skill_id
	LEFT JOIN office O ON O.office_id=U.office_id";

	if ($_POST['office_id']>0) $andA[]="U.office_id=".$_POST['office_id'];
	if ($_POST['skill_id']>0) $andA[]="S.skill_id= ".$_POST['skill_id'];
	if (strlen(trim($_POST['job_title']))>0) $andA[]="job_title LIKE '".$_POST['job_title']."%'";

	if (count($andA)>0) $sql.=" WHERE ".implode(' AND ',$andA);
	$sql.=" GROUP BY U.job_id";
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
			<div>Status:</div>
			<div>
				<select name="status">
					<option value="-1"<?php if ($status==-1) echo ' selected';?>>(all)</option>
					<option value="1"<?php if ($status==1) echo ' selected';?>Active</option>
					<option value="0"<?php if ($status==0) echo ' selected';?>Inactive</option>

				</select>
			</div>
		</div>

		<div>
			<input name="submt" type="submit" value="Search" />
		</div>
	</div>
</form>

<table>
	<tr>
		<th><a href="javascript:checkAll('job_id')">Select</a></th>
		<th><a href="javascript:void(0)" class="sort" id="sort_job_title">Job title:</a></th>
		<th><a href="javascript:void(0)" class="sort" id="sort_degree">Degree:</a></th>
		<th><a href="javascript:void(0)" class="sort" id="sort_salary">Annual Salary:</a></th>
		<th><a href="javascript:void(0)" class="sort" id="sort_office_name">Office:</a></th>
		<th><a href="javascript:void(0)" class="sort" id="sort_skillset">Skill(s):</a></th>
		<th><a href="javascript:void(0)" class="sort" id="sort_status">Status:</a></th>
		<th>Actions:</th>
	</tr>
	<tbody>
<?php
	if ($rs_row->num_rows==0) {
		echo '
			<tr>
				<td class="inactive italic" colspan=7>(none)</td>
			</tr>';
	}
	while ($row=$rs_row->fetch_assoc()) {
?>
		<tr>
			<td class="center"><input name="job_id[]" type="checkbox" value="<?=$row['job_id'];?>" /></td>
			<td>
				<a href="?action=modify&amp;job_id=<?=$row['job_id'];?>">
					<?=stripslashes($row['job_title']);?>
				</a>
			</td>
			<td><?=stripslashes($row['degree']);?></td>
			<td><?php 
			if ($row['salary']>0) echo '$'.number_format(stripslashes($row['salary']),2);
			else echo '- -';
			?></td>
			<td><?=stripslashes($row['office_name']);?></td>
			<td><?php 
			if (strlen(trim($row['skillset']))==0) echo '<span class="inactive italic">(none)</span>';
			else echo stripslashes($row['skillset']);
			?></td>
			<td><?php if ($row['status']==1) echo 'Active'; else echo 'Inactive';?></td>
			<td><a href="/match.php?job_id=<?=$row['job_id'];?>" class="button">Match</a></td>
		</tr>
<?php
	}
?>
	</tbody>
</table>
<div class="standard">
	<a href="?action=add" class="button">+&nbsp;Add Job</a>
</div>
<?php
}


include($_SERVER['DOCUMENT_ROOT'].'/includes/footer.php');
?>