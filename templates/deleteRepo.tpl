		<div id="edit_form">
			<form name="deleteRepo" method="post">
				<table>
				   	<tr>
				      <td colspan="3"><h3><?php print _("Repository administration / delete repository"); ?></h3></td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td width="150"><?php print _("Repository name").": "; ?></td>
				   		<td>
				   			<?php print $tReponame; ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td><?php print _("Repository path").": "; ?></td>
				   		<td>
				   			<?php print $tRepopath; ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td><?php print _("Repository user").": "; ?></td>
				   		<td>
				   			<?php print $tRepouser; ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td><?php print _("Repository password").": "; ?></td>
				   		<td>
				   			<?php print $tRepopassword; ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr valign="top">
				   		<td>
				   			<?php print _("Notice").": "; ?>
				   		</td>
				   		<td>
				   			<?php print _("Please note that a repository can only be deleted when it is no longer used in any project. Removing a repository does not affect the subversion repository itself. It is only removed from the database!"); ?>
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