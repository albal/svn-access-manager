		<div id="editform">
			<form name="deleteAccessRight" method="post">
				<table>
				   	<tr>
				      <td colspan="3"><h3><?php print _("Access right administration / delete access rights"); ?></h3></td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td nowrap><strong><?php print _("Project").": "; ?></strong></td>
				   		<td>
				   			<?php print $tProjectName; ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td nowrap><strong><?php print _("Subversion module path").": "; ?></strong></td>
				   		<td>
				   			<?php print $tModulePath; ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td nowrap><strong><?php print _("Selected directory").": "; ?></strong></td>
				   		<td colspan="2"><?php print $tPathSelected; ?>
				   		
				   	</tr>
				   	<tr>
				   		<td><strong><?php print _("Access right").": "; ?></strong></td>
				   		<td>
				   			<?php print $tAccessRight; ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td><strong><?php print _("Valid from").": "; ?></strong></td>
				   		<td>
				   			<?php print $tValidFrom; ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td><strong><?php print _("Valid until").": "; ?></strong></td>
				   		<td>
				   			<?php print $tValidUntil; ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr valign="top">
				   		<td><strong><?php print _("Allowed users").": "; ?></strong></td>
				   		<td>
							<?php print $tUsers; ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr valign="top">
				   		<td><strong><?php print _("Allowed groups").": "; ?></strong></td>
				   		<td>
							<?php print $tGroups; ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="3" class="hlpcenter">
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