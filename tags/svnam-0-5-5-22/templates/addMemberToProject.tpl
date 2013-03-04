		<div id="edit_form">
			<form action="workOnProject.php" method="post">
				<table>
					<tr>
						<td align="center" colspan="2"><h2><?php print _("Add project responsibles"); ?></h2></td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr valign="top">
						<td align="left"><?php print _("Choose the responsibles to add"); ?></td>
						<td>&nbsp;</td>
					</tr>
					<tr valign="top">
						<td>
							<select id="members" name="membersadd[]" multiple="" size="15" style="width: 100%; height=200px;" class="chzn-select" title="<?php print _("Select the users to add.");?>">
							<?php
								foreach($tUsers as $uid => $name) {
									
									print "\t\t\t\t\t\t\t<option value=\"$uid\" label=\"$name ($uid)\">$name ($uid)</option>\n";
									
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
							<input type="image" name="fSubmitAdd_ok" src="./images/ok.png" value="<?php print _("Add"); ?>"  title="<?php print _("Add"); ?>" />&nbsp;&nbsp;&nbsp;
				      		<input type="image" name="fSubmitAdd_back" src="./images/button_cancel.png" value="<?php print _("Cancel"); ?>" title="<?php print _("Cancel"); ?>" />
						</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2"><?php print $tMessage; ?></td>
					</tr>
				</table>
			</form>
			<script>
				$("#members").chosen({no_results_text: "No results matched"});
			</script>
		</div>