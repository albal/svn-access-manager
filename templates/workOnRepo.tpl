		<div id="editform">
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
				   			<input type="text" name="fRepopath" value="<?php print no_magic_quotes($tRepopath); ?>" size="40" maxsize="255"  />
				   		</td>
				   		<td>
				   			&nbsp;
				   		</td>
				   	</tr>
				   	<tr>
				   		<td><strong><?php print _("Repository user").": "; ?></strong></td>
				   		<td>
				   			<input type="text" name="fRepouser" value="<?php print $tRepouser; ?>" size="40" maxsize="255"  />
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td><strong><?php print _("Repository password").": "; ?></strong></td>
				   		<td>
				   			<input type="text" name="fRepopassword" value="<?php print $tRepopassword; ?>" size="40" maxsize="255"  />
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td><strong><?php print _("Create repository in filesystem").": "; ?></strong></td>
				   		<?php
				   			if( $_SESSION[SVNSESSID]['task'] == "change" ) {
				   				$checked			= "disabled";
				   			} else {
					   			if( $tCreateRepo == "1" ) {
					   				$checked		= "checked";
					   			} else {
					   				$checked		= "";
					   			}
				   			}
				   		?>
				   		<td><input type="checkbox" name="fCreateRepo" value="1" <?php print $checked; ?> /></td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<?php
				   		if(isset( $CONF[SEPARATEFILESPERREPO]) && ($CONF[SEPARATEFILESPERREPO] != "YES")) {
				   			
			   				print "<!--\n";
				   			
				   		}
				   	?>
				   	<tr>
				   		<td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td colspan="3"><?php print _("If you need separate configuration files for each repository specify the locations of the files here. If you do not give a path and filename config parameters will be used and the filename will be replaced accordingly."); ?></td>
				   	</tr>
				   	<tr>
				   		<td nowrap><strong><?php print _("Auth user file").": "; ?></strong></td>
				   		<td>
				   			<input type="text" name="fAuthUserFile" value="<?php print $tAuthUserFile;?>" size="40" maxsize="255" />
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td nowrap><strong><?php print _("SVN access file").": "; ?></strong></td>
				   		<td>
				   			<input type="text" name="fSvnAccessFile" value="<?php print $tSvnAccessFile;?>" size="40" maxsize="255" />
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<?php
				   		if(isset( $CONF[SEPARATEFILESPERREPO]) && ($CONF[SEPARATEFILESPERREPO] != "YES")) {
				   			
			   				print "-->\n";
				   			
				   		} 
				   	?>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="3" class="hlpcenter">
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