		<div id="edit_form">
			<form action="rep_access_rights.php" name="getDateforAccessRights" method="post">
				<table>
				   	<tr>
				      <td colspan="3"><h3><?php print _("Date for access rights report"); ?></h3></td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr valign="top">
				   		<td width="150"><strong><?php print _("Date").": "; ?></strong></td>
				   		<td>
				   			<input type="text" name="fDate" id="date" value="<?php print $tDate; ?>" size="10" maxsize="10" />
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="3" class="hlp_center">
				      	<input type="image" name="fSubmit_date" src="./images/ok.png" value="<?php print _("Create report"); ?>"  title="<?php print _("Create report"); ?>" />
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
					var year  = "<?php print date('Y');?>";
					var month = "<?php print date('m') - 1;?>";
					var day   = "<?php print date('d');?>";
					
					$( "#date" ).datepicker({
						maxDate: new Date(year, month, day),
						regional: ['<?php print $tLocale;?>'],
						altFormat: ['<?php print $tDateFormat;?>'],
					});
			</script>
		</div>