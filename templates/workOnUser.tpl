		<div id="edit_form">
			<form name="workOnUser" method="post">
				<table>
				   	<tr>
				      <td colspan="3"><h3><?php print _("User administration / edit user"); ?></h3></td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td width="150"><strong><?php print _("Username").": "; ?></strong></td>
				   		<td>
				   			<input type="text" name="fUserid" value="<?php print $tUserid; ?>" size="8" maxsize="255" <?php print $tReadonly; ?> />
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td><strong><?php print _("Name").": "; ?></strong></td>
				   		<td>
				   			<input type="text" name="fName" value="<?php print $tName; ?>" size="40" maxsize="255"  />
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td><strong><?php print _("Givenname").": "; ?></strong></td>
				   		<td>
				   			<input type="text" name="fGivenname" value="<?php print $tGivenname; ?>" size="40" maxsize="255" />
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td><strong><?php print _("Password").": "; ?></strong></td>
				   		<td>
				   			<input type="password" name="fPassword" value="<?php print $tPassword; ?>" size="40" maxsize="255" />
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td nowrap><strong><?php print _("Retype password").": "; ?></strong></td>
				   		<td>
				   			<input type="password" name="fPassword2" value="<?php print $tPassword2; ?>" size="40" maxsize="255" />
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td nowrap><strong><?php print _("Email address").": "; ?></strong></td>
				   		<td>
				   			<input type="text" name="fEmail" value="<?php print $tEmail; ?>" size="40" maxsize="255" />
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td nowrap><strong><?php print _("Password expires").": "; ?></strong></td>
				   		<td>
				   			<select name="fPasswordExpires">
				   				<?php
				   					if( $tPasswordExpires == 0 ) {
				   						print "\t\t\t\t\t\t\t\t<option value='0' selected>"._("no")."</option>\n";
				   						print "\t\t\t\t\t\t\t\t<option value='1'>"._("yes")."</option>\n";
				   					} else {
				   						print "\t\t\t\t\t\t\t\t<option value='0'>"._("no")."</option>\n";
				   						print "\t\t\t\t\t\t\t\t<option value='1' selected>"._("yes")."</option>\n";
				   					}
				   				?>
				   			</select>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td nowrap><strong><?php print _("Locked").": "; ?></strong></td>
				   		<td>
				   			<select name="fLocked">
				   				<?php
				   					if( $tLocked == 0 ) {
				   						print "\t\t\t\t\t\t\t\t<option value='0' selected>"._("no")."</option>\n";
				   						print "\t\t\t\t\t\t\t\t<option value='1'>"._("yes")."</option>\n";
				   					} else {
				   						print "\t\t\t\t\t\t\t\t<option value='0'>"._("no")."</option>\n";
				   						print "\t\t\t\t\t\t\t\t<option value='1' selected>"._("yes")."</option>\n";
				   					}
				   				?>
				   			</select>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td nowrap><strong><?php print _("Administrator").": "; ?></strong></td>
				   		<td>
				   			<select name="fAdministrator">
				   				<?php
				   					if( $tAdministrator == "n" ) {
				   						print "\t\t\t\t\t\t\t\t<option value='n' selected>"._("no")."</option>\n";
				   						print "\t\t\t\t\t\t\t\t<option value='y'>"._("yes")."</option>\n";
				   					} else {
				   						print "\t\t\t\t\t\t\t\t<option value='n'>"._("no")."</option>\n";
				   						print "\t\t\t\t\t\t\t\t<option value='y' selected>"._("yes")."</option>\n";
				   					}
				   				?>
				   			</select>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td nowrap><strong><?php print _("Repository user right").": "; ?></strong></td>
				   		<td>
				   			<select name="fUserRight">
				   				<?php
				   					if( $tUserRight == "read" ) {
				   						print "\t\t\t\t\t\t\t\t<option value='read' selected>"._("read")."</option>\n";
				   						print "\t\t\t\t\t\t\t\t<option value='write'>"._("write")."</option>\n";
				   					} else {
				   						print "\t\t\t\t\t\t\t\t<option value='read'>"._("read")."</option>\n";
				   						print "\t\t\t\t\t\t\t\t<option value='write' selected>"._("write")."</option>\n";
				   					}
				   				?>
				   			</select>
				   		</td>
				   		<td><?php print _("This right overrules the repository access right settings. A user with read permission only can't get write access to any repository!"); ?></td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td colspan="3"><strong><?php print _("Select global user rights"); ?></strong></td>
				   	</tr>
			   		<?php
			   			
			   			foreach( $tRightsAvailable as $right ) {
			   			
			   				$id						= $right['id'];
			   				
			   				print "\t\t\t\t\t\t<tr>\n";
			   				print "\t\t\t\t\t\t\t<td><strong>\n";
			   				print "\t\t\t\t\t\t\t\t".$right['right_name'].": ";
			   				print "\t\t\t\t\t\t\t</strong></td>\n";
			   				print "\t\t\t\t\t\t\t<td>\n";
			   				print "\t\t\t\t\t\t\t\t<select name='fId".$id."'>\n";
			   				
			   				$tNone						= "selected";
			   				$tRead						= "";
			   				$tEdit						= "";
			   				$tDelete					= "";   				
			   					
		   					if(strtolower($tRightsGranted[$id]) == "read") {
		   						$tNone				= "";
		   						$tRead				= "selected";
		   						$tEdit				= "";
		   						$tDelete			= "";
		   					} elseif(strtolower($tRightsGranted[$id]) == "edit" ) {
		   						$tNone				= "";
		   						$tRead				= "";
		   						$tEdit				= "selected";
		   						$tDelete			= "";
		   					} elseif(strtolower($tRightsGranted[$id]) == "delete" ) {
		   						$tNone				= "";
		   						$tRead				= "";
		   						$tEdit				= "";
		   						$tDelete			= "selected";
		   					}
			   				
			   							   				
			   				print "\t\t\t\t\t\t\t\t\t<option value='none' ".$tNone.">"._("none")."</option>\n";
			   				if( ($right['allowed_action'] == "read") or 
			   					($right['allowed_action'] == "edit") or 
			   					($right['allowed_action'] == "delete")) {
			   					
			   					print "\t\t\t\t\t\t\t\t\t<option value='read' ".$tRead.">"._("read")."</option>\n";
			   				}
			   				if( ($right['allowed_action'] == "edit") or
			   					($right['allowed_action'] == "delete") ) {
			   					
			   					print "\t\t\t\t\t\t\t\t\t<option value='edit' ".$tEdit.">"._("edit")."</option>\n";
			   				}
			   				if( $right['allowed_action'] == "delete" ) {
			   				
			   					print "\t\t\t\t\t\t\t\t\t<option value='delete' ".$tDelete.">"._("delete")."</option>\n";
			   				}
			   				print "\t\t\t\t\t\t\t\t</select>\n";
			   				print "\t\t\t\t\t\t\t</td>\n";
			   				print "\t\t\t\t\t\t\t<td>\n";
			   				print "\t\t\t\t\t\t\t\t".$right['description'];
			   				print "\t\t\t\t\t\t\t</td>\n";
			   				print "\t\t\t\t\t\t</tr>\n";
			   			
			   			}
			   		?>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="3" class="hlp_center">
				      	<input class="button" type="submit" name="fSubmit" value="<?php print _("Submit"); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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