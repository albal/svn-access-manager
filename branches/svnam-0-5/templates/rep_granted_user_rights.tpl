		<div id="edit_form">
			<h3><?php print _("List of granted user rights"); ?></h3>
			<p>&nbsp;</p>
			<form name="rep_granted_user_rights" method="post">
				<table id="grantedrights_table">
				   	<thead>
				   		<tr>
					   		<th>
					   			&nbsp;
					   		</th>
					   		<th>
					   			<strong><?php print _("Userid"); ?></strong>
					   		</th>
					   		<th>
					   			<strong><?php print _("Username"); ?></strong>
					   		</th>
					   		<th align="center">
					   			<strong><?php print _("Granted rights"); ?></strong>
					   		</th>
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
					   			print "\t\t\t\t\t\t<td> </td>\n";
					   			print "\t\t\t\t\t</tr>\n";
	
					   		}
					   	?>
				   	</tbody>
				   	<tfoot>
				   		<tr>
					      <td colspan="3" class="standout">
					      	<?php print $tMessage; ?>
					      </td>
					   	</tr>
				   	</tfoot>
				</table>
			</form>
			<script>
					$("#grantedrights_table").ariaSorTable({
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