		<div id="edit_form">
			<form name="user_list" method="post">
				<h3><?php print _("Search user result"); ?></h3>
				<p>&nbsp;</p>
				<table id="userlist_table">
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
                                        if (isset($CONF['column_custom1'])) {
                                                print "\t\t\t\t\t\t<th class=\"ui-table-default\">\n";
                                                print "\t\t\t\t\t\t\t"._($CONF['column_custom1']);
                                                print "\t\t\t\t\t\t</th>\n";
                                        }
                                        if (isset($CONF['column_custom2'])) {
                                                print "\t\t\t\t\t\t<th class=\"ui-table-default\">\n";
                                                print "\t\t\t\t\t\t\t"._($CONF['column_custom2']);
                                                print "\t\t\t\t\t\t</th>\n";
                                        }
                                        if (isset($CONF['column_custom3'])) {
                                                print "\t\t\t\t\t\t<th class=\"ui-table-default\">\n";
                                                print "\t\t\t\t\t\t\t"._($CONF['column_custom3']);
                                                print "\t\t\t\t\t\t</th>\n";
                                        }

                                        if( (isset($CONF['use_ldap'])) and (strtoupper($CONF['use_ldap']) == "YES") ) {
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
					   		foreach( $tArray as $entry ) {
					   		
					   			list($date, $time)		= splitDateTime( $entry['password_modified'] );
					   			$pwChanged 				= $date." ".$time; 
					   			$locked					= $entry['locked'] == 0 			? _("no") : _("yes");
					   			$expires				= $entry['passwordexpires'] == 0 	? _("no") : _("yes");
					   			$admin					= $entry['admin'] == "n" 			? _("no") : _("yes");
					   			$custom1                = $entry['custom1'];
								$custom2                = $entry['custom2'];
								$custom3                = $entry['custom3'];

					   			if( ($rightAllowed == "edit") or
					   			    ($rightAllowed == "delete" ) ) {
					   			    $url				= htmlentities("workOnUser.php?id=".$entry['id']."&task=change");
					   			    $edit				= "<a href=\"$url\" title=\""._("Change")."\" alt=\""._("Change")."\"><img src=\"./images/edit.png\" border=\"0\" /></a>";
					   			} else {
					   				$edit				= "";
					   			}
					   			
					   			if( $rightAllowed == "delete" ) {
					   				$url				= htmlentities("deleteUser.php?id=".$entry['id']."&task=delete");
					   				$delete				= "<a href=\"$url\" title=\""._("Delete")."\" alt=\""._("Delete")."\"><img src=\"./images/edittrash.png\" border=\"0\" /></a>";
					   			} else {
					   				$delete				= "";
					   			}
					   			$action					= $edit."     ".$delete;
					   			
					   			print "\t\t\t\t\t<tr>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['userid']."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['name']."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['givenname']."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['emailaddress']."</td>\n";
                                if (isset($CONF['column_custom1'])) print "\t\t\t\t\t\t<td>".$custom1."</td>\n";
                                if (isset($CONF['column_custom2'])) print "\t\t\t\t\t\t<td>".$custom2."</td>\n";
                                if (isset($CONF['column_custom3'])) print "\t\t\t\t\t\t<td>".$custom3."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['user_mode']."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$locked."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$pwChanged."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$expires."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$admin."</td>\n";
					   			if( (isset($CONF['use_ldap'])) and (strtoupper($CONF['use_ldap']) == "YES") ) {
					   				if( isset( $entry['ldap'] ) ) {
					   					$ldap			= ($entry['ldap'] == 1) ? _("yes") : _("no");
					   				} else {
					   					$ldap			= _("No");
					   				}
					   				print "\t\t\t\t\t\t<td>".$ldap."</td>\n";
					   			}
					   			print "\t\t\t\t\t\t<td>".$action."</td>\n";
					   			print "\t\t\t\t\t</tr>\n";
					   		}
					   	?>
				   	</tbody>
					<tfoot>
						<tr>
					      <td colspan="9">&nbsp;</td>
					   	</tr>
					   	<tr>
					      <td colspan="9" class="hlp_center">
					      	<?php
					      		if( ($rightAllowed == "add") or
					      		    ($rightAllowed == "edit") or
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
					$("#userlist_table").ariaSorTable({
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
