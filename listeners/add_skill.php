<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'].'/includes/includes.php');

$errorsA=array();
$count=$mysqli->fetch_value("SELECT COUNT(*) FROM skill WHERE skill_name LIKE '".addslashes($_POST['skill_name'])."'");

//	Check to see if skill is null or already added...
if (strlen(trim($_POST['skill_name']))==0) $errorsA[]='Enter a skill!';
elseif ($count>0) $errorsA[]='Already added!';

$checkedA=array();
if (count($_POST['checked_skill_id'])>0) $checkedA=$_POST['checked_skill_id'];

if (count($errorsA)==0) {
	$sql="INSERT INTO skill SET skill_name='".addslashes($_POST['skill_name'])."'";
	$result=$mysqli->query($sql);
}

$sql="SELECT * FROM skill WHERE skill_status=1 ORDER BY skill_name";
$skillsA=$mysqli->fetch_rows($sql);
?>

<div style="width:100%;">
	<?php
	foreach ($skillsA as $i=>$skill) {
		if ($i%3==2) echo '</div><div style="width:100%;">';

		echo '
		<div style="display:inline-block; width:33%;">
			<label>
				<input name="skill_id[]" type="checkbox" value="'.$skill['skill_id'].'"';
			if (in_array('skill_id',$errorsA)) echo ' class="error"';
			if ($skill['skill_name']==$_POST['skill_name'] || in_array($skill['skill_id'],$checkedA)) echo ' checked';
			echo ' />&nbsp;'.stripslashes($skill['skill_name']).'
			</label>
		</div>';
	}
	?>			
</div>
<?php
if (count($errorsA)>0) echo '<div class="plainerror">'.implode('<br/>',$errorsA).'</div>';
?>