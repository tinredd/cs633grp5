<?php
//	Contact HR
	$field=base64_decode(urldecode($_REQUEST['f']));
	if (!in_array($field,array_keys($ufieldsA))) $field='';

	if (count($errorsA)>0) echo '<div class="error">Please correct the errors in the highlighted fields</div>';
	$office=$mysqli->fetch_row("SELECT * FROM office WHERE office_id={$_SESSION['office_id']}");
?>

<form name="account" action="" method="post">
	<input name="action" value="hr2" type="hidden" />
	<input name="t" value="<?php echo $tab;?>" type="hidden" />
	<input name="f" value="<?php echo $_REQUEST['f'];?>" type="hidden" />
	<input name="employee_id" value="<?php echo $_SESSION['employee_id'];?>" type="hidden" />

	<div class="form_row">
		<div>Office contact:</div>
		<div>
			<div><?php echo stripslashes($office['contact_name']);?></div>
		</div>
	</div>

	<div class="form_row">
		<div>Contact email address:</div>
		<div>
			<div><?php echo stripslashes($office['contact_email']);?></div>
		</div>
	</div>

	<div class="form_row">
		<div>Contact phone:</div>
		<div>
			<div><?php echo stripslashes($office['contact_phone']);?></div>
		</div>
	</div>
	<?php
	if (strlen(trim($field))>0) {
	?>
	<div class="form_row">
		<div>Update Field:</div>
		<div>
			<select name="field" id="field" onchange="updateToHr($(this).val());"<?php if (in_array('field',$errorsA)) echo ' class="error"';?>>
			<?php
			foreach ($ufieldsA as $key=>$value) {
				echo '<option value="'.$key.'"';
				if ($key==$field) echo ' selected';
				echo '>'.$value.'</option>';
			}
			?>
			</select>
		</div>
	</div>

	<input name="subjecta" type="hidden" value="<?php if (strlen(trim($field))>0) echo 'Please update my '.$ufieldsA[$field];?>" />
	<?php
	}
	?>

	<div class="form_row">
		<div>Subject:</div>
		<div>
			<input name="subject" type="text"<?php if (strlen(trim($field))>0) echo ' disabled';?> value="<?php if (strlen(trim($field))>0) echo 'Please update my '.$ufieldsA[$field];?>"<?php if (in_array('subject',$errorsA)) echo ' class="error"';?> />
		</div>
	</div>

	<div class="form_row">
		<div>Message:</div>
		<div><textarea name="message"<?php if (in_array('message',$errorsA)) echo ' class="error"';?>><?php
if (strlen(trim($field))>0) echo 'Please update my '.$ufieldsA[$field].' to: [ENTER NEW VALUE HERE].

Your assistance is very much appreciated!';
		?></textarea></div>
	</div>

	<div class="form_row">
		<div>&nbsp;</div>
		<div><input name="submt" type="submit" value="Send" /></div>
	</div>
</form>
