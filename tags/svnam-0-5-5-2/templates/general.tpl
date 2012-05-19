		<div id="edit_form">
			<form name="general" method="post">
				<table>
				   	<tr>
				      <td colspan=""><h3><?php print _("General"); ?></h3></td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td  width="130">
				   			<strong><?php print _("Username").": "; ?></strong>
				   		</td>
				   		<td>
				   			<input type="text" name="fUserid" value="<?php print $tUserid; ?>" readonly />
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td>
				   			<strong><?php print _("Given name").": "; ?></strong>
				   		</td>
				   		<td>
				   			<input type="text" name="fGivenname" value="<?php print $tGivenname; ?>" />
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td>
				   			<strong><?php print _("Name").": "; ?></strong>
				   		</td>
				   		<td>
				   			<input type="text" name="fName" value="<?php print $tName; ?>" />
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td>
				   			<strong><?php print _("Email").": "; ?></strong>
				   		</td>
				   		<td>
				   			<input type="text" name="fEmail" value="<?php print $tEmail; ?>" size="40" />
				   		</td>
				   		<td><?php print _("Take care that you have a valid email address submitted. Otherwise notifications concerning your account will be lost!"); ?></td>
				   	</tr>
				   	<tr>
				   		<td>
				   			<strong><?php print _("Security question").": "; ?></strong>
				   		</td>
				   		<td>
				   			<input type="text" name="fSecurityQuestion" value="<?php print $tSecurityQuestion;?>" size="40" maxsize="255" /> 
				   		</td>
				   		<td><?php print _("Question to answer before a password reset."); ?></td>
				   	</tr>
				   	<tr>
				   		<td>
				   			<strong><?php print _("Security question answer").": "; ?></strong>
				   		</td>
				   		<td>
				   			<input type="text" name="fAnswer" value="<?php print $tAnswer; ?>" size="40" maxsize="255" />
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td nowrap>
				   			<strong><?php print _("Password modified").": "; ?></strong>
				   		</td>
				   		<td>
				   			<input type="text" name="fpwModified" value="<?php print $tPwModified; ?>" readonly />
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td>
				   			<strong><?php print _("Locked").": "; ?></strong>
				   		</td>
				   		<td>
				   			<input type="text" name="fLocked" value="<?php print $tLocked; ?>" readonly />
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
				      	<td colspan="3" class="standout">
				      		<?php print $tMessage; ?>
				      	</td>
				   	</tr>
				</table>
			</form>
		</div>