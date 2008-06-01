		<div id="edit_form">
			<form name="deleteUser" method="post">
				<table>
				   	<tr>
				      <td colspan="3"><h3><?php print _("User administration / delete user"); ?></h3></td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td width="150"><?php print _("Username").": "; ?></td>
				   		<td>
				   			<?php print $tUserid; ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td><?php print _("Name").": "; ?></td>
				   		<td>
				   			<?php print $tName; ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td><?php print _("Given name").": "; ?></td>
				   		<td>
				   			<?php print $tGivenname; ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td nowrap><?php print _("Email address").": "; ?></td>
				   		<td>
				   			<?php print $tEmail; ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td nowrap><?php print _("Password expires").": "; ?></td>
				   		<td>
				   			<?php
				   					if( $tPasswordExpires == 0 ) {
				   						print "\t\t\t\t\t\t\t\t"._("no")."\n";
				   					} else {
				   						print "\t\t\t\t\t\t\t\t"._("yes")."\n";
				   					}
				   			?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td nowrap><?php print _("Locked").": "; ?></td>
				   		<td>
				   			<?php
				   					if( $tLocked == 0 ) {
				   						print "\t\t\t\t\t\t\t\t"._("no")."\n";
				   					} else {
				   						print "\t\t\t\t\t\t\t\t"._("yes")."\n";
				   					}
				   			?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td nowrap><?php print _("Administrator").": "; ?></td>
				   		<td>
				   				<?php
				   					if( $tAdministrator == "n" ) {
				   						print "\t\t\t\t\t\t\t\t"._("no")."\n";
				   					} else {
				   						print "\t\t\t\t\t\t\t\t"._("yes")."\n";
				   					}
				   				?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="3" class="hlp_center">
				      	<input type="image" name="fSubmit_ok" src="./images/ok.png" value="<?php print _("Delete"); ?>"  title="<?php print _("Delete"); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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