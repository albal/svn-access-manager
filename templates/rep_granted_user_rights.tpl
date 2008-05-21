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
				      			
				      			print "\t\t\t\t\t\t<input type='image' name='fSubmit_f' src='./images/first.png' value='"._("<<")."' /> \n";
				      			print "\t\t\t\t\t\t<input type='image' name='fSubmit_p' src='./images/previous.png' value='"._("<")."' />          \n";

				      		} else {
				      		
				      			print "\t\t\t\t\t\t<img src='./images/clear.gif' width='24' height='24'> \n";
				      			print "\t\t\t\t\t\t<img src='./images/clear.gif' width='24' height='24'>          \n";
				      		}
				      		
				      		if( $tNextDisabled != "disabled" ) {
				      		
				      			print "\t\t\t\t\t\t<input type='image' name='fSubmit_n' src='./images/next.png' value='"._(">")."' /> \n";
				      			print "\t\t\t\t\t\t<input type='image' name='fSubmit_p' src='./images/last.png' value='"._(">>")."' />\n";

				      		} else {
				      		
				      			print "\t\t\t\t\t\t<img src)'./images/clear.gif' width='24' height='24'> \n";
				      			print "\t\t\t\t\t\t<img src)'./images/clear.gif' width='24' height='24'>\n";
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