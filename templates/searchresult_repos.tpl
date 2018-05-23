		<div id="editform">
			<h3><?php print _("Repository search result"); ?></h3>
			<p>&nbsp;</p>
			<form name="repo_list" method="post">
				<table id="repolisttable">
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
					   		outputRepos($tArray, $rightAllowed);
					   	?>
				   	</tbody>
				   	<tfoot>
				   		<tr>
					      <td colspan="5" class="hlpcenter">
					        <?php
					      		if( ($rightAllowed == "add") || 
					      		    ($rightAllowed == "edit") || 
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
					$("#repolisttable").ariaSorTable({
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