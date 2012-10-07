		<div id="edit_form">
			<form action="rep_show_user.php" name="show_user" method="post">
				<h3><?php print sprintf( _("Show user %s"), $tUser );?></h3>
				<p>&nbsp;</p>
				<table id="userdetails">
					<tr>
						<td><strong><?php print _("Username").": "; ?></strong></td>
						<td><?php print $tUsername;?></td>
						<td>&nbsp;&nbsp;</td>
						<td><strong><?php print _("Administrator").": "; ?></strong></td>
						<td><?php print $tAdministrator;?></td>
					</tr>
					<tr>
						<td><strong><?php print _("Name").": "; ?></strong></td>
						<td><?php print $tName;?></td>
						<td>&nbsp;&nbsp;</td>
						<td><strong><?php print _("Givenname").": "; ?></strong></td>
						<td><?php print $tGivenname;?></td>
					</tr>
					<tr>
						<td><strong><?php print _("Email address").": "; ?></strong></td>
						<td><?php print $tEmailAddress;?></td>
						<td>&nbsp;&nbsp;</td>
						<td><strong><?php print _("Locked").": "; ?></strong></td>
						<td><?php print $tLocked;?></td>
					</tr>
					<tr>
						<td><strong><?php print _("Password expires").": "; ?></strong></td>
						<td><?php print $tPasswordExpires;?></td>
						<td>&nbsp;&nbsp;</td>
						<td><strong><?php print _("Repository access right").": "; ?></strong></td>
						<td><?php print $tAccessRight;?></td>
					</tr>
				</table>
				<p>&nbsp;</p>
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
							<th><?php print _(" SVN Module");?></th>
							<th><?php print _("Reporitory");?></th>
							<th><?php print _("Path");?></th>
							<th><?php print _("Module path");?></th>
							<th><?php print _("Access right");?></th>
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
								print "\t\t\t</tr>\n";
							}
						?>
					</tbody>
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