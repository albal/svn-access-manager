		<div id="edit_form">
			<form action="rep_show_group.php" name="show_group_select" method="post">
				<table>
				   	<tr>
				      <td colspan="3"><h3><?php print _("Group to show"); ?></h3></td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr valign="top">
				   		<td width="150"><strong><?php print _("Group").": "; ?></strong></td>
				   		<td>
				   			<select name="fGroup">
				   				<?php
				   					print "\t\t\t<option value='default'>"._("--- Select group ---")."</option>\n";
				   					foreach( $tGroups as $entry ) {
				   						 
				   						print "\t\t\t<option value='".$entry['id']."'>".$entry['groupname']."</option>\n";
				   					}
				   				?>
				   			</select>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="3" class="hlp_center">
				      	<input type="image" name="fSubmit_show" src="./images/ok.png" value="<?php print _("Create report"); ?>"  title="<?php print _("Create report"); ?>" />
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