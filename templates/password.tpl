		<div id="edit_form">
		<form name="mailbox" method="post">
		<table>
		   <tr>
		      <td colspan="3"><h3><?php print _("Password change"); ?></h3></td>
		   </tr>
		   <tr>
		      <td colspan=3>&nbsp;</td>
		   </tr>
		   <tr>
		      <td><strong><?php print _("Current password").": "; ?></strong></td>
		      <td><input type="password" name="fPassword_current" autocomplete="off" /></td>
		      <td><?php print $pPassword_password_current_text; ?></td>
		   </tr>
		   <tr>
		      <td><strong><?php print _("New password").": "; ?></strong></td>
		      <td><input type="password" name="fPassword" autocomplete="off" /></td>
		      <td><?php print $pPassword_password_text; ?></td>
		   </tr>
		   <tr>
		      <td><strong><?php print _("Retype new password").": "; ?></strong></td>
		      <td><input type="password" name="fPassword2" autocomplete="off" /></td>
		      <td>&nbsp;</td>
		   </tr>
		   <tr>
		      <td colspan=3>&nbsp;</td>
		   </tr>
		   <tr>
		      <td colspan="3" class="hlp_center">
		      	<input type="image" name="fSubmit_ok" src="./images/ok.png" value="<?php print _("Change password"); ?>"  title="<?php print _("Change password"); ?>" />
			  </td>
		   </tr>
		   <tr>
		      <td colspan="3" class="standout"><?php print $tMessage; ?></td>
		   </tr>
		</table>
		</form>
		</div>
