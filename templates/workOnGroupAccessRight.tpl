		<div id="edit_form">
			<form name="workOnGroupAccessRight" method="post">
				<table>
				   	<tr>
				      <td colspan="3"><h3><?php print _("Group access right administration / Step 2: select user and access rights"); ?></h3></td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td nowrap><strong><?php print _("Group").": "; ?></strong></td>
				   		<td>
				   			<?php print $tGroupName; ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr valign="top">
				   		<td nowrap><strong><?php print _("Select user").": "; ?></strong></td>
				   		<td>
				   			<select name="fUser" size="1" <?php print $tReadonly;?> style="width: 100%; height=200px;" title="<?php print _("Select the user to grant access to the group.");?>">
				   				
				   				<?php  					
				   					foreach( $tUsers as $id => $user ) {
				   					
				   						if( $id == $tUser ) {
				   							print '\t\t\t\t\t\t\t\t<option value="'.$id.'" selected>'.$user.' ['.$id.']</option>\n';
				   						} else {
				   							print '\t\t\t\t\t\t\t\t<option value="'.$id.'">'.$user.' ['.$id.']</option>\n';
				   						}				   						
				   					}
				   				?>
				   			</select>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td><strong><?php print _("Access right").": ";?></strong></td>
				   		<td>
				   			<select name="fRight" size="1" title="<?php print _("Select the access right to the group.");?>">
				   				<?php
				   					$none							= "";
				   					$read							= "";
				   					$edit							= "";
				   					$delete							= "";
				   					
				   					if( $tRight == "none" ) {
				   						$none						= "selected";
				   					}
				   					if( $tRight == "read" ) {
				   						$read						= "selected";
				   					}
				   					if( $tRight == "edit" ) {
				   						$edit						= "selected";
				   					}
				   					if( $tRight == "delete" ) {
				   						$delete 					= "selected";
				   					}
				   					
				   					#print "\t\t\t\t\t\t\t<option value='none' ".$none." >"._("none")."</option>\n";
					   				print "\t\t\t\t\t\t\t<option value='read' ".$read." >"._("read")."</option>\n";
					   				print "\t\t\t\t\t\t\t<option value='edit' ".$edit." >"._("edit")."</option>\n";
					   				print "\t\t\t\t\t\t\t<option value='delete' ".$delete." >"._("delete")."</option>\n";
				   				?>
				   			</select>
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