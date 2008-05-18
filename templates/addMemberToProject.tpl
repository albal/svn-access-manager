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
							<select name="membersadd[]" multiple="" size="15" style="width: 100%; height=200px;">
							<?php
								foreach($tUsers as $uid => $name) {
									
									print "\t\t\t\t\t\t\t<option value=\"$uid\" label=\"$name\">$name ($uid)</option>\n";
									
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
							<input class="button" type="submit" name="fSubmitAdd" value="<?php print _("Add"); ?>" />&nbsp;&nbsp;&nbsp;
							<input class="button" type="submit" name="fSubmitAdd" value="<?php print _("Cancel"); ?>" />
						</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2"><?php print $tMessage; ?></td>
					</tr>
				</table>
			</form>
		</div>