		<div id="editform">
			<h3><?php print _("Log report"); ?></h3>
			<p>&nbsp;</p>
			<form name="rep_log" method="post">
				<table class="testlayout table-autosort:0 table-stripeclass:alternate table-autopage:<?php print $CONF['page_size'];?>" id="page">
					<thead>
						<tr>
					   		<th class="table-sortable:datetime">
					   			<?php print _("Date"); ?>
					   		</th>
					   		<th class="table-sortable:ignorecase">
					   			<?php print _("Username"); ?>
					   		</th>
					   		<th class="table-sortable:default">
					   			<?php print _("IP Address"); ?>
					   		</th>
					   		<th class="table-sortable:default">
					   			<?php print _("Logmessage"); ?>
					   		</th>
					   	</tr>
					   	<tr>
							<th><input name="filterd" size="8" onkeyup="Table.filter(this,this)"></th>
							<th><input name="filteru" size="8" onkeyup="Table.filter(this,this)"></th>
							<th><input name="filteri" size="8" onkeyup="Table.filter(this,this)"></th>
							<th><input name="filterr" size="8" onkeyup="Table.filter(this,this)"></th>
						</tr>
					</thead>
					<tbody>
						<?php
					   		foreach( $tLogmessages as $entry ) {
					   		
					   			list($date, $time)		= splitDateTime( $entry['logtimestamp'] );
					   			
					   			print "\t\t\t\t\t<tr>\n";
					   			print "\t\t\t\t\t\t<td>".$date." ".$time."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['username']."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['ipaddress']."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['logmessage']."</td>\n";
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
					
					$("#editform *").tooltip({
						showURL: false
					});
			</script>
		</div>