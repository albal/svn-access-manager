		<div id="edit_form">
			<form name="workOnGroup" method="post">
				<table>
				   	<tr>
				      <td colspan="5"><h3><?php print _("Group administration / edit group"); ?></h3></td>
				   	</tr>
				   	<tr>
				      <td colspan="5">&nbsp;</td>
				   	</tr>
				   	<tr valign="top">
				   		<td width="150"><strong><?php print _("Group").": "; ?></strong></td>
				   		<td>
				   			<input type="text" name="fGroup" value="<?php print $tGroup; ?>" maxsize="255" />
				   		</td>
				   		<td width="30">&nbsp;</td>
				   		<td><strong><?php print _("Groupmembers").": "; ?></strong></td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr valign="top">
				   		<td><strong><?php print _("Description").": "; ?></strong></td>
				   		<td>
				   			<input type="text" name="fDescription" value="<?php print $tDescription; ?>" maxsize="255"  />
				   		</td>
				   		<td width="30">&nbsp;</td>
				   		<td>
				   			<select name="members[]" multiple="" size="15" style="width: 100%; height=200px;">
							<?php
								foreach($tMembers as $uid => $member) {
									$label = $member." [".$uid."]";
									print "\t\t\t\t\t\t\t<option value=\"$uid\" label=\"$label\">$label</option>\n";
								}   
							?>
							</select>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr valign="top">
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td nowrap>
							<input class="button" type="submit" name="fSubmit" value="<?php print _("Add member"); ?>" />&nbsp;&nbsp;&nbsp;
							<input class="button" type="submit" name="fSubmit" value="<?php print _("Remove member"); ?>" />
						</td>
						<td>&nbsp;</td>
					</tr>
				   	<tr>
				      <td colspan="5">&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="5" class="hlp_center">
				      	<input class="button" type="submit" name="fSubmit" value="<?php print _("Submit"); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				      	<input class="button" type="submit" name="fSubmit" value="<?php print _("Back"); ?>" />
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