		<div id="edit_form">
			<form name="user_list" method="post">
				<table>
				   	<tr>
				      <td colspan="11"><h3><?php print _("User administration"); ?></h3></td>
				   	</tr>
				   	<tr>
				      <td colspan="11">&nbsp;</td>
				   	</tr>
				   	<tr class="theader">
				   		<td>
				   			<?php print _("UserId"); ?>
				   		</td>
				   		<td>
				   			<?php print _("Name"); ?>
				   		</td>
				   		<td>
				   			<?php print _("Given name"); ?>
				   		</td>
				   		<td>
				   			<?php print _("Email"); ?>
				   		</td>
				   		<td>
				   			<?php print _("Right"); ?>
				   		</td>
				   		<td>
				   			<?php print _("Locked"); ?>
				   		</td>
				   		<td align="center">
				   			<?php print _("Password changed"); ?>
				   		</td>
				   		<td align="center">
				   			<?php print _("Password expires"); ?>
				   		</td>
				   		<td>
				   			<?php print _("Administrator"); ?>
				   		</td>
				   		<?php
				   			if( (isset($CONF['use_ldap'])) and (strtoupper($CONF['use_ldap']) == "YES") ) {
				   				print "\t\t\t\t\t\t<td>\n";
				   				print "\t\t\t\t\t\t\t"._("LDAP User");
				   				print "\t\t\t\t\t\t</td>\n";
				   			}
				   		?>
				   		<td width="20">&nbsp;</td>
				   		<td nowrap>
				   			<?php print _("Action"); ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<?php
				   		foreach( $tUsers as $entry ) {
				   		
				   			list($date, $time)		= splitDateTime( $entry['password_modified'] );
				   			$pwChanged 				= $date." ".$time; 
				   			$locked					= $entry['locked'] == 0 			? _("no") : _("yes");
				   			$expires				= $entry['passwordexpires'] == 0 	? _("no") : _("yes");
				   			$admin					= $entry['admin'] == "n" 			? _("no") : _("yes");
				   			
				   			if( ($rightAllowed == "edit") or
				   			    ($rightAllowed == "delete" ) ) {
				   			    $url				= htmlentities("workOnUser.php?id=".$entry['id']."&task=change");
				   			    $edit				= "<a href=\"$url\" title=\""._("Change")."\" alt=\""._("Change")."\"><img src=\"./images/edit.png\" border=\"0\" /></a>";
				   			} else {
				   				$edit				= "";
				   			}
				   			
				   			if( $rightAllowed == "delete" ) {
				   				$url				= htmlentities("deleteUser.php?id=".$entry['id']."&task=delete");
				   				$delete				= "<a href=\"$url\" title=\""._("Delete")."\" alt=\""._("Delete")."\"><img src=\"./images/edittrash.png\" border=\"0\" /></a>";
				   			} else {
				   				$delete				= "";
				   			}
				   			$action					= $edit."     ".$delete;
				   			
				   			print "\t\t\t\t\t<tr>\n";
				   			print "\t\t\t\t\t\t<td>".$entry['userid']."</td>\n";
				   			print "\t\t\t\t\t\t<td>".$entry['name']."</td>\n";
				   			print "\t\t\t\t\t\t<td>".$entry['givenname']."</td>\n";
				   			print "\t\t\t\t\t\t<td>".$entry['emailaddress']."</td>\n";				   			
				   			print "\t\t\t\t\t\t<td align='center'>".$entry['user_mode']."</td>\n";
				   			print "\t\t\t\t\t\t<td align='center'>".$locked."</td>\n";
				   			print "\t\t\t\t\t\t<td align='center'>".$pwChanged."</td>\n";
				   			print "\t\t\t\t\t\t<td align='center'>".$expires."</td>\n";
				   			print "\t\t\t\t\t\t<td align='center'>".$admin."</td>\n";
				   			if( (isset($CONF['use_ldap'])) and (strtoupper($CONF['use_ldap']) == "YES") ) {
				   				if( isset( $entry['ldap'] ) ) {
				   					$ldap			= ($entry['ldap'] == 1) ? _("yes") : _("no");
				   				} else {
				   					$ldap			= _("No");
				   				}
				   				print "\t\t\t\t\t\t<td align='center'>".$ldap."</td>\n";
				   			}
				   			print "\t\t\t\t\t\t<td> </td>\n";
				   			print "\t\t\t\t\t\t<td nowrap>".$action."</td>\n";
				   			print "\t\t\t\t\t\t<td> </td>\n";
				   			print "\t\t\t\t\t</tr>\n";
				   		}
				   	?>
				   	<tr>
				      <td colspan="11">&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="11" class="hlp_center">
				      	<?php
				      		if( ($rightAllowed == "add") or
				      		    ($rightAllowed == "edit") or
				      		    ($rightAllowed == "delete") ) {
				      		
				      			print "<input type=\"image\" name=\"fSubmit_new\" src=\"./images/add_user.png\" value=\""._("New user")."\"  title=\""._("New user")."\" />     ";
				      		}
				      	?>
				      	
				      	<input type="image" name="fSubmit_back" src="./images/button_cancel.png" value="<?php print _("Back"); ?>" title="<?php print _("Back"); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				      	
				      	<?php
				      		if( $tPrevDisabled != "disabled" ) {
				      			
				      			print "\t\t\t\t\t\t<input type='image' name='fSubmit_f' src='./images/first.png' value='"._("<<")."' /> \n";
				      			print "\t\t\t\t\t\t<input type='image' name='fSubmit_p' src='./images/previous.png' value='"._("<")."' />          \n";

				      		} else {
				      		
				      			print "\t\t\t\t\t\t<img src='./images/clear.gif' width='24' height='24' /> \n";
				      			print "\t\t\t\t\t\t<img src='./images/clear.gif' width='24' height='24' />          \n";
				      		}
				      		
				      		if( $tNextDisabled != "disabled" ) {
				      		
				      			print "\t\t\t\t\t\t<input type='image' name='fSubmit_n' src='./images/next.png' value='"._(">")."' /> \n";
				      			print "\t\t\t\t\t\t<input type='image' name='fSubmit_l' src='./images/last.png' value='"._(">>")."' />\n";

				      		} else {
				      		
				      			print "\t\t\t\t\t\t<img src='./images/clear.gif' width='24' height='24' /> \n";
				      			print "\t\t\t\t\t\t<img src='./images/clear.gif' width='24' height='24' />\n";
				      		}
				      	?>
				      </td>
				   	</tr>
				   	<tr>
				      <td colspan="11" class="standout">
				      	<?php print $tMessage; ?>
				      </td>
				   	</tr>
				</table>
			</form>
		</div>