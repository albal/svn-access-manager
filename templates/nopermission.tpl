		<div id="editform">
			<form name="nopermission" method="post">
				<table>
				   	<tr>
				      <td colspan="3"><h3><?php print _("Permission denied"); ?></h3></td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td colspan="3">
				   			<?php print _("You do not have sufficient access rights. Permission denied. The attempt was logged."); ?>
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