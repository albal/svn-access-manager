		<div id="edit_form">
			<form name="preferences" method="post">
				<table>
				   	<tr>
				      <td colspan="3"><h3><?php print _("Preferences"); ?></h3></td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td width="150"><strong><?php print _("Records per page").": "; ?></strong></td>
				   		<td>
				   			<input type="text" name="fPageSize" value="<?php print $tPageSize; ?>" size="3" maxsize="3" title="<?php print _("Number of lines of a table shown on a page.");?>"/>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<!--
				   	<tr>
				   		<td width="150"><strong><?php print _("Sort user by").": "; ?></strong></td>
				   		<td>
				   			<input type="radio" name="fSortField" value="name,givenname" <?php print $tName; ?> />&nbsp;&nbsp;<?php print _("Name, given name"); ?>&nbsp;&nbsp;&nbsp;
				   			<input type="radio" name="fSortField" value="userid" <?php print $tUserid; ?> />&nbsp;&nbsp;<?php print _("Username"); ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td width="150"><strong><?php print _("Sort user order").": "; ?></strong></td>
				   		<td>
				   			<input type="radio" name="fSortOrder" value="ASC" <?php print $tAsc; ?> />&nbsp;&nbsp;<?php print _("ascending"); ?>&nbsp;&nbsp;&nbsp;
				   			<input type="radio" name="fSortOrder" value="DESC" <?php print $tDesc; ?> />&nbsp;&nbsp;<?php print _("descending"); ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	-->
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