		<div id="editform">
			<h3><?php print _("Group administrators"); ?></h3>
			<p>&nbsp;</p>
			<form name="group_admin_list" method="post">
				<table>
					<tr>
						<td><?php print _("Search group");?>: </td>
						<td>
							<input id="search" class="large" type="text" name="fSearch" value="" title="<?php print _("Search group administrator by name, description or group name.");?>" />&nbsp;&nbsp;
                           	<span style="white-space:nowrap;">
                            	<input class="small imgButton" type="image" name="fSearchBtn" src="./images/search.png" value="<?php print _("Search");?>" title="<?php print _("Search group administrator.");?>" />
                            </span>
                    	</td>   
					</tr>
				</table>                                        
				<p>&nbsp;</p>
				<table class="testlayout table-autosort:0 table-stripeclass:alternate table-autopage:<?php print $CONF['page_size'];?>" id="page">
					<thead>
						<tr>
					   		<th class="table-sortable:default">
					   			<?php print _("Group name"); ?>
					   		</th>
					   		<th class="table-sortable:default">
					   			<?php print _("Group description"); ?>
					   		</th>
					   		<th class="table-sortable:default">
					   			<?php print _("Administrator"); ?>
					   		</th>
					   		<th class="table-sortable:default">
					   			<?php print _("Right"); ?>
					   		</th>
					   		<th>
					   			<?php print _("Action"); ?>
					   		</th>
					   	</tr>
					</thead>
					<tbody>
						<?php
					   		outputGroupAdmin($tGroups, $rightAllowed);
					   	?>
					</tbody>
					<tfoot>
						<tr>
				   			<td colspan="5">
								<a href="#" onclick="pageexample('previous'); return false;">&lt;&lt;&nbsp;Previous</a>
								<a href="#" id="page1" class="pagelink" onclick="pageexample(0); return false;">1</a>
								<a href="#" id="page2" class="pagelink" onclick="pageexample(1); return false;">2</a>
								<a href="#" id="page3" class="pagelink" onclick="pageexample(2); return false;">3</a>
								<a href="#" id="page4" class="pagelink" onclick="pageexample(3); return false;">4</a>
								<a href="#" id="page5" class="pagelink" onclick="pageexample(4); return false;">5</a>
								<a href="#" id="page6" class="pagelink" onclick="pageexample(5); return false;">6</a>
								<a href="#" onclick="pageexample('next'); return false;">Next&nbsp;&gt;&gt;</a>
							</td>
				   		</tr>
						<tr>
					      <td colspan="5" class="hlpcenter">
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
				<input id="id" type="hidden" />
			</form>
			<script>
					function pageexample(page) {
						var t = document.getElementById('page');
						var res;
						if (page=="previous") {
							res=Table.pagePrevious(t);
						}
						else if (page=="next") {
							res=Table.pageNext(t);
						}
						else {
							res=Table.page(t,page);
						}
						var currentPage = res.page+1;
						$('.pagelink').removeClass('currentpage');
						$('#page'+currentPage).addClass('currentpage');
					}
					
					$("#search").autocomplete({
                        source: function( request, response ) {
                                $.ajax({
                                        url: "searchrpc.php",
                                        dataType: "jsonp",
                                        data: {
                                                maxRows: 10,
                                                name_startsWith: request.term,
                                                db: "groupadmin"
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
                                document.group_admin_list.submit();
                        },
                        open: function() {
                                $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
                        },
                        close: function() {
                                $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
                        }

                	});
					
					$("#editform *").tooltip({
						showURL: false
					});
			</script>
		</div>