		<div id="edit_form">
			<form name="deleteGroup" method="post">
				<table>
				   	<tr>
				      <td colspan="3"><h3><?php print _("Group administration / delete group"); ?></h3></td>
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
				   		<td><?php print _("Group members").": "; ?></td>
				   		<td>
				   			<?php print $tMembers; ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="3" class="hlp_center">
				      	<input class="button" type="submit" name="fSubmit" value="<?php print _("Delete"); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				      	<input class="button" type="submit" name="fSubmit" value="<?php print _("Back"); ?>" />
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