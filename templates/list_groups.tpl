		<div id="edit_form">
			<h3><?php print _("Group administration"); ?></h3>
			<p>&nbsp;</p>
			<form name="group_list" method="post">
				<table id="grouplist_table">
				   	<thead>
				   		<tr>
					   		<th>
					   			<?php print _("Group name"); ?>
					   		</th>
					   		<th>
					   			<?php print _("Group description"); ?>
					   		</th>
					   		<th>
					   			<?php print _("Action"); ?>
					   		</th>
					   	</tr>
				   	</thead>
				   	<tbody>
				   		<?php
					   		foreach( $tGroups as $entry ) {
					   		
					   			$groupRight				=  isset($tGroupsAllowed[$entry['id']]) ? $tGroupsAllowed[$entry['id']] : "none";
					   			
					   			if( ($rightAllowed == "edit") or
					   			    ($rightAllowed == "delete") or
					   			    ($groupRight == "edit") or
					   			    ($groupRight == "delete") 
					   			   ) {
					   			   	$url				= htmlentities("workOnGroup.php?id=".$entry['id']."&task=change");
					   			    $edit				= "<a href=\"$url\" title=\""._("Change")."\" alt=\""._("Change")."\"><img src=\"./images/edit.png\" border=\"0\" /></a>";
					   			} else {
					   				$edit				= "";
					   			}
					   			
					   			
					   			if( ($rightAllowed == "delete") or ($groupRight == "delete") ) {
					   				$url				= htmlentities("deleteGroup.php?id=".$entry['id']."&task=delete");
					   				$delete				= "<a href=\"$url\" title=\""._("delete")."\" alt=\""._("Delete")."\"><img src=\"./images/edittrash.png\" border=\"0\" /></a>";
					   			} else {
					   				$delete				= "";
					   			}
					   			$action					= $edit."     ".$delete;
					   			
					   			print "\t\t\t\t\t<tr>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['groupname']."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['description']."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$action."</td>\n";
					   			print "\t\t\t\t\t</tr>\n";
					   		}
					   	?>
				   	</tbody>
				   	<tfoot>
				   		<tr>
				   			<td colspan="3">
				   				<?php
						      		if( ($rightAllowed == "add") or
						      		    ($rightAllowed == "edit") or
						      		    ($rightAllowed == "delete") ) {
						      		    
						      			print "<input type=\"image\" name=\"fSubmit_new\" src=\"./images/add_group.png\" value=\""._("New group")."\"  title=\""._("New group")."\" />     ";
						      		}
						      	?>
						      	
						      	<input type="image" name="fSubmit_back" src="./images/button_cancel.png" value="<?php print _("Back"); ?>" title="<?php print _("Back"); ?>" />
				   			</td>
				   		</tr>
				   		<tr>
					      <td colspan="3" class="standout">
					      	<?php print $tMessage; ?>
					      </td>
					   	</tr>
				   	</tfoot>
				</table>
			</form>
			<script>
					$("#grouplist_table").ariaSorTable({
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