		<div id="edit_form">
			<form name="deleteProject" method="post">
				<table>
				   	<tr>
				      <td colspan="3"><h3><?php print _("Project administration / delete project"); ?></h3></td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td width="150"><?php print _("Suvbersion project").": "; ?></td>
				   		<td>
				   			<?php print $tProject; ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td><?php print _("Subversion module path").": "; ?></td>
				   		<td>
				   			<?php print $tModulepath; ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td nowrap><?php print _("Repository").": "; ?></td>
				   		<td>
				   			<?php print $tRepo; ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr valign="top">
				   		<td nowrap><?php print _("Responsible").": "; ?></td>
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