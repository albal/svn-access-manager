		<div id="edit_form">
			<h3><?php print _("Access rights administration"); ?></h3>
			<p>&nbsp;</p>
			<form id="form_access_list" name="list_access_rights" method="post">
				
				<table id="accessrightlist_table">
				   	<thead>
				   			<tr>
					   		<th align="center" class="ui-table-default">
					   			<strong><?php print _("M"); ?></strong>
					   		</th>
					   		<th class="ui-table-default">
					   			<strong><?php print _("Project"); ?></strong>
					   		</th>
					   		<th class="ui-table-default">
					   			<strong><?php print _("Rights"); ?></strong>
					   		</th>
					   		<th align="center" class="ui-table-default">
					   			<strong><?php print _("User"); ?></strong>
					   		</th>
					   		<th align="center" class="ui-table-default">
					   			<strong><?php print _("Group"); ?></strong>
					   		</th>
					   		<th align="center" class="ui-table-default">
					   			<strong><?php print _("Valid from"); ?></strong>
					   		</th>
					   		<th align="center" class="ui-table-default">
					   			<strong><?php print _("Valid until"); ?></strong>
					   		</th>
					   		<th class="ui-table-default">
					   			<strong><?php print _("Repository:Directory"); ?></strong>
					   		</th>
					   		<th class="ui-table-deactivate">
					   			<strong><?php print _("Action"); ?></strong>
					   		</th>
					   	</tr>	
					   	<tr class="ui-table-deactivate">
					   		<td class="ui-table-deactivate">
					   			<strong><?php print _("Filter:"); ?></strong>
					   		</td>
					   		<td class="ui-table-deactivate">
					   			<input id="filterproject" class="large" type="text" name="fSearchProject" value="<?php print $tSearchProject;?>" title="<?php print _("Search access rights by project.");?>" />
					   		</td>
					   		<td class="ui-table-deactivate">
					   			&nbsp;
					   		</td>
					   		<td class="ui-table-deactivate">
					   			<input id="filteruser" class="large" type="text" name="fSearchUser" value="<?php print $tSearchUser;?>" title="<?php print _("Search access rights by user.");?>" />
					   		</td>
					   		<td class="ui-table-deactivate">
					   			<input id="filtergroup" class="large" type="text" name="fSearchGroup" value="<?php print $tSearchGroup;?>" title="<?php print _("Search access rights by group.");?>" />
					   		</td>
					   		<td class="ui-table-deactivate">
					   			&nbsp;
					   		</td>
					   		<td class="ui-table-deactivate">
					   			&nbsp;
					   		</td>
					   		<td class="ui-table-deactivate">
					   			&nbsp;
					   		</td>
					   		<td class="ui-table-deactivate">
					   			&nbsp;
					   		</td>
					   	</tr>	
				   	</thead>
				   	<tbody id="tbody">
				   		<?php
					   		$i 										= 0;
					   		$_SESSION['svn_sessid']['max_mark']		= 0;
					   		$_SESSUIN['svn_sessid']['mark']			= array();
					   		
					   		foreach( $tAccessRights as $entry ) {
					   		
					   			$id						= $entry['id'];
					   			$validfrom				= splitValidDate( $entry['valid_from'] );
					   			$validuntil				= splitValiddate( $entry['valid_until'] );
					   			$field					= "fDelete".$i;
					   			$action					= "";
					   			
					   			if( $rightAllowed == "edit" ) {
					   				$url					= htmlentities("workOnAccessRight.php?id=".$entry['id']."&task=change");
					   				$action					= "<a href=\"$url\" title=\""._("Change")."\" alt=\""._("Change")."\"><img src=\"./images/edit.png\" border=\"0\" /></a>";
					   			} elseif( $rightAllowed == "delete" ) {
					   				$url					= htmlentities("workOnAccessRight.php?id=".$entry['id']."&task=change");
					   				$action					= "<a href=\"$url\" title=\""._("Change")."\" alt=\""._("Change")."\"><img src=\"./images/edit.png\" border=\"0\" /></a>     <a href=\"deleteAccessRight.php?id=".htmlentities($entry['id'])."&task=delete\" title=\""._("Delete")."\" alt=\""._("Delete")."\"><img src=\"./images/edittrash.png\" border=\"0\" /></a>";
					   			} elseif( $_SESSION['svn_sessid']['admin'] == "p" ) {
					   				$url					= htmlentities("workOnAccessRight.php?id=".$entry['id']."&task=change");
					   				$action					= "<a href=\"workOnAccessRight.php?id=".$entry['id']."&task=change\" title=\""._("Change")."\" alt=\""._("Change")."\"><img src=\"./images/edit.png\" border=\"0\" /></a>";
					   				$action					= "<a href=\"$url\" title=\""._("Change")."\" alt=\""._("Change")."\"><img src=\"./images/edit.png\" border=\"0\" /></a>     <a href=\"deleteAccessRight.php?id=".htmlentities($entry['id'])."&task=delete\" title=\""._("Delete")."\" alt=\""._("Delete")."\"><img src=\"./images/edittrash.png\" border=\"0\" /></a>";
					   			}
					   			
					   			print "\t\t\t\t\t<tr valign=\"top\">\n";
					   			print "\t\t\t\t\t\t<td><input type=\"checkbox\" name=\"".$field."\" value=\"".$entry['id']."\"/></td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['svnmodule']."</td>\n";
					   			print "\t\t\t\t\t\t<td align=\"center\">".$entry['access_right']."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['username']."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['groupname']."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$validfrom."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$validuntil."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['reponame'].":".$entry['path']."</td>\n";
					   			print "\t\t\t\t\t\t<td nowrap>".$action."</td>\n";
					   			print "\t\t\t\t\t</tr>\n";
					   			
					   			$_SESSION['svn_sessid']['mark'][$i]		= $entry['id'];
					   			
					   			$i++;
					   		}
					   		
					   		$_SESSION['svn_sessid']['max_mark'] = $i - 1;
					   	?>
				   	</tbody>
				   	<tfoot>
				   		<tr>
					      <td colspan="9" class="hlp_center">
					        <?php
					      		if( ($rightAllowed == "add") or
					      		    ($rightAllowed == "edit") or
					      		    ($rightAllowed == "delete") or 
					      		    ($_SESSION['svn_sessid']['admin'] == "p") ) {
					      		    
					      			print "<input type=\"image\" name=\"fSubmit_new\" src=\"./images/edit_add.png\" value=\""._("New access right")."\"  title=\""._("New access right")."\" />     ";
					      		}
					      	?>
					      	
					      	<input type="image" name="fSubmit_delete" src="./images/delete_all.png" value="<?php print _("Delete selected"); ?>"  title="<?php print _("Delete selected"); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
					/*
					var $user;
					var $group;
					var $project;
					
					function changeFunc1() {
						
						$.ajax({
							data:	{
								maxRows: 10,
	                            name_startsWith: "",
	                            db: "accessrighttable",
	                          	userid: "<?php print $tSeeUserid;?>",
	                          	user: $("#filteruser").val(),
	                          	group: $("#filtergroup").val(),
	                          	project: $("#filterproject").val(),
	                          	allowed: " <?php print $rightAllowed;?>",
	                          	admin: "<?php print $_SESSION['svn_sessid']['admin'];?>",
							},
							dataType: 'html',
							type: 'GET',
							url: 'searchrpc.php',
							success: function(data) {
								$user = $("#filteruser").val();
								$group = $("#filtergroup").val();
								$project = $("#filterproject").val();
								$("#accessrightlist_table").remove();
								$(".ui-table-pager").remove();
								$("#form_access_list").append("<table id='accessrightlist_table'></table>");
								$("#accessrightlist_table").html(data);
								$("#accessrightlist_table").ariaSorTable({
									rowsToShow: <?php print $CONF['page_size'];?>,
									pager: true,
									textPager: '<?php print _("Page").":"; ?>',
									onInit: function(){	
									},
								});
								$("#filteruser").val($user);
								$("#filtergroup").val($group);
								$("#filterproject").val($project);
								
								$("#filteruser").die();
								$("#filtergroup").die();
								$("#filterproject").die();
								
								$("#filteruser").change(changeFunc() );
								$("#filtergroup").change(changeFunc() );
								$("#filterproject").change(changeFunc() );
							}
						});
					}
					
					function changeFunc() {
						$("#form_access_list").submit();
					}
					*/
					$("#filteruser").change(function(){
						$("#form_access_list").submit();
					});
					
					$("#filtergroup").change(function(){
						$("#form_access_list").submit();
					});
					
					$("#filterproject").change(function(){
						$("#form_access_list").submit();
					});
					
					$("#accessrightlist_table").ariaSorTable({
						rowsToShow: <?php print $CONF['page_size'];?>,
						pager: true,
						textPager: '<?php print _("Page").":"; ?>',
						onInit: function(){	
						},
					});
					
					$("#edit_form *").tooltip({
						showURL: false
					});
			</script>
		</div>