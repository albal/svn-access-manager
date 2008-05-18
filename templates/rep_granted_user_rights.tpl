		<div id="edit_form">
			<form name="rep_granted_user_rights" method="post">
				<table>
				   	<tr>
				      <td colspan="4"><h3><?php print _("List of granted user rights"); ?></h3></td>
				   	</tr>
				   	<tr>
				      <td colspan="4">&nbsp;</td>
				   	</tr>
				   	<tr class="theader">
				   		<td>
				   			<strong><?php print _("Userid"); ?></strong>
				   		</td>
				   		<td>
				   			<strong><?php print _("Username"); ?></strong>
				   		</td>
				   		<td align="center">
				   			<strong><?php print _("Granted rights"); ?></strong>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<?php
				   						   		
				   		foreach( $tGrantedRights as $entry ) {
				   		
				   			print "\t\t\t\t\t<tr valign=\"top\">\n";
				   			print "\t\t\t\t\t\t<td>".$entry['userid']."</td>\n";
				   			print "\t\t\t\t\t\t<td nowrap>".$entry['name']."</td>\n";
				   			print "\t\t\t\t\t\t<td>".$entry['rights']."</td>\n";
				   			print "\t\t\t\t\t\t<td> </td>\n";
				   			print "\t\t\t\t\t</tr>\n";

				   		}
				   	?>
				   	<tr>
				      <td colspan="4">&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="4" class="hlp_center">
				      	<?php
				      		if( $tPrevDisabled != "disabled" ) {
				      			
				      			print "\t\t\t\t\t\t<input class='button' type='submit' name='fSubmit' value='"._("<<")."' /> \n";
				      			print "\t\t\t\t\t\t<input class='button' type='submit' name='fSubmit' value='"._("<")."' />          \n";
				      			
				      		}
				      		
				      		if( $tNextDisabled != "disabled" ) {
				      		
				      			print "\t\t\t\t\t\t<input class='button' type='submit' name='fSubmit' value='"._(">")."' /> \n";
				      			print "\t\t\t\t\t\t<input class='button' type='submit' name='fSubmit' value='"._(">>")."' />\n";
				      			
				      		}
				      	?>
				      </td>
				   	</tr>
				   	<tr>
				      <td colspan="4" class="standout">
				      	<?php print $tMessage; ?>
				      </td>
				   	</tr>
				</table>
			</form>
		</div>