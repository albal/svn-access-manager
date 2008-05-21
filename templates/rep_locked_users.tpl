		<div id="edit_form">
			<form name="rep_log" method="post">
				<table>
				   	<tr>
				      <td colspan="6"><h3><?php print _("Log report"); ?></h3></td>
				   	</tr>
				   	<tr>
				      <td colspan="6">&nbsp;</td>
				   	</tr>
				   	<tr class="theader">
				   		<td>
				   			<?php print _("Last Modified"); ?>
				   		</td>
				   		<td>
				   			<?php print _("Modified by user"); ?>
				   		</td>
				   		<td>
				   			<?php print _("Username"); ?>
				   		</td>
				   		<td>
				   			<?php print _("Name"); ?>
				   		</td>
				   		<td>
				   			<?php print _("Givenname"); ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<?php
				   		if( is_array( $tLockedUsers ) ) {
				   		
					   		foreach( $tLockedUsers as $entry ) {
					   		
					   			list($date, $time)		= splitDateTime( $entry['modified'] );
					   			
					   			print "\t\t\t\t\t<tr>\n";
					   			print "\t\t\t\t\t\t<td align='center'>".$date." ".$time."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['modified_user']."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['userid']."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['name']."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['givenname']."</td>\n";
					   			print "\t\t\t\t\t\t<td> </td>\n";
					   			print "\t\t\t\t\t</tr>\n";
					   		}
					   		
				   		}
				   	?>
				   	<tr>
				      <td colspan="6">&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="6" class="hlp_center">
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
				      <td colspan="5" class="standout">
				      	<?php print $tMessage; ?>
				      </td>
				   	</tr>
				</table>
			</form>
		</div>