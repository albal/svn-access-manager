		<div id="edit_form">
			<form name="deleteGroupAccess Right" method="post">
				<table>
				   	<tr>
				      <td colspan="3"><h3><?php print _("Group administration / delete group access right"); ?></h3></td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr valign="top">
				   		<td width="150"><?php print _("Group").": "; ?></td>
				   		<td>
				   			<?php print $tGroup; ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr valign="top">
				   		<td><?php print _("Description").": "; ?></td>
				   		<td>
				   			<?php print $tDescription; ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr valign="top">
				   		<td><?php print _("Rights allowed").": "; ?></td>
				   		<td>
				   			<?php print $tRights; ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="3" class="hlp_center">
				      	<input type="image" name="fSubmit_ok" src="./images/ok.png" value="<?php print _("Delete"); ?>"  title="<?php print _("Delete"); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				      	<input type="image" name="fSubmit_back" src="./images/button_cancel.png" value="<?php print _("Back"); ?>" title="<?php print _("Back"); ?>" />
				      </td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="3" class="standout">
				      	<?php print $tMessage; ?>
				      </td>
				   	</tr>
				</table>
			</form>
		</div>