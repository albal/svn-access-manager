		<div id="edit_form">
			<form name="createAccessFilesResult" method="post">
				<table>
				   	<tr>
				      <td colspan="3"><h3><?php print _("Create Access Files"); ?></h3></td>
				   	</tr>
				   	<tr>
				   		<td width="200"><strong><?php print _("Result of auth user file").": "; ?></strong></td>
				   		<td><?php print $tRetAuthUser['errormsg']; ?></td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td width="200"><strong><?php print _("Result of access file").": "; ?></strong></td>
				   		<td><?php print $tRetAccess['errormsg']; ?></td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<?php
				   		if( $CONF['createViewvcConf'] != "YES" ) {
				   			print "<!--\n";
				   		}
				   	?>
				   	<tr>
				   		<td width="200"><strong><?php print _("Result of viewvc config").": "; ?></strong></td>
				   		<td><?php print $tRetViewvc['errormsg']; ?></td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td width="200"><strong><?php print _("Result of webserver reload").": "; ?></strong></td>
				   		<td><?php print $tRetReload['errormsg']; ?></td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<?php
				   		if( $CONF['createViewvcConf'] != "YES" ) {
				   			print "-->\n";
				   		}
				   	?>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				</table>
			</form>
		</div>