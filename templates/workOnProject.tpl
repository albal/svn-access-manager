		<div id="edit_form">
			<form name="workOnProject" method="post">
				<table>
					<tr valign="top">
						<td>
							<table>
							   	<tr>
							      <td colspan="5"><h3><?php print _("Project administration"); ?></h3></td>
							   	</tr>
							   	<tr>
							      <td colspan="5">&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td width="150"><strong><?php print _("Suvbersion project").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fProject" value="<?php print $tProject; ?>" size="30" maxsize="255" <?php print $tReadonly; ?> />
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td><strong><?php print _("Subversion module path").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fModulepath" value="<?php print $tModulepath; ?>" size="30" maxsize="255"  />
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td nowrap><strong><?php print _("Repository").": "; ?></strong></td>
							   		<td>
							   			<select name="fRepo">
							   				<?php
							   					foreach( $tRepos as $repoId => $repoName ) {
							   					
							   						if( $tRepo == $repoId ) {
							   						
							   							print "\t\t\t\t\t\t\t\t<option value='".$repoId."' selected>".$repoName."</option>\n";
							   							
							   						} else {
							   						
							   							print "\t\t\t\t\t\t\t\t<option value='".$repoId."'>".$repoName."</option>\n";
							   							
							   						}
							   					}
							   				?>
							   			</select>
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
							</table>
						</td>
						<td width="20">
							&nbsp;
						</td>
						<td>
							<table>
								<tr>
									<td>
										<strong><?php print _("Select project responsible users"); ?></strong>
									</td>
								</tr>
								<tr>
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
								</tr>
								<tr>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>
										<input class="button" type="submit" name="fSubmit" value="<?php print _("Add responsible"); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							      		<input class="button" type="submit" name="fSubmit" value="<?php print _("Remove responsible"); ?>" />
									</td>
								</tr>
							</table>
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