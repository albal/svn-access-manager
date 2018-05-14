		<div id="editform">
			<h3><?php print _("Access rights administration search result"); ?></h3>
			<p>&nbsp;</p>
			<form name="list_access_rights" method="post">
				
				<table id="accessrightlisttable">
				   	<thead>
				   			<tr>
					   		<th align="center">
					   			<strong><?php print _("M"); ?></strong>
					   		</th>
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
					   		<th>
					   			<strong><?php print _("Action"); ?></strong>
					   		</th>
					   	</tr>				   	</thead>
				   	<tbody>
				   		<?php
					   		outputAccessRights($tArray, $rightAllowed);
					   	?>
				   	</tbody>
				   	<tfoot>
				   		<tr>
					      <td colspan="9" class="hlpcenter">
					        <?php
					      		if( ($rightAllowed == "add") || 
					      		    ($rightAllowed == "edit") || 
					      		    ($rightAllowed == "delete") ||  
					      		    ($_SESSION[SVNSESSID]['admin'] == "p") ) {
					      		    
					      			print "<input type=\"image\" name=\"fSubmit_new\" src=\"./images/edit_add.png\" value=\""._("New access right")."\"  title=\""._("New access right")."\" />     ";
					      		}
					      	?>
					      	
					      	<input type="image" name="fSubmit_back" src="./images/button_cancel.png" value="<?php print _("Back"); ?>" title="<?php print _("Back"); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					      </td>
					   	</tr>
					   	<tr>
					      <td colspan="9" class="standout">
					      	<?php print $tMessage; ?>
					      </td>
					   	</tr>
				   	</tfoot>
				</table>
			</form>
			<script>
					$("#accessrightlisttable").ariaSorTable({
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