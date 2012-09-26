		<div id="edit_form">
			<form name="setAccessRight" method="post">
				<table>
				   	<tr>
				      <td colspan="3"><h3><?php print _("Access right administration / Step 3: set access rights"); ?></h3></td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td nowrap><strong><?php print _("Project").": "; ?></strong></td>
				   		<td>
				   			<?php print $tProjectName; ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td nowrap><strong><?php print _("Subversion module path").": "; ?></strong></td>
				   		<td>
				   			<?php print $tModulePath; ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td nowrap><strong><?php print _("Selected directory").": "; ?></strong></td>
				   		<td colspan="2"><?php print $tPathSelected; ?>
				   		
				   	</tr>
				   	<tr>
				   		<td><strong><?php print _("Access right").": "; ?></strong></td>
				   		<td>
				   			<input type="radio" name="fAccessRight" value="none" <?php print $tNone; ?> />&nbsp;&nbsp;None&nbsp;&nbsp;&nbsp;
				   			<input type="radio" name="fAccessRight" value="read" <?php print $tRead; ?> />&nbsp;&nbsp;Read&nbsp;&nbsp;&nbsp;
				   			<input type="radio" name="fAccessRight" value="write" <?php print $tWrite; ?> />&nbsp;&nbsp;Write
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<!--
				   	<tr>
				   		<td><strong><?php print _("Recursive access").": "; ?></strong></td>
				   		<td>
				   			<input type="checkbox" name="fRecursive" value="1" <?php print $tRecursive; ?> />
				   		</td>
				   		<td><?php print _("If checked the access rights are valid for the selected directory itself and all directories below it."); ?></td>
				   	</tr>
				   	-->
				   	<tr>
				   		<td><strong><?php print _("Valid from").": "; ?></strong></td>
				   		<td>
				   			<input id="validFrom" type="text" name="fValidFrom" value="<?php print $tValidFrom; ?>" size="11" maxlength="10" />
				   		</td>
				   		<td><?php print _("Format MM.DD.YYYY"); ?></td>
				   	</tr>
				   	<tr>
				   		<td><strong><?php print _("Valid until").": "; ?></strong></td>
				   		<td>
				   			<input id="validUntil" type="text" name="fValidUntil" value="<?php print $tValidUntil; ?>" size="11" maxlength="10" />
				   		</td>
				   		<td><?php print _("Format MM.DD.YYYY"); ?> </td>
				   	</tr>
				   	<tr valign="top">
				   		<td><strong><?php print _("Allowed users").": "; ?></strong></td>
				   		<td>
							<select name="fUsers[]" multiple="" size="8" style="width: 100%; height=100px;" <?php print $tReadonly; ?> >
							<?php
								foreach($tUsers as $uid => $name) {
									
									if( $uid == $tUid ) {
										print "\t\t\t\t\t\t\t<option value=\"$uid\" label=\"$name ($uid)\" selected>$name ($uid)</option>\n";
									} else {
										print "\t\t\t\t\t\t\t<option value=\"$uid\" label=\"$name ($uid)\">$name ($uid)</option>\n";
									}
									
								}   
							?>
							</select>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr valign="top">
				   		<td><strong><?php print _("Allowed groups").": "; ?></strong></td>
				   		<td>
							<select name="fGroups[]" multiple="" size="8" style="width: 100%; height=100px;" <?php print $tReadonly; ?> >
							<?php
								foreach($tGroups as $gid => $name) {
									
									if( $gid == $tGid ) {
										print "\t\t\t\t\t\t\t<option value=\"$gid\" label=\"$name\" selected>$name</option>\n";
									} else {
										print "\t\t\t\t\t\t\t<option value=\"$gid\" label=\"$name\">$name</option>\n";
									}
									
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
			</form>
			<script>
					var year     = "<?php print date('Y');?>";
					var month    = "<?php print date('m') - 1;?>";
					var day      = "<?php print date('d');?>";
					
					$( "#validUntil" ).datepicker({
						maxDate: new Date(year, month, day),
						regional: ['<?php print $tLocale;?>'],
						altFormat: ['<?php print $tDateFormat;?>'],
					});
					
					$( "#validFrom" ).datepicker({
						maxDate: new Date(year, month, day),
						regional: ['<?php print $tLocale;?>'],
						altFormat: ['<?php print $tDateFormat;?>'],
					});
			</script>
		</div>