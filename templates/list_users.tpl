		<div id="editform">
			<form name="user_list" method="post">
				<h3><?php print _("User administration"); ?></h3>
				<p>&nbsp;</p>
				<table>
					<tr>
						<td><?php print _("Search user");?>: </td>
						<td>
							<input id="search" class="large" type="text" name="fSearch" value="" title="<?php print _("Search user by uid or name.");?>" />&nbsp;&nbsp;
                           	<span style="white-space:nowrap;">
                            	<input class="small imgButton" type="image" name="fSearchBtn" src="./images/search.png" value="<?php print _("Search");?>" title="<?php print _("Search user.");?>" />
                            </span>
                    	</td>   
					</tr>
				</table>                                        
				<p>&nbsp;</p>
				<table class="testlayout table-autosort:1 table-stripeclass:alternate table-autopage:<?php print $CONF['page_size'];?>" id="page">
				   	<thead>
				   		<tr >
					   		<th class="table-sortable:default">
					   			<?php print _("UserId"); ?>
					   		</th>
					   		<th class="table-sortable:default">
					   			<?php print _("Name"); ?>
					   		</th>
					   		<th class="table-sortable:default">
					   			<?php print _("Given name"); ?>
					   		</th>
					   		<th class="table-sortable:default">
					   			<?php print _("Email"); ?>
					   		</th>
                            <?php
                                    outputCustomFields();
                            ?>
					   		<th class="table-sortable:default">
					   			<?php print _("Right"); ?>
					   		</th>
					   		<th class="table-sortable:default">
					   			<?php print _("Locked"); ?>
					   		</th>
					   		<th class="table-sortable:default">
					   			<?php print _("Password changed"); ?>
					   		</th>
					   		<th class="table-sortable:default">
					   			<?php print _("Password expires"); ?>
					   		</th>
					   		<th class="table-sortable:default">
					   			<?php print _("Administrator"); ?>
					   		</th>
					   		<?php
					   				if( (isset($CONF['use_ldap'])) && (strtoupper($CONF['use_ldap']) == "YES") ) {
                                            print "\t\t\t\t\t\t<th class=\"table-sortable:default\">\n";
                                            print "\t\t\t\t\t\t\t"._("LDAP User");
                                            print "\t\t\t\t\t\t</th>\n";
                                    }
					   		?>
					   		<th>
					   			<?php print _("Action"); ?>
					   		</th>
					   	</tr>
				   	</thead>
				   	<tbody>
				   		<?php
					   		outputUsers($tUsers, $rightAllowed);
					   	?>
				   	</tbody>
					<tfoot>
						<tr>
				   			<td colspan="10">
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
					      <td colspan="10">&nbsp;</td>
					   	</tr>
					   	<tr>
					      <td colspan="10" class="hlpcenter">
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
					      <td colspan="10" class="standout">
					      	<?php print $tMessage; ?>
					      </td>
					   	</tr> 	
					</tfoot>
				   	
				</table>
			</form>
			<script type="text/javascript">
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
                                                db: "people"
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
                                document.user_list.submit();
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
