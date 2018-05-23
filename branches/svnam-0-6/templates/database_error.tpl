		<div id="editform">
			<form name="database_error" method="post">
				<table>
				   	<tr>
				      <td colspan="3"><h3><?php print _("Database error"); ?></h3></td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td colspan="3">
				   			<?php print _("A database error occured:"); ?>
				   		</td>
				   	</tr>
				   	<tr>
				   		<td width="100">
				   			<strong><?php print _("Errormessage").": "; ?></strong>
				   		</td>
				   		<td>
				   			<?php print $tDbError; ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td>
				   			<strong><?php print _("Query").": "; ?></strong>
				   		</td>
				   		<td>
				   			<?php print $tQuery; ?>
				   		</td>
				   		<td>&nbsp;</td>
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