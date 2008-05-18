		<?php
			if( $CONF['use_javascript'] == "YES" ) {
			
				echo "\t\t\t<script language=\"JavaScript1.3\">\n"; 
				echo "\t\t\t\tfunction onChangeDir() {\n";
				echo "\t\t\t\t\tdocument.workOnAccessRight.submit();\n";
				echo "\t\t\t\t}\n";
				echo "\t\t\t</script>\n";

				$tChangeFunction = 'onchange="onChangeDir();"';
			} else {
				$tChangeFunction = "";
			}
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
				   		<td><?php print _("This path will be prepended to the directories you select below."); ?></td>
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
				   			<select name="fPath" size="15" style="width: 100%; height=200px;" <?php print $tChangeFunction; ?> >
				   				
				   				<?php
				   					if( $_SESSION['svn_sessid']['pathcnt'] > 0 ) {
				   						print "\t\t\t\t\t\t\t\t<option value=\"[back]\">[..]</option>\n";
				   					}
				   					
				   					foreach( $tRepodirs as $dir ) {
				   					
				   						$dirs = explode("/", $dir);
				   						$count = count( $dir ) - 1;
				   						if( $count >= 0 ) {
				   							$direntry = $dirs[$count];
				   							print '\t\t\t\t\t\t\t\t<option value="'.$dir.'">'.$direntry.'</option>\n';
				   						}
				   						
				   					}
				   				?>
				   			</select>
				   		</td>
				   		<td><?php print _("Select the directory you want to descend into and click 'Change to directory' afterwards if no JavaScript is enabled. '..' ascends one level if possible." ); ?></td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="3" class="hlp_center">
				      	<input class="button" type="submit" name="fSubmit" value="<?php print _("Change to directory"); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				      	<input class="button" type="submit" name="fSubmit" value="<?php print _("Set access rights"); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				      	<input class="button" type="submit" name="fSubmit" value="<?php print _("Back"); ?>" />
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