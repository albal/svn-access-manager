		<div id="edit_form">
			<form name="buld_add" method="post">
				<h3><?php print _("Bulk add LDAP users"); ?></h3>
				<table id="bulkuseradd_table">
				   	<thead>
				   		<tr >
				   			<th class="ui-table-deactivate">
					   			<?php print _("Action"); ?>
					   		</th>
					   		<th class="ui-table-default">
					   			<?php print _("UserId"); ?>
					   		</th>
					   		<th class="ui-table-default">
					   			<?php print _("Name"); ?>
					   		</th>
					   		<th class="ui-table-default">
					   			<?php print _("Given name"); ?>
					   		</th>
					   		<th class="ui-table-default">
					   			<?php print _("Email"); ?>
					   		</th>
					   		<th class="ui-table-default">
					   			<?php print _("Repository user access").": ";?>
					   			<select name="fUserRight" title="<?php print _("This right overrules the repository access right settings. A user with read permission only can't get write access to any repository! All users will be added with the same right!"); ?>">
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
					   		</th>
					   		<th class="ui-table-default">
					   			&nbsp;
					   		</th>
					   	</tr>
				   	</thead>
				   	<tbody>
				   		<?php
					   		foreach( $tUsers as $userid => $entry ) {
					   			
					   			if( $entry['selected'] == 1 ) {
					   				$checked			= "checked=checked";
					   			} else {
					   				$checked			= "";
					   			}
					   			
					   			print "\t\t\t\t\t<tr>\n";
					   			print "\t\t\t\t\t\t<td><input type=\"checkbox\" name=\"fToAdd[]\" value=\"".$entry['userid']."\" $checked $tDisabled /></td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['userid']."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['name']."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['givenname']."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['emailaddress']."</td>\n";
					   			print "\t\t\t\t\t\t<td>&nbsp;</td>\n";
					   			if( $entry['added'] == 1 ) {
					   				print "\t\t\t\t\t\t<td>"._("User successfully added")."</td>\n";
					   			} else {
					   				print "\t\t\t\t\t\t<td>&nbsp;</td>\n";
					   			}
					   			print "\t\t\t\t\t</tr>\n";
					   			
					   		}
					   	?>
				   	</tbody>
					<tfoot>
						<tr>
					      <td colspan="7">&nbsp;</td>
					   	</tr>
					   	<tr>
					      <td colspan="7" class="hlp_center">
					      	<?php
					      		if( ($rightAllowed == "add") or
					      		    ($rightAllowed == "edit") or
					      		    ($rightAllowed == "delete") ) {
					      		
					      			print "<input type=\"image\" name=\"fSubmit_new\" src=\"./images/add_user.png\" value=\""._("Add user")."\"  title=\""._("Add user")."\" />     ";
					      		}
					      	?>
					      	
					      	<input type="image" name="fSubmit_back" src="./images/button_cancel.png" value="<?php print _("Back"); ?>" title="<?php print _("Back"); ?>" />
					      </td>
					   	</tr>
					   	<tr>
					      <td colspan="7" class="standout">
					      	<?php print $tMessage; ?>
					      </td>
					   	</tr> 	
					</tfoot>
				   	
				</table>
			</form>
			<script type="text/javascript">
					
					$("#edit_form *").tooltip({
						showURL: false
					});
			</script>
		</div>
