		<div id="editform">
			<form action="rep_show_user.php" name="show_user" method="post">
				<h3><?php print sprintf( _("Show user %s"), $tUser );?></h3>
				<p>&nbsp;</p>
				<table id="userdetails">
					<tr>
						<td class="greycell"><strong><?php print _("Username").": "; ?></strong></td>
						<td><?php print $tUsername;?></td>
						<td>&nbsp;&nbsp;</td>
						<td class="greycell"><strong><?php print _("Administrator").": "; ?></strong></td>
						<td><?php print $tAdministrator;?></td>
					</tr>
					<tr>
						<td class="greycell"><strong><?php print _("Name").": "; ?></strong></td>
						<td><?php print $tName;?></td>
						<td>&nbsp;&nbsp;</td>
						<td class="greycell"><strong><?php print _("Givenname").": "; ?></strong></td>
						<td><?php print $tGivenname;?></td>
					</tr>
					<tr>
						<td class="greycell"><strong><?php print _("Email address").": "; ?></strong></td>
						<td><?php print $tEmailAddress;?></td>
						<td>&nbsp;&nbsp;</td>
						<td class="greycell"><strong><?php print _("Locked").": "; ?></strong></td>
						<td><?php print $tLocked;?></td>
					</tr>
					<tr>
						<td class="greycell"><strong><?php print _("Password expires").": "; ?></strong></td>
						<td><?php print $tPasswordExpires;?></td>
						<td>&nbsp;&nbsp;</td>
						<td class="greycell"><strong><?php print _("Repository access right").": "; ?></strong></td>
						<td><?php print $tAccessRight;?></td>
					</tr>
					<tr>
						<td class="greycell"><strong><?php print _("Password last modified").": "; ?></strong></td>
						<td><?php print $tPasswordModified;?></td>
						<td>&nbsp;&nbsp;</td>
						<td class="greycell">&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>
				<p>&nbsp;</p>
				<h3><?php print _("Group membership");?></h3>
				<p>&nbsp;</p>
				<table id="showusergrouptable">
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
				<table id="showuserprojecttable">
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
				<table id="showuserrighttable">
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
			</form>
			<script type="text/javascript">
					$("#showusergrouptable").ariaSorTable({
						rowsToShow: <?php print $CONF[PAGESIZE];?>,
						pager: true,
						textPager: '<?php print _("Page").":"; ?>',
						onInit: function(){	}
					});
					
					$("#showuserprojecttable").ariaSorTable({
						rowsToShow: <?php print $CONF[PAGESIZE];?>,
						pager: true,
						textPager: '<?php print _("Page").":"; ?>',
						onInit: function(){	}
					});
					
					$("#showuserrighttable").ariaSorTable({
						rowsToShow: <?php print $CONF[PAGESIZE];?>,
						pager: true,
						textPager: '<?php print _("Page").":"; ?>',
						onInit: function(){	}
					});
					
					$("#editform *").tooltip({
						showURL: false
					});
			</script>
		</div>