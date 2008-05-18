		<div id="edit_form">
			<form name="rep_log" method="post">
				<table>
				   	<tr>
				      <td colspan="5"><h3><?php print _("Log report"); ?></h3></td>
				   	</tr>
				   	<tr>
				      <td colspan="5">&nbsp;</td>
				   	</tr>
				   	<tr class="theader">
				   		<td>
				   			<?php print _("Date"); ?>
				   		</td>
				   		<td>
				   			<?php print _("Username"); ?>
				   		</td>
				   		<td>
				   			<?php print _("IP Address"); ?>
				   		</td>
				   		<td>
				   			<?php print _("Logmessage"); ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<?php
				   		foreach( $tLogmessages as $entry ) {
				   		
				   			list($date, $time)		= splitDateTime( $entry['timestamp'] );
				   			
				   			print "\t\t\t\t\t<tr>\n";
				   			print "\t\t\t\t\t\t<td align='center' nowrap>".$date." ".$time."</td>\n";
				   			print "\t\t\t\t\t\t<td align='center'>".$entry['username']."</td>\n";
				   			print "\t\t\t\t\t\t<td>".$entry['ipaddress']."</td>\n";
				   			print "\t\t\t\t\t\t<td>".$entry['logmessage']."</td>\n";
				   			print "\t\t\t\t\t\t<td> </td>\n";
				   			print "\t\t\t\t\t</tr>\n";
				   		}
				   	?>
				   	<tr>
				      <td colspan="5">&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="5" class="hlp_center">
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
				      <td colspan="5" class="standout">
				      	<?php print $tMessage; ?>
				      </td>
				   	</tr>
				</table>
			</form>
		</div>