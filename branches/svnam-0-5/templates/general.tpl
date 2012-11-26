		<div id="edit_form">
			<form name="general" method="post">
				<table>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td  width="130">
				   			<strong><?php print _("Username").": "; ?></strong>
				   		</td>
				   		<td>
				   			<input type="text" name="fUserid" value="<?php print $tUserid; ?>" readonly />
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td>
				   			<strong><?php print _("Given name").": "; ?></strong>
				   		</td>
				   		<td>
				   			<input type="text" name="fGivenname" value="<?php print $tGivenname; ?>" title="<?php print _("Enter the given name of the user.");?>" />
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td>
				   			<strong><?php print _("Name").": "; ?></strong>
				   		</td>
				   		<td>
				   			<input type="text" name="fName" value="<?php print $tName; ?>" title="<?php print _("Enter the name of the user.");?>"/>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td>
				   			<strong><?php print _("Email").": "; ?></strong>
				   		</td>
				   		<td>
				   			<input type="text" name="fEmail" value="<?php print $tEmail; ?>" size="40" title="<?php print _("Enter the email address of the user. Please fill in a valid email address. Otherwise the user will not be able to receive notifications.");?>"/>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<?php
				   		if (isset($CONF['column_custom1'])) {
				   			print "\t\t\t<tr>\n";
				   			print "\t\t\t\t<td><strong>".$CONF['column_custom1'].": </strong>\n";
				   			print "\t\t\t\t<td><input type='text' name='fCustom1' value='$tCustom1' size='40'/></td>\n";
				   			print "\t\t\t\t<td> </td>\n";
				   			print "\t\t\t</tr>\n";
				   		}
				   		if (isset($CONF['column_custom2'])) {
				   			print "\t\t\t<tr>\n";
				   			print "\t\t\t\t<td><strong>".$CONF['column_custom2'].": </strong>\n";
				   			print "\t\t\t\t<td><input type='text' name='fCustom2' value='$tCustom2' size='40'/></td>\n";
				   			print "\t\t\t\t<td> </td>\n";
				   			print "\t\t\t</tr>\n";
				   		}
				   		if (isset($CONF['column_custom3'])) {
				   			print "\t\t\t<tr>\n";
				   			print "\t\t\t\t<td><strong>".$CONF['column_custom3'].": </strong>\n";
				   			print "\t\t\t\t<td><input type='text' name='fCustom3' value='$tCustom3' size='40'/></td>\n";
				   			print "\t\t\t\t<td> </td>\n";
				   			print "\t\t\t</tr>\n";
				   		}
				   	?>
				   	<tr>
				   		<td>
				   			<strong><?php print _("Security question").": "; ?></strong>
				   		</td>
				   		<td>
				   			<input type="text" name="fSecurityQuestion" value="<?php print $tSecurityQuestion;?>" size="40" maxsize="255" title="<?php print _("Question to answer before a password reset."); ?>"/> 
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td>
				   			<strong><?php print _("Security question answer").": "; ?></strong>
				   		</td>
				   		<td>
				   			<input type="text" name="fAnswer" value="<?php print $tAnswer; ?>" size="40" maxsize="255" title="<?php print _("Answer to the security question. The answer is case sensitive must be given exactly as written here.");?>"/>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td nowrap>
				   			<strong><?php print _("Password modified").": "; ?></strong>
				   		</td>
				   		<td>
				   			<input type="text" name="fpwModified" value="<?php print $tPwModified; ?>" readonly />
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td>
				   			<strong><?php print _("Password expires").": "; ?></strong>
				   		</td>
				   		<td>
				   			<input type="text" name="fPasswordExpires" value="<?php print $tPasswordExpires; ?>" readonly />
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td>
				   			<strong><?php print _("Locked").": "; ?></strong>
				   		</td>
				   		<td>
				   			<input type="text" name="fLocked" value="<?php print $tLocked; ?>" readonly />
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				      	<td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				      	<td colspan="3">
				      	
				      		<h3><?php print _("Group membership");?></h3>
							<p>&nbsp;</p>
							<table id="showusergroup_table">
								<thead>
									<tr>
										<th><?php print _("Group name");?></th>
										<th><?php print _("Description");?></th>
									</tr>
								</thead>
								<tbody>
									<?php
										foreach( $tGroups as $entry ) {
											print "\t\t\t<tr>\n";
											print "\t\t\t\t<td>".$entry['groupname']."</td>\n";
											print "\t\t\t\t<td>".$entry['description']."</td>\n";
											print "\t\t\t</tr>\n";
										}
									?>
								</tbody>
							</table>
							<p>&nbsp;</p>
				      		<h3><?php print _("Project responsible");?></h3>
							<p>&nbsp;</p>
							<table id="showuserproject_table">
								<thead>
									<tr>
										<th><?php print _("SVN Module");?></th>
										<th><?php print _("Repository name");?></th>
									</tr>
								</thead>
								<tbody>
									<?php
										foreach( $tProjects as $entry ) {
											print "\t\t\t<tr>\n";
											print "\t\t\t\t<td>".$entry['svnmodule']."</td>\n";
											print "\t\t\t\t<td>".$entry['reponame']."</td>\n";
											print "\t\t\t</tr>\n";
										}
									?>
								</tbody>
							</table>
							<p>&nbsp;</p>
							<h3><?php print _("Access rights");?></h3>
							<p>&nbsp;</p>
							<table id="showuserright_table">
								<thead>
									<tr>
										<th><?php print _("SVN Module");?></th>
										<th><?php print _("Reporitory");?></th>
										<th><?php print _("Path");?></th>
										<th><?php print _("Module path");?></th>
										<th><?php print _("Access right");?></th>
										<th><?php print _("Access by");?></th>
									</tr>
								</thead>
								<tbody>
									<?php
										foreach( $tAccessRights as $entry ) {
											print "\t\t\t<tr>\n";
											print "\t\t\t\t<td>".$entry['svnmodule']."</td>\n";
											print "\t\t\t\t<td>".$entry['reponame']."</td>\n";
											print "\t\t\t\t<td>".$entry['path']."</td>\n";
											print "\t\t\t\t<td>".$entry['modulepath']."</td>\n";
											print "\t\t\t\t<td>".$entry['access_right']."</td>\n";
											print "\t\t\t\t<td>".$entry['access_by']."</td>\n";
											print "\t\t\t</tr>\n";
										}
									?>
								</tbody>
							</table>
				      	
				      	</td>
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
				      	<td colspan="3" class="standout">
				      		<?php print $tMessage; ?>
				      	</td>
				   	</tr>
				</table>
			</form>
			<script type="text/javascript">
					$("#showusergroup_table").ariaSorTable({
						rowsToShow: <?php print $CONF['page_size'];?>,
						pager: true,
						textPager: '<?php print _("Page").":"; ?>',
						onInit: function(){	}
					});
					
					$("#showuserproject_table").ariaSorTable({
						rowsToShow: <?php print $CONF['page_size'];?>,
						pager: true,
						textPager: '<?php print _("Page").":"; ?>',
						onInit: function(){	}
					});
					
					$("#showuserright_table").ariaSorTable({
						rowsToShow: <?php print $CONF['page_size'];?>,
						pager: true,
						textPager: '<?php print _("Page").":"; ?>',
						onInit: function(){	}
					});
					
					$("#edit_form *").tooltip({
						showURL: false
					});
			</script>
		</div>