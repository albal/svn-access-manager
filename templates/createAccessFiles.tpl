		<div id="edit_form">
			<form name="createAccessFiles" method="post">
				<table>
				   	<tr>
				      <td colspan="3"><h3><?php print _("Create Access Files"); ?></h3></td>
				   	</tr>
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
				      	<input class="button" type="submit" name="fSubmit" value="<?php print _("Yes"); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				      	<input class="button" type="submit" name="fSubmit" value="<?php print _("No"); ?>" />
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