<form name="account" action="" method="post">
	<input name="action" value="pw2" type="hidden" />
	<input name="t" value="<?=$tab;?>" type="hidden" />

	<div class="form_row">
		<div>Current password:</div>
		<div><input name="password" type="password" value=""<?php if (in_array('password',$errorsA)) echo ' class="error"';?> /></div>
	</div>

	<div class="form_row">
		<div>New password:</div>
		<div><input name="password1" type="password" value=""<?php if (in_array('password1',$errorsA)) echo ' class="error"';?> /></div>
	</div>

	<div class="form_row">
		<div>Confirm new password:</div>
		<div><input name="password2" type="password" value=""<?php if (in_array('password2',$errorsA)) echo ' class="error"';?> /></div>
	</div>

	<div class="form_row">
		<div>&nbsp;</div>
		<div><input name="submt" type="submit" value="Update" /></div>
	</div>
</form>

