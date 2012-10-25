		<div id="edit_form">
			<h3><?php print _("List of locked users"); ?></h3>
			<p>&nbsp;</p>
			<form name="rep_locked_users" method="post">
				<table id="lockeduser_table">
				   	<thead>
				   		<tr>
					   		<th>
					   			<?php print _("Last Modified"); ?>
					   		</th>
					   		<th>
					   			<?php print _("Modified by user"); ?>
					   		</th>
					   		<th>
					   			<?php print _("Username"); ?>
					   		</th>
					   		<th>
					   			<?php print _("Name"); ?>
					   		</th>
					   		<th>
					   			<?php print _("Given name"); ?>
					   		</th>
					   	</tr>
				   	</thead>
				   	<tbody>
				   		<?php
					   		if( is_array( $tLockedUsers ) ) {
					   		
						   		foreach( $tLockedUsers as $entry ) {
						   		
						   			list($date, $time)		= splitDateTime( $entry['modified'] );
						   			
						   			print "\t\t\t\t\t<tr>\n";
						   			print "\t\t\t\t\t\t<td>".$date." ".$time."</td>\n";
						   			print "\t\t\t\t\t\t<td>".$entry['modified_user']."</td>\n";
						   			print "\t\t\t\t\t\t<td>".$entry['userid']."</td>\n";
						   			print "\t\t\t\t\t\t<td>".$entry['name']."</td>\n";
						   			print "\t\t\t\t\t\t<td>".$entry['givenname']."</td>\n";
						   			print "\t\t\t\t\t</tr>\n";
						   		}
						   		
					   		}
					   	?>
				   	</tbody>
				   	<tfoot>
				   		<tr>
					      <td colspan="5" class="standout">
					      	<?php print $tMessage; ?>
					      </td>
					   	</tr>
				   	</tfoot>
				</table>
			</form>
			<script>
					$("#lockeduser_table").ariaSorTable({
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