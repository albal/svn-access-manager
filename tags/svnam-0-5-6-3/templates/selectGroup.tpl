		<div id="edit_form">
			<form name="selectGroup" method="post">
				<table>
				   	<tr>
				      <td colspan="3"><h3><?php print _("Group acccess right administration / Step 1: select group"); ?></h3></td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td nowrap><strong><?php print _("Group").": "; ?></strong></td>
				   		<td>
				   			<select name="fGroup" title="<?php print _("Select group to work with.");?>">
				   				<?php
				   					foreach( $tGroups as $groupId => $groupName ) {
				   					
				   						if( $tGroup == $groupId ) {
				   						
				   							print "\t\t\t\t\t\t\t\t<option value='".$groupId."' selected>".$groupName."</option>\n";
				   							
				   						} else {
				   						
				   							print "\t\t\t\t\t\t\t\t<option value='".$groupId."'>".$groupName."</option>\n";
				   							
				   						}
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