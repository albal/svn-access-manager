		<div id="edit_form">
			<form name="workOnGroup" method="post">
				<table>
					<tr valign="top">
						<td>
							<table>
								<tr>
							      <td colspan="3"><h3><?php print _("Group administration / edit group"); ?></h3></td>
							   	</tr>
							   	<tr>
							      <td colspan="3">&nbsp;</td>
							   	</tr>
							   	<tr valign="top">
							   		<td width="150"><strong><?php print _("Group").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fGroup" value="<?php print $tGroup; ?>" maxsize="255" />
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td><strong><?php print _("Description").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fDescription" value="<?php print $tDescription; ?>" maxsize="255"  />
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
							      <td colspan="3">&nbsp;</td>
							   	</tr>
							   	<tr>
							      <td colspan="3" class="standout">
							      	<?php print $tMessage; ?>
							      </td>
							   	</tr>
							</table>
						</td>
					
						<td width="20">
							&nbsp;
						</td>
					
						<td>
							<table>
								<tr>
									<td colspan="3">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="3">&nbsp;</td>
								</tr>
								<tr valign="top">
									<td><strong><?php print _("Groupmembers").": "; ?></strong></td>
									<td>
										<select name="members[]" multiple="" size="10" style="width: 100%; height=200px;">
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
								<tr>
									<td>&nbsp;</td>
									<td nowrap>
										<input type="image" name="fSubmit_add" src="./images/add_user.png" value="<?php print _("Add member"); ?>"  title="<?php print _("Add member"); ?>" />&nbsp;&nbsp;&nbsp;
							      		<input type="image" name="fSubmit_remove" src="./images/edit_remove.png" value="<?php print _("Remove member"); ?>" title="<?php print _("Remove member"); ?>" />
									</td>
									<td>&nbsp;</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</form>
		</div>