		<div id="edit_form">
			<form action="rep_access_rights.php" name="getDateforAccessRights" method="post">
				<table>
				   	<tr>
				      <td colspan="3"><h3><?php print _("Date for access rights report"); ?></h3></td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr valign="top">
				   		<td width="150"><strong><?php print _("Date").": "; ?></strong></td>
				   		<td>
				   			<input type="text" name="fDate" value="<?php print $tDate; ?>" size="10" maxsize="10" />
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="3" class="hlp_center">
				      	<input class="button" type="submit" name="fSubmit" value="<?php print _("Create report"); ?>" />
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