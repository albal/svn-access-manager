		<div id="editform">
			<form action="workOnProject.php" method="post">
				<table>
					<tr>
						<td align="center" colspan="2"><h2><?php print _("Add group members"); ?></h2></td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr valign="top">
						<td align="left"><?php print _("Choose the members to add"); ?></td>
						<td>&nbsp;</td>
					</tr>
					<tr valign="top">
						<td>
							<select name="groupsadd[]" multiple="" size="15" style="width: 100%; height=200px;" class="chzn-select" title="<?php print _("Select the groups to add.");?>">
							<?php
								foreach($tGroups as $id => $name) {
									
									print "\t\t\t\t\t\t\t<option value=\"$id\" label=\"$name\">$name</option>\n";
									
								}   
							?>
							</select>
						</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr valign="top">
						<td nowrap>
							<input type="image" name="fSubmitAddGroup_ok" src="./images/ok.png" value="<?php print _("Add"); ?>"  title="<?php print _("Add"); ?>" />&nbsp;&nbsp;&nbsp;
				      		<input type="image" name="fSubmitAddGroup_back" src="./images/button_cancel.png" value="<?php print _("Cancel"); ?>" title="<?php print _("Cancel"); ?>" />
						</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2"><?php print $tMessage; ?></td>
					</tr>
				</table>
			</form>
		</div>