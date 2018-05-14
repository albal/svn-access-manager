		<div id="editform">
		<form name="mailbox" method="post">
		<table>
		   <tr>
		      <td colspan="3"><h3><?php print _("Password change"); ?></h3></td>
		   </tr>
		   <tr>
		      <td colspan=3>&nbsp;</td>
		   </tr>
		   <tr>
			 <td colspan=3><?php print _( "Note that your new password becomes valid for the SVN Access Manager Webinterface immediately, but may take some time for repository access itself. The latter depends from if and how your system administrator has setup the update-interval for passwords."); ?></td>
		   </tr>
		    <tr>
		      <td colspan=3>&nbsp;</td>
		   </tr>
		   <tr>
		      <td width="150"><strong><?php print _("Current password").": "; ?></strong></td>
		      <td><input type="password" name="fPassword_current" autocomplete="off" /></td>
		      <td><?php print $pPassword_password_current_text; ?></td>
		   </tr>
		   <tr>
		      <td width="150"><strong><?php print _("New password").": "; ?></strong></td>
		      <td><input type="password" name="fPassword" autocomplete="off" /></td>
		      <td><?php print $pPassword_password_text; ?></td>
		   </tr>
		   <tr>
		      <td width="150"><strong><?php print _("Retype new password").": "; ?></strong></td>
		      <td><input type="password" name="fPassword2" autocomplete="off" /></td>
		      <td>&nbsp;</td>
		   </tr>
		   <tr>
		      <td colspan=3>&nbsp;</td>
		   </tr>
		   <tr>
		      <td colspan="3" class="hlpcenter">
		      	<input type="image" name="fSubmit_ok" src="./images/ok.png" value="<?php print _("Change password"); ?>"  title="<?php print _("Change password"); ?>" />
			  </td>
		   </tr>
		   <tr>
		      <td colspan="3" class="standout"><?php print $tMessage; ?></td>
		   </tr>
		</table>
		</form>
		</div>
