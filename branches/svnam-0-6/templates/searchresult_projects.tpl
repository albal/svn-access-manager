		<div id="editform">
			<h3><?php print _("Project search result"); ?></h3>
			<p>&nbsp;</p>
			<form name="project_list" method="post">
				<table id="projectlisttable">
				   	<thead>
				   		<tr>
					   		<th>
					   			<?php print _("Subversion project"); ?>
					   		</th>
					   		<th>
					   			<?php print _("Subversion module path"); ?>
					   		</th>
					   		<th>
					   			<?php print _("Repository"); ?>
					   		</th>
					   		<th>
					   			<?php print _("Action"); ?>
					   		</th>
					   	</tr>
				   	</thead>
				   	<tbody>
				   		<?php
					   		outputProjects($tArray, $rightAllowed);
					   	?>
				   	</tbody>
				   	<tfoot>
				   		<tr>
					      <td colspan="4" class="hlpcenter">
					        <?php
					      		if( ($rightAllowed == "add") || 
					      		    ($rightAllowed == "edit") || 
					      		    ($rightAllowed == "delete") ) {
					      		    
					      			print "<input type=\"image\" name=\"fSubmit_new\" src=\"./images/add_project.png\" value=\""._("New project")."\"  title=\""._("New project")."\" />     ";
					      		}
					      	?>
					      	
					      	<input type="image" name="fSubmit_back" src="./images/button_cancel.png" value="<?php print _("Back"); ?>" title="<?php print _("Back"); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					      </td>
					   	</tr>
					   	<tr>
					      <td colspan="4" class="standout">
					      	<?php print $tMessage; ?>
					      </td>
					   	</tr>
				   	</tfoot>
				</table>
			</form>
			<script>
					$("#projectlisttable").ariaSorTable({
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