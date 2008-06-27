		<div id="edit_form">
			<form name="createAccessFiles" method="post">
				<table>
				   	<tr>
				      <td colspan="3"><h3><?php print _("Create Access Files"); ?></h3></td>
				   	</tr>
				   	<?php
				   		if( $CONF['createViewvcConf'] != "YES" ) {
				   			print "<!--\n";
				   		}
				   	?>	
				   	<tr>
				   		<td>
				   			<?php print _("Create viewvc configuration").": "; ?>
				   		</td>
				   		<td>
				   			<input type="radio" name="fViewvcConfig" value="YES" <?php print $tViewvcConfigYes; ?> />&nbsp;&nbsp;<?php print _("Yes"); ?>&nbsp;&nbsp;&nbsp;
				   			<input type="radio" name="fViewvcConfig" value="NO" <?php print $tViewvcConfigNo; ?> />&nbsp;&nbsp;<?php print _("No"); ?>
				   		</td>
				   		<td>
				   			<?php print _("Viewvc configuration is genereated for Apache webservers only!"); ?>
				   		</td>
				   	</tr>
				   	<tr>
				   		<td>
				   			<?php print _("Command to reload webserver configuration").": "; ?>
				   		</td>
				   		<td>
				   			<input type="text" name="fReload" value="<?php print $tReload; ?>" />
				   		</td>
				   		<td>
				   			<?php print _("The user running this webserver must be able to execute the command!"); ?>
				   		</td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<?php
				   		if( $CONF['createViewvcConf'] != "YES" ) {
				   			print "-->\n";
				   		}
				   	?>	
				   	<tr>
				   		<td colspan="3">
				   			<?php print _("Create files for user authentication and access rights?"); ?>
				   		</td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="3" class="hlp_center">
				      	<input type="image" name="fSubmit_y" src="./images/ok.png" value="<?php print _("Yes"); ?>" title="<?php print _("Yes"); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				      	<input type="image" name="fSubmit_n" src="./images/button_cancel.png" value="<?php print _("No"); ?>" title="<?php print _("No"); ?>" />
				      	
				      </td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td colspan="3">
				   			<?php print $tMessage; ?>
				   		</td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				</table>
			</form>
		</div>