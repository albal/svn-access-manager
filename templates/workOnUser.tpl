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
				   			<?php
				   				if( (isset($CONF['use_ldap'])) and (strtoupper($CONF['use_ldap']) == "YES") and ($_SESSION['svn_sessid']['task'] == "new") ) {
				   					
				   					print "\t\t\t\t\t\t\t<select name=\"fUserid\" $tReadonly onchange=\"changeUser();\">\n";
				   					print "\t\t\t\t\t\t\t\t<option value=\"default\">"._("--- Please select user ---")."</option>\n";
				   					foreach( $tUsers as $entry ) {
				   						$value	= $entry['uid'].":".$entry['name'].":".$entry['givenname'].":".$entry['emailaddress'].":";
				   						if( $entry['uid'] == $tUserid ) {
				   							print "\t\t\t\t\t\t\t\t<option value=\"".$value."\" selected>".$entry['name']." ".$entry['givenname']." (".$entry['uid'].")"."</option>\n";
				   						} else {
				   							print "\t\t\t\t\t\t\t\t<option value=\"".$value."\">".$entry['name']." ".$entry['givenname']." (".$entry['uid'].")"."</option>\n";
				   						}
				   					}
				   					print "\t\t\t\t\t\t\t</select>\n";
				   					
				   				} else {
				   					
				   					print "\t\t\t\t\t\t\t<input type=\"text\" name=\"fUserid\" value=\"$tUserid\" size=\"8\" maxsize=\"255\" $tReadonly />\n";
				   					
				   				}
				   			?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td><strong><?php print _("Name").": "; ?></strong></td>
				   		<td>
				   			<input type="text" name="fName" value="<?php print $tName; ?>" size="40" maxsize="255"  title='<?php print _("Enter the name of the user.");?>' />
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td><strong><?php print _("Given name").": "; ?></strong></td>
				   		<td>
				   			<input type="text" name="fGivenname" value="<?php print $tGivenname; ?>" size="40" maxsize="255" title='<?php print _("Enter the given name of the user.");?>' />
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<?php
				   		if( (isset($CONF['use_ldap'])) and (strtoupper($CONF['use_ldap']) != "YES") ) {
					   		print "\t\t\t\t\t\t<tr>\n";
						   	print "\t\t\t\t\t\t\t<td><strong>"._("Password").": </strong></td>\n";
						   	print "\t\t\t\t\t\t\t<td>\n";
						   	print "\t\t\t\t\t\t\t\t<input type=\"password\" name=\"fPassword\" value=\"$tPassword\" size=\"40\" maxsize=\"255\" autocomplete=\"off\" title=\""._("Enter the password. Keep in mind that the password must be set accordingly to the password policy.")."\"/>\n";
						   	print "\t\t\t\t\t\t\t</td>\n";
						   	print "\t\t\t\t\t\t\t<td>&nbsp;</td>\n";
							print "\t\t\t\t\t\t</tr>\n";
						   	print "\t\t\t\t\t\t<tr>\n";
						   	print "\t\t\t\t\t\t\t<td nowrap><strong>"._("Retype password").": </strong></td>\n";
						   	print "\t\t\t\t\t\t\t<td>\n";
						   	print "\t\t\t\t\t\t\t\t<input type=\"password\" name=\"fPassword2\" value=\"$tPassword2\" size=\"40\" maxsize=\"255\" title=\""._("Retype the password to avoid typos.")."\"/>\n";
						   	print "\t\t\t\t\t\t\t</td>\n";
						   	print "\t\t\t\t\t\t\t<td>&nbsp;</td>\n";
						   	print "\t\t\t\t\t\t</tr>\n";
						}
				   	?>
					   	
				   	<tr>
				   		<td nowrap><strong><?php print _("Email address").": "; ?></strong></td>
				   		<td>
				   			<input type="text" name="fEmail" value="<?php print $tEmail; ?>" size="40" maxsize="255" title="<?php print _("Enter the email address of the user. Please fill in a valid email address. Otherwise the user will not be able to receive notifications.");?>"/>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
			   		<?php
						if (isset($CONF['column_custom1'])) {
                            print "\n\t\t\t\t\t\t<tr>\n";
                            print "\t\t\t\t\t\t\t<td nowrap><strong>".$CONF['column_custom1'].": </strong></td>\n";
                            print "\t\t\t\t\t\t\t<td>\n";
                            print "\t\t\t\t\t\t\t\t<input type='text' name='fCustom1' value='".$tCustom1."' size='40' maxsize='255' />\n";
                            print "\t\t\t\t\t\t\t</td>\n";
                            print "\t\t\t\t\t\t\t<td>&nbsp;</td>\n";
                            print "\t\t\t\t\t\t</tr>\n";
						}
                        if (isset($CONF['column_custom2'])) {
                            print "\n\t\t\t\t\t\t<tr>\n";
                            print "\t\t\t\t\t\t\t<td nowrap><strong>".$CONF['column_custom2'].": </strong></td>\n";
                            print "\t\t\t\t\t\t\t<td>\n";
                            print "\t\t\t\t\t\t\t\t<input type='text' name='fCustom2' value='".$tCustom2."' size='40' maxsize='255' />\n";
                            print "\t\t\t\t\t\t\t</td>\n";
                            print "\t\t\t\t\t\t\t<td>&nbsp;</td>\n";
                            print "\t\t\t\t\t\t</tr>\n";
                        }
                        if (isset($CONF['column_custom3'])) {
                            print "\n\t\t\t\t\t\t<tr>\n";
                            print "\t\t\t\t\t\t\t<td nowrap><strong>".$CONF['column_custom3'].": </strong></td>\n";
                            print "\t\t\t\t\t\t\t<td>\n";
                            print "\t\t\t\t\t\t\t\t<input type='text' name='fCustom3' value='".$tCustom3."' size='40' maxsize='255' />\n";
                            print "\t\t\t\t\t\t\t</td>\n";
                            print "\t\t\t\t\t\t\t<td>&nbsp;</td>\n";
                            print "\t\t\t\t\t\t</tr>\n";
                        }
                                                
			   			if( (isset($CONF['use_ldap'])) and (strtoupper($CONF['use_ldap']) != "YES") ) {
			   				print "\n\t\t\t\t\t\t<tr>\n";
			   				print "\t\t\t\t\t\t\t<td nowrap><strong>"._("Password expires").": </strong></td>\n";
			   				print "\t\t\t\t\t\t\t<td>\n";
			   				print "\t\t\t\t\t\t\t\t<select name=\"fPasswordExpires\" $tDisabledAdmin title=\""._("Select if the user password should expire.")."\">\n";
		   					if( $tPasswordExpires == 0 ) {
		   						print "\t\t\t\t\t\t\t\t<option value='0' selected>"._("no")."</option>\n";
		   						print "\t\t\t\t\t\t\t\t<option value='1'>"._("yes")."</option>\n";
		   					} else {
		   						print "\t\t\t\t\t\t\t\t<option value='0'>"._("no")."</option>\n";
		   						print "\t\t\t\t\t\t\t\t<option value='1' selected>"._("yes")."</option>\n";
		   					}
			   				print "\t\t\t\t\t\t\t\t</select>\n";
			   				print "\t\t\t\t\t\t\t</td>\n";
			   				print "\t\t\t\t\t\t\t<td>&nbsp;</td>\n";
			   				print "\t\t\t\t\t\t</tr>\n";
			   			}
			   		?>
				   	
				   	<tr>
				   		<td nowrap><strong><?php print _("Locked").": "; ?></strong></td>
				   		<td>
				   			<select name="fLocked" <?php print $tDisabledAdmin;?> title="<?php print _("A locked user can not work any longer with the subversion repositories. If the user password expiered, the user will be locked automatically.");?>">
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
				   			<select name="fAdministrator" <?php print $tDisabledAdmin;?> title="<?php print _("An administrator has more privileges as a normal user. Administrators have a stronger password policy as normal users.");?>">
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
				   			<select name="fUserRight" title="<?php print _("This right overrules the repository access right settings. A user with read permission only can't get write access to any repository!"); ?>">
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
				   		<td>&nbsp;</td>
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
			   				print "\t\t\t\t\t\t\t\t<select name='fId".$id."' title=\"".$right['description']."\">\n";
			   				
			   				$tNone						= "selected";
			   				$tRead						= "";
			   				$tAdd						= "";
			   				$tEdit						= "";
			   				$tDelete					= "";   		
			   					
			   				if( isset($tRightsGranted[$id]) ) {
			   					if(strtolower($tRightsGranted[$id]) == "read") {
			   						$tNone				= "";
			   						$tRead				= "selected";
			   						$tAdd				= "";
			   						$tEdit				= "";
			   						$tDelete			= "";  
			   					} elseif(strtolower($tRightsGranted[$id]) == "add" ) {
			   						$tNone				= "";
			   						$tRead				= "";
			   						$tAdd				= "selected";
			   						$tEdit				= "";
			   						$tDelete			= "";
			   					} elseif(strtolower($tRightsGranted[$id]) == "edit" ) {
			   						$tNone				= "";
			   						$tRead				= "";
			   						$tAdd				= "";
			   						$tEdit				= "selected";
			   						$tDelete			= "";
			   					} elseif(strtolower($tRightsGranted[$id]) == "delete" ) {
			   						$tNone				= "";
			   						$tRead				= "";
			   						$tAdd				= "";
			   						$tEdit				= "";
			   						$tDelete			= "selected";
			   					}
		   					}
			   				
			   							   				
			   				print "\t\t\t\t\t\t\t\t\t<option value='none' ".$tNone.">"._("none")."</option>\n";
			   				if( (($right['allowed_action'] == "read") 		or 
			   					 ($right['allowed_action'] == "add") 		or
			   					 ($right['allowed_action'] == "edit") 		or 
			   					 ($right['allowed_action'] == "delete"))	
			   				  ) {
			   					
			   					print "\t\t\t\t\t\t\t\t\t<option value='read' ".$tRead.">"._("read")."</option>\n";
			   				}
			   				if( (($right['allowed_action'] == "add" ) 		or
			   				     ($right['allowed_action'] == "edit") 		or
			   					 ($right['allowed_action'] == "delete"))	
			   				  ) {
			   					print "\t\t\t\t\t\t\t\t\t<option value='add' ".$tAdd.">"._("add")."</option>\n";
			   				}
			   				if( (($right['allowed_action'] == "edit") 		or
			   					 ($right['allowed_action'] == "delete"))	
			   				   ) {
			   					
			   					print "\t\t\t\t\t\t\t\t\t<option value='edit' ".$tEdit.">"._("edit")."</option>\n";
			   				}
			   				if( ($right['allowed_action'] == "delete")		
			   				  ) {
			   				
			   					print "\t\t\t\t\t\t\t\t\t<option value='delete' ".$tDelete.">"._("delete")."</option>\n";
			   				}
			   				print "\t\t\t\t\t\t\t\t</select>\n";
			   				print "\t\t\t\t\t\t\t</td>\n";
			   				print "\t\t\t\t\t\t\t<td>\n";
			   				print "\t\t\t\t\t\t\t\t ";
			   				print "\t\t\t\t\t\t\t</td>\n";
			   				print "\t\t\t\t\t\t</tr>\n";
			   			
			   			}
			   		?>
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
		<script type="text/javascript">
		
			function changeUser() {
			
				var uid  = document.forms.workOnUser.fUserid.value;
				var arr  = uid.split(":");
				
				document.forms.workOnUser.fName.value = arr[1];
				document.forms.workOnUser.fGivenname.value = arr[2];
				document.forms.workOnUser.fEmail.value = arr[3];
 
			}
		</script>
