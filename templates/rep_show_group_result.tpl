		<div id="editform">
			<form action="rep_show_group.php" name="show_group" method="post">
				<h3><?php print sprintf( _("Show group %s"), $tGroupname );?></h3>
				<p>&nbsp;</p>
				<table id="groupdetails">
					<tr>
						<td class="greycell"><strong><?php print _("Group Name").": "; ?></strong></td>
						<td><?php print $tGroupname;?></td>
						<td>&nbsp;&nbsp;</td>
						<td class="greycell"><strong><?php print _("Description").": "; ?></strong></td>
						<td><?php print $tDescription;?></td>
					</tr>
				</table>
				<p>&nbsp;</p>
				<h3><?php print _("Users in group");?></h3>
				<p>&nbsp;</p>
				<table id="showgroupusertable">
					<thead>
						<tr>
							<th><?php print _("Username");?></th>
							<th><?php print _("Givenname");?></th>
							<th><?php print _("Name");?></th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach( $tUsers as $entry ) {
								print "\t\t\t<tr>\n";
								print "\t\t\t\t<td>".$entry['userid']."</td>\n";
								print "\t\t\t\t<td>".$entry['givenname']."</td>\n";
								print "\t\t\t\t<td>".$entry['name']."</td>\n";
								print "\t\t\t</tr>\n";
							}
						?>
					</tbody>
				</table>
				<p>&nbsp;</p>
				<h3><?php print _("Group administrators");?></h3>
				<p>&nbsp;</p>
				<table id="showgroupadmintable">
					<thead>
						<tr>
							<th><?php print _("Username");?></th>
							<th><?php print _("Givenname");?></th>
							<th><?php print _("Name");?></th>
							<th><?php print _("Access right");?></th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach( $tAdmins as $entry ) {
								print "\t\t\t<tr>\n";
								print "\t\t\t\t<td>".$entry['userid']."</td>\n";
								print "\t\t\t\t<td>".$entry['givenname']."</td>\n";
								print "\t\t\t\t<td>".$entry['name']."</td>\n";
								print "\t\t\t\t<td>".$entry['allowed']."</td>\n";
								print "\t\t\t</tr>\n";
							}
						?>
					</tbody>
				</table>
				<p>&nbsp;</p>
				<h3><?php print _("Access rights");?></h3>
				<p>&nbsp;</p>
				<table id="showgrouprighttable">
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
					$("#showgroupusertable").ariaSorTable({
						rowsToShow: <?php print $CONF[PAGESIZE];?>,
						pager: true,
						textPager: '<?php print _("Page").":"; ?>',
						onInit: function(){	}
					});
					
					$("#showgroupadmintable").ariaSorTable({
						rowsToShow: <?php print $CONF[PAGESIZE];?>,
						pager: true,
						textPager: '<?php print _("Page").":"; ?>',
						onInit: function(){	}
					});
					
					$("#showgrouprighttable").ariaSorTable({
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