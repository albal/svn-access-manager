		<div id="editform">
			<h3><?php print _("Search group result"); ?></h3>
			<p>&nbsp;</p>
			<form name="group_list" method="post">
				<table id="grouplisttable">
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
					   		outputGroups($tArray, $tGroupsAllowed, $rightAllowed);
					   	?>
				   	</tbody>
				   	<tfoot>
				   		<tr>
				   			<td colspan="3">
				   				<?php
						      		if( ($rightAllowed == "add") || 
						      		    ($rightAllowed == "edit") || 
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
					$("#grouplisttable").ariaSorTable({
						rowsToShow: <?php print $CONF['page_size'];?>,
						pager: true,
						textPager: '<?php print _("Page").":"; ?>',
						onInit: function(){	}
					});
					
					$("#editform *").tooltip({
						showURL: false
					});
			</script>
		</div>