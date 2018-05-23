		<div id="editform">
			<h3><?php print _("Search group administrators result"); ?></h3>
			<p>&nbsp;</p>
			<form name="group_admin_list" method="post">

				<table id="groupadminlisttable">
					<thead>
						<tr>
					   		<th>
					   			<?php print _("Group name"); ?>
					   		</th>
					   		<th>
					   			<?php print _("Group description"); ?>
					   		</th>
					   		<th>
					   			<?php print _("Administrator"); ?>
					   		</th>
					   		<th>
					   			<?php print _("Right"); ?>
					   		</th>
					   		<th>
					   			<?php print _("Action"); ?>
					   		</th>
					   	</tr>
					</thead>
					<tbody>
						<?php
					   		outputGroupAdmin($tArray, $rightAllowed);
					   	?>
					</tbody>
					<tfoot>
						<tr>
					      <td colspan="3" class="hlpcenter">
					        <?php
					      		if( ($rightAllowed == "add") || 
					      		    ($rightAllowed == "edit") || 
					      		    ($rightAllowed == "delete") ) {
					      		    
					      			print "<input type=\"image\" name=\"fSubmit_new\" src=\"./images/add_group.png\" value=\""._("New group")."\"  title=\""._("New group")."\" />     ";
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
					$("#groupadminlisttable").ariaSorTable({
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