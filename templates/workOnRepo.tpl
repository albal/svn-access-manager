		<div id="edit_form">
			<form name="workOnRepo" method="post">
				<table>
				   	<tr>
				      <td colspan="3"><h3><?php print _("Repository administration"); ?></h3></td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td width="150"><strong><?php print _("Repository name").": "; ?></strong></td>
				   		<td>
				   			<input type="text" name="fReponame" value="<?php print $tReponame; ?>" size="40" maxsize="255" />
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td><strong><?php print _("Repository path").": "; ?></strong></td>
				   		<td>
				   			<input type="text" name="fRepopath" value="<?php print $tRepopath; ?>" size="40" maxsize="255"  />
				   		</td>
				   		<td><?php print _("Path to the repository. If SVN Access Manager runs on the same host as the repository does use file:// for access to te repository. No username and password in required in this case."); ?></td>
				   	</tr>
				   	<tr>
				   		<td><strong><?php print _("Repository user").": "; ?></strong></td>
				   		<td>
				   			<input type="text" name="fRepouser" value="<?php print $tRepouser; ?>" size="40" maxsize="255"  />
				   		</td>
				   		<td><?php print _("The username and password if necessary for access to the repository"); ?></td>
				   	</tr>
				   	<tr>
				   		<td><strong><?php print _("Repository password").": "; ?></strong></td>
				   		<td>
				   			<input type="text" name="fRepopassword" value="<?php print $tRepopassword; ?>" size="40" maxsize="255"  />
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="3" class="hlp_center">
				      	<input type="image" name="fSubmit_ok" src="./images/ok.png" value="<?php print _("Submit"); ?>"  title="<?php print _("Submit"); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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