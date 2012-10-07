		<div id="edit_form">
			<h3><?php print _("Log report"); ?></h3>
			<p>&nbsp;</p>
			<form name="rep_log" method="post">
				<table id="log_table">
					<thead>
						<tr>
					   		<th>
					   			<?php print _("Date"); ?>
					   		</th>
					   		<th>
					   			<?php print _("Username"); ?>
					   		</th>
					   		<th>
					   			<?php print _("IP Address"); ?>
					   		</th>
					   		<th>
					   			<?php print _("Logmessage"); ?>
					   		</th>
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
					      <td colspan="5" class="standout">
					      	<?php print $tMessage; ?>
					      </td>
					   	</tr>
					</tfoot>
				</table>
			</form>
			<script>
					$("#log_table").ariaSorTable({
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