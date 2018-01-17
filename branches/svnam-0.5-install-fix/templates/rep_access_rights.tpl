		<div id="edit_form">
			<h3><?php print sprintf( _("List of access rights for %s"), $_SESSION[SVNSESSID]['date'] ); ?></h3>
			<p>&nbsp;</p>
			<form action="rep_access_rights.php" name="rep_access_rights" method="post">
				<table class="testlayout table-autosort:6 table-stripeclass:alternate table-autopage:<?php print $CONF['page_size'];?>" id="page">
				   	<thead>
				   		<tr>
					   		<th class="table-sortable:ignorecase">
					   			<strong><?php print _("Project"); ?></strong>
					   		</th>
					   		<th class="table-sortable:default">
					   			<strong><?php print _("Rights"); ?></strong>
					   		</th>
					   		<th align="center" class="table-sortable:ignorecase">
					   			<strong><?php print _("User"); ?></strong>
					   		</th>
					   		<th align="center" class="table-sortable:ignorecase">
					   			<strong><?php print _("Group"); ?></strong>
					   		</th>
					   		<th align="center"class="table-sortable:date">
					   			<strong><?php print _("Valid from"); ?></strong>
					   		</th>
					   		<th align="center" class="table-sortable:date">
					   			<strong><?php print _("Valid until"); ?></strong>
					   		</th>
					   		<th class="table-sortable:default">
					   			<strong><?php print _("Repository:Directory"); ?></strong>
					   		</th>
					   	</tr>
					   	<tr>
							<th><input name="filterp" size="8" onkeyup="Table.filter(this,this)"></th>
							<th>&nbsp;</th>
							<th><input name="filteru" size="8" onkeyup="Table.filter(this,this)"></th>
							<th><input name="filterg" size="8" onkeyup="Table.filter(this,this)"></th>
							<th>&nbsp;</th>
							<th>&nbsp;</th>
							<th><input name="filterr" size="8" onkeyup="Table.filter(this,this)"></th>
						</tr>
				   	</thead>
				   	<tbody>
				   		<?php
					   		$i 										= 0;
					   		$_SESSION[SVNSESSID]['max_mark']		= 0;
					   		$_SESSUIN[SVNSESSID]['mark']			= array();
					   		
					   		foreach( $tAccessRights as $entry ) {
					   		
					   			$id						= $entry['id'];
					 
					   			$validfrom				= splitValidDate( $entry['valid_from'] );
					   			$validuntil				= splitValiddate( $entry['valid_until'] );
					   			$field					= "fDelete".$i;
					   			
					   			print "\t\t\t\t\t<tr valign=\"top\">\n";
					   			print "\t\t\t\t\t\t<td>".$entry['svnmodule']."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['access_right']."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['username']."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['groupname']."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$validfrom."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$validuntil."</td>\n";
					   			print "\t\t\t\t\t\t<td>".$entry['reponame'].":".$entry['path']."</td>\n";
					   			print "\t\t\t\t\t</tr>\n";
					   			
					   			$_SESSION[SVNSESSID]['mark'][$i]		= $entry['id'];
					   			
					   			$i++;
					   		}
					   		
					   		$_SESSION[SVNSESSID]['max_mark'] = $i - 1;
					   	?>
				   	</tbody>
				   	<tfoot>
				   		<tr>
				   			<td colspan="7">
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
					      <td colspan="7" class="standout">
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