		<div id="editform">
			<form action="rep_show_user.php" name="show_user_select" method="post">
				<table>
				   	<tr>
				      <td colspan="3"><h3><?php print _("User to show"); ?></h3></td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr valign="top">
				   		<td width="150"><strong><?php print _("User").": "; ?></strong></td>
				   		<td>
				   			<select name="fUser" id="user" class="chzn-select">
				   				<?php
				   					print "\t\t\t<option value='default'>"._("--- Select user ---")."</option>\n";
				   					foreach( $tUsers as $entry ) {
				   						
				   						if( $entry['givenname'] != "" ) {
				   							$name		= $entry['name'].", ".$entry['givenname'];
				   						} else {
				   							$name		= $entry['name'];
				   						}
				   						 
				   						print "\t\t\t<option value='".$entry['id']."'>".$name." (".$entry['userid'].")</option>\n";
				   					}
				   				?>
				   			</select>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="3" class="hlpcenter">
				      	<input type="image" name="fSubmit_show" src="./images/ok.png" value="<?php print _("Create report"); ?>"  title="<?php print _("Create report"); ?>" />
				      </td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="3" class="standout">
				      	<?php print $tMessage; ?>
				      </td>
				   	</tr>
				</table>
			</form>
			<script>
				$("#user").chosen({no_results_text: "No results matched"});
			</script>
		</div>