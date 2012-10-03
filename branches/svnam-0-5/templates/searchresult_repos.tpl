		<div id="edit_form">
			<h3><?php print _("Repository search result"); ?></h3>
			<p>&nbsp;</p>
			<form name="repo_list" method="post">
				<table id="repolist_table">
				   	<thead>
				   		<tr>
					   		<th>
					   			<?php print _("Repository name"); ?>
					   		</th>
					   		<th>
					   			<?php print _("Repository path"); ?>
					   		</th>
					   		<th>
					   			<?php print _("User"); ?>
					   		</th>
					   		<th>
					   			<?php print _("Password"); ?>
					   		</th>
					   		<th>
					   			<?php print _("Action"); ?>
					   		</th>
					   	</tr>
				   	</thead>
				   	<tbody>
				   		<?php
					   		foreach( $tArray as $entry ) {
					   		
					   			if( ($rightAllowed == "edit") or
					   			    ($rightAllowed == "delete" ) ) {
					   			    $url				= htmlentities("workOnRepo.php?id=".$entry['id']."&task=change");
					   			    $edit				= "<a href=\"$url\" title=\""._("Change")."\" alt=\""._("Change")."\"><img src=\"./images/edit.png\" border=\"0\" /></a>";
					   			} else {
					   				$edit				= "";
					   			}
					   			
					   			
					   			if( $rightAllowed == "delete" ) {
					   				$url				= htmlentities("deleteRepo.php?id=".$entry['id']."&task=delete");
					   				$delete				= "<a href=\"$url\" title=\""._("Delete")."\" alt=\""._("Delete")."\"><img src=\"./images/edittrash.png\" border=\"0\" /></a>";
					   			} else {
					   				$delete				= "";
					   			}
					   			$action					= $edit."     ".$delete;
					   			
					   			print "\t\t\t\t\t<tr>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['reponame']."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['repopath']."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['repouser']."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['repopassword']."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$action."</td>\n";
					   			print "\t\t\t\t\t</tr>\n";
					   		}
					   	?>
				   	</tbody>
				   	<tfoot>
				   		<tr>
					      <td colspan="5" class="hlp_center">
					        <?php
					      		if( ($rightAllowed == "add") or
					      		    ($rightAllowed == "edit") or
					      		    ($rightAllowed == "delete") ) {
					      		    
					      			print "<input type=\"image\" name=\"fSubmit_new\" src=\"./images/edit_add.png\" value=\""._("New repository")."\"  title=\""._("New repository")."\" />     ";
					      		}
					      	?>
					      	
					      	<input type="image" name="fSubmit_back" src="./images/button_cancel.png" value="<?php print _("Back"); ?>" title="<?php print _("Back"); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					      </td>
					   	</tr>
					   	<tr>
					      <td colspan="5" class="standout">
					      	<?php print $tMessage; ?>
					      </td>
					   	</tr>
				   	</tfoot>
				</table>
			</form>
			<script>
					$("#repolist_table").ariaSorTable({
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