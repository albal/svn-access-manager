		<div id="edit_form">
			<h3><?php print _("Access rights administration"); ?></h3>
			<p>&nbsp;</p>
			<form id="form_access_list" name="list_access_rights" method="post">
				<table class="testlayout table-autosort:3 table-stripeclass:alternate table-autopage:<?php print $CONF['page_size'];?>" id="page">
				   	<thead>
				   			<tr>
					   		<th class="table-sortable:default">
					   			<strong><?php print _("M"); ?></strong>
					   		</th>
					   		<th class="table-sortable:default">
					   			<strong><?php print _("Project"); ?></strong>
					   		</th>
					   		<th class="table-sortable:default">
					   			<strong><?php print _("Rights"); ?></strong>
					   		</th>
					   		<th class="table-sortable:default">
					   			<strong><?php print _("User"); ?></strong>
					   		</th>
					   		<th class="table-sortable:default">
					   			<strong><?php print _("Group"); ?></strong>
					   		</th>
					   		<th class="table-sortable:date">
					   			<strong><?php print _("Valid from"); ?></strong>
					   		</th>
					   		<th class="table-sortable:date">
					   			<strong><?php print _("Valid until"); ?></strong>
					   		</th>
					   		<th class="table-sortable:default">
					   			<strong><?php print _("Repository:Directory"); ?></strong>
					   		</th>
					   		<th>
					   			<strong><?php print _("Action"); ?></strong>
					   		</th>
					   	</tr>	
					   	<tr>
							<th>Filter:</th>
							<th><input name="filterp" size="8" onkeyup="Table.filter(this,this)"></th>
							<th>&nbsp;</th>
							<th><input name="filteru" size="8" onkeyup="Table.filter(this,this)"></th>
							<th><input name="filterg" size="8" onkeyup="Table.filter(this,this)"></th>
							<th>&nbsp;</th>
							<th>&nbsp;</th>
							<th>&nbsp;</th>
							<th>&nbsp;</th>
						</tr>
				   	</thead>
				   	<tbody>
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
				   			<td colspan="9">
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
				<input type="hidden" id="cntl" name="fCntl" />
			</form>
			<script>
					$("#searchbutton").hide();
					
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