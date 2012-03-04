		<div id="edit_form">
			<h3><?php print sprintf( _("List of access rights for %s"), $_SESSION['svn_sessid']['date'] ); ?></h3>
			<p>&nbsp;</p>
			<form action="rep_access_rights.php" name="rep_access_rights" method="post">
				<table id="accessrep_table">
				   	<thead>
				   		<tr>
					   		<th>
					   			<strong><?php print _("Project"); ?></strong>
					   		</th>
					   		<th>
					   			<strong><?php print _("Rights"); ?></strong>
					   		</th>
					   		<th align="center">
					   			<strong><?php print _("User"); ?></strong>
					   		</th>
					   		<th align="center">
					   			<strong><?php print _("Group"); ?></strong>
					   		</th>
					   		<th align="center">
					   			<strong><?php print _("Valid from"); ?></strong>
					   		</th>
					   		<th align="center">
					   			<strong><?php print _("Valid until"); ?></strong>
					   		</th>
					   		<th>
					   			<strong><?php print _("Repository:Directory"); ?></strong>
					   		</th>
					   	</tr>
				   	</thead>
				   	<tbody>
				   		<?php
					   		$i 										= 0;
					   		$_SESSION['svn_sessid']['max_mark']		= 0;
					   		$_SESSUIN['svn_sessid']['mark']			= array();
					   		
					   		foreach( $tAccessRights as $entry ) {
					   		
					   			$id						= $entry['id'];
					 
					   			$validfrom				= splitValidDate( $entry['valid_from'] );
					   			$validuntil				= splitValiddate( $entry['valid_until'] );
					   			$field					= "fDelete".$i;
					   			
					   			print "\t\t\t\t\t<tr valign=\"top\">\n";
					   			print "\t\t\t\t\t\t<td>".$entry['svnmodule']."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['access_right']."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['username']."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['groupname']."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$validfrom."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$validuntil."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['reponame'].":".$entry['path']."</td>\n";
					   			print "\t\t\t\t\t</tr>\n";
					   			
					   			$_SESSION['svn_sessid']['mark'][$i]		= $entry['id'];
					   			
					   			$i++;
					   		}
					   		
					   		$_SESSION['svn_sessid']['max_mark'] = $i - 1;
					   	?>
				   	</tbody>
				   	<tfoot>
				   		<tr>
					      <td colspan="7" class="standout">
					      	<?php print $tMessage; ?>
					      </td>
					   	</tr>
				   	</tfoot>
				</table>
			</form>
			<script>
					$("#accessrep_table").ariaSorTable({
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