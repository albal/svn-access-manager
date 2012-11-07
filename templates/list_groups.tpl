		<div id="edit_form">
			<h3><?php print _("Group administration"); ?></h3>
			<p>&nbsp;</p>
			<form name="group_list" method="post">
				<table>
					<tr>
						<td><?php print _("Search group");?>: </td>
						<td>
							<input id="search" class="large" type="text" name="fSearch" value="" title="<?php print _("Search group by description or name.");?>" />&nbsp;&nbsp;
                           	<span style="white-space:nowrap;">
                            	<input class="small imgButton" type="image" name="fSearchBtn" src="./images/search.png" value="<?php print _("Search");?>" title="<?php print _("Search group.");?>" />
                            </span>
                    	</td>   
					</tr>
				</table>                                        
				<p>&nbsp;</p>
				<table id="grouplist_table">
				   	<thead>
				   		<tr>
					   		<th class="ui-table-default">
					   			<?php print _("Group name"); ?>
					   		</th>
					   		<th class="ui-table-default">
					   			<?php print _("Group description"); ?>
					   		</th class="ui-table-deactivate">
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
					
					$("#search").autocomplete({
                        source: function( request, response ) {
                                $.ajax({
                                        url: "searchrpc.php",
                                        dataType: "jsonp",
                                        data: {
                                                maxRows: 10,
                                                name_startsWith: request.term,
                                                db: "groups",
                                                userid: "<?php print $SESSID_USERNAME;?>"
                                        },
                                        success: function( data ) {
                                                var retarr =[];
                                                $.each(data, function(i, val){
                                                        myName = $("<div/>").html(val.name).text();
                                                        if(myName == "Session expired!") {
                                                                window.location.href="login.php";
                                                        }
                                                        retarr.push(myName);
                                                });
                                                response(retarr);
                                        }
                                });
                        },
                        minLength: 1,
                        select: function( event, ui ) {
                                var name = ui.item.value;
                                $("#search").val(name);
                                document.group_list.submit();
                        },
                        open: function() {
                                $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
                        },
                        close: function() {
                                $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
                        }

                	});
					
					$("#edit_form *").tooltip({
						showURL: false
					});
			</script>
		</div>