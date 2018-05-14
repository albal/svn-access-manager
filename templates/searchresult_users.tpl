		<div id="editform">
			<form name="user_list" method="post">
				<h3><?php print _("Search user result"); ?></h3>
				<p>&nbsp;</p>
				<table id="userlisttable">
				   	<thead>
				   		<tr >
					   		<th class="ui-table-default">
					   			<?php print _("UserId"); ?>
					   		</th>
					   		<th class="ui-table-default">
					   			<?php print _("Name"); ?>
					   		</th>
					   		<th class="ui-table-default">
					   			<?php print _("Given name"); ?>
					   		</th>
					   		<th class="ui-table-default">
					   			<?php print _("Email"); ?>
					   		</th>
                                <?php
                                        outputCustomFields();

                                        if( (isset($CONF['use_ldap'])) && (strtoupper($CONF['use_ldap']) == "YES") ) {
                                                print "\t\t\t\t\t\t<th class=\"ui-state-default\">\n";
                                                print "\t\t\t\t\t\t\t"._("LDAP User");
                                                print "\t\t\t\t\t\t</th>\n";
                                        }
                                ?>
					   		<th class="ui-table-default">
					   			<?php print _("Right"); ?>
					   		</th>
					   		<th class="ui-table-default">
					   			<?php print _("Locked"); ?>
					   		</th>
					   		<th class="ui-table-default">
					   			<?php print _("Password changed"); ?>
					   		</th>
					   		<th class="ui-table-default">
					   			<?php print _("Password expires"); ?>
					   		</th>
					   		<th class="ui-table-default">
					   			<?php print _("Administrator"); ?>
					   		</th>
					   		<th class="ui-table-deactivate">
					   			<?php print _("Action"); ?>
					   		</th>
					   	</tr>
				   	</thead>
				   	<tbody>
				   		<?php
					   		outputUsers($tArray, $rightAllowed);
					   	?>
				   	</tbody>
					<tfoot>
						<tr>
					      <td colspan="9">&nbsp;</td>
					   	</tr>
					   	<tr>
					      <td colspan="9" class="hlpcenter">
					      	<?php
					      		if( ($rightAllowed == "add") || 
					      		    ($rightAllowed == "edit") || 
					      		    ($rightAllowed == "delete") ) {
					      		
					      			print "<input type=\"image\" name=\"fSubmit_new\" src=\"./images/add_user.png\" value=\""._("New user")."\"  title=\""._("New user")."\" />     ";
					      		}
					      	?>
					      	
					      	<input type="image" name="fSubmit_back" src="./images/button_cancel.png" value="<?php print _("Back"); ?>" title="<?php print _("Back"); ?>" />
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
			<script type="text/javascript">
					$("#userlisttable").ariaSorTable({
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
