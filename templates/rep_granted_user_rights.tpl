		<div id="edit_form">
			<h3><?php print _("List of granted user rights"); ?></h3>
			<p>&nbsp;</p>
			<form name="rep_granted_user_rights" method="post">
				<table class="testlayout table-autosort:2 table-stripeclass:alternate table-autopage:<?php print $CONF['page_size'];?>" id="page">
				   	<thead>
				   		<tr>
					   		<th>
					   			&nbsp;
					   		</th>
					   		<th class="table-sortable:ignorecase">
					   			<strong><?php print _("Userid"); ?></strong>
					   		</th>
					   		<th class="table-sortable:ignorecase">
					   			<strong><?php print _("Username"); ?></strong>
					   		</th>
					   		<th class="table-sortable:ignorecase">
					   			<strong><?php print _("Granted rights"); ?></strong>
					   		</th>
					   	</tr>
					   	<tr>
							<th>&nbsp;</th>
							<th><input name="filteru" size="8" onkeyup="Table.filter(this,this)"></th>
							<th><input name="filterun" size="8" onkeyup="Table.filter(this,this)"></th>
							<th><input name="filterg" size="8" onkeyup="Table.filter(this,this)"></th>
						</tr>
				   	</thead>
				   	<tbody>
				   		<?php
					   						   		
					   		foreach( $tGrantedRights as $entry ) {
					   		
					   			if( $entry['locked'] == 1 ) {
					   				$locked				= "<img src='./images/locked_16_16.png' width='16' height='16' border='0' alt='"._("User locked")."' title='"._("User locked")."' />";
					   			} else {
					   				$locked				= "&nbsp;";
					   			}
					   			
					   			print "\t\t\t\t\t<tr valign=\"top\">\n";
					   			print "\t\t\t\t\t\t<td>".$locked."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['userid']."</td>\n";
					   			print "\t\t\t\t\t\t<td nowrap>".$entry['name']."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['rights']."</td>\n";
					   			print "\t\t\t\t\t</tr>\n";
	
					   		}
					   	?>
				   	</tbody>
				   	<tfoot>
				   		<tr>
				   			<td colspan="4">
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
					      <td colspan="4" class="standout">
					      	<?php print $tMessage; ?>
					      </td>
					   	</tr>
				   	</tfoot>
				</table>
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
					
					$("#edit_form *").tooltip({
						showURL: false
					});
			</script>
		</div>