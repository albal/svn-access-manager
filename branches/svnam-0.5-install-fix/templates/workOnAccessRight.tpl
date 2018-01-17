		<?php
			#if( $CONF['use_javascript'] == "YES" ) {
			
				echo "\t\t\t<script language=\"JavaScript1.3\">\n"; 
				echo "\t\t\t\tfunction onChangeDir() {\n";
				echo "\t\t\t\t\tdocument.workOnAccessRight.submit();\n";
				echo "\t\t\t\t}\n";
				echo "\t\t\t</script>\n";

				$tChangeFunction = 'onchange="onChangeDir();"';
			#} else {
			#	$tChangeFunction = "";
			#}
		?>
		<div id="edit_form">
			<form name="workOnAccessRight" method="post">
				<table>
				   	<tr>
				      <td colspan="3"><h3><?php print _("Access right administration / Step 2: select directory"); ?></h3></td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td nowrap><strong><?php print _("Project").": "; ?></strong></td>
				   		<td>
				   			<?php print $tProjectName; ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td nowrap><strong><?php print _("Subversion module path").": "; ?></strong></td>
				   		<td>
				   			<?php print $tModulePath; ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td nowrap><strong><?php print _("Selected directory").": "; ?></strong></td>
				   		<td><input type="text" name="fPathSelected" value="<?php print $tPathSelected; ?>" size="60" />
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td colspan="2">
				   			<?php print _("You can edit the directory path above to your needs. But keep in mind that the path must match a valid path in the repository. Regular expressions are not allowed."); ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr valign="top">
				   		<td nowrap><strong><?php print _("Select directory").": "; ?></strong></td>
				   		<td>
				   			<select name="fPath" size="15" style="width: 100%; height=200px;" <?php print $tChangeFunction; ?> title="<?php print _("Select the directory you want to descend into and click 'Change to directory' afterwards if no JavaScript is enabled. '..' ascends one level if possible." ); ?>">
				   				
				   				<?php
				   					if( $_SESSION[SVNSESSID]['pathcnt'] > 0 ) {
				   						print "\t\t\t\t\t\t\t\t<option value=\"[back]\">[..]</option>\n";
				   					}
				   					
				   					if($fileSelect == 0) {
										
										$files = array();
					   					foreach( $tRepodirs as $dir ) {
					   					
					   						#$dirs = explode("/", $dir);
					   						#$count = count( $dir ) - 1;
					   						#if( $count >= 0 ) {
					   						#	$direntry = $dirs[$count];
					   						#	print '\t\t\t\t\t\t\t\t<option value="'.$dir.'">'.$direntry."/".'</option>\n';
					   						#} elseif( strtolower($accessControl) == "files" ) {
					   						#	$files[] = $dir;
					   						#}
					   						
					   						if( preg_match( '/\/$/', $dir ) ) {
					   							print '\t\t\t\t\t\t\t\t<option value="'.$dir.'">'.$dir.'</option>\n';
					   						} else {
					   							$files[] = $dir;
					   						}
					   						
					   					}
					   					
					   					foreach( $files as $file ) {
					   						print '\t\t\t\t\t\t\t\t<option value="'.$file.'">'.$file.'</option>\n';
					   					}
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
				      <td colspan="3" class="hlp_center">
				      	<input type="image" name="fSubmit_chdir" src="./images/chdir.png" value="<?php print _("Change to directory"); ?>"  title="<?php print _("Change to directory"); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				      	<input type="image" name="fSubmit_set" src="./images/forward.png" value="<?php print _("Set access rights"); ?>"  title="<?php print _("Set access rights"); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				      	<input type="image" name="fSubmit_back" src="./images/button_cancel.png" value="<?php print _("Back"); ?>" title="<?php print _("Back"); ?>" />
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
		</div>