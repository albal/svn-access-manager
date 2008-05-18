		<div id="edit_form">
			<form name="project_list" method="post">
				<table>
				   	<tr>
				      <td colspan="6"><h3><?php print _("Project administration"); ?></h3></td>
				   	</tr>
				   	<tr>
				      <td colspan="6">&nbsp;</td>
				   	</tr>
				   	<tr class="theader">
				   		<td>
				   			<?php print _("Subversion project"); ?>
				   		</td>
				   		<td>
				   			<?php print _("Subversion module path"); ?>
				   		</td>
				   		<td>
				   			<?php print _("Repository"); ?>
				   		</td>
				   		<td width="20">&nbsp;</td>
				   		<td>
				   			<?php print _("Action"); ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<?php
				   		foreach( $tProjects as $entry ) {
				   		
				   			$id						= $entry['id'];
				   			$action					= "<a href=\"workOnProject.php?id=".$entry['id']."&task=change\" title=\""._("Change")."\" alt=\""._("Change")."\"><img src=\"./images/edit.png\" border=\"0\" /></a>     <a href=\"deleteProject.php?id=".htmlentities($entry['id'])."&task=delete\" title=\""._("Delete")."\" alt=\""._("Delete")."\"><img src=\"./images/edittrash.png\" border=\"0\" /></a>";
				   			
				   			print "\t\t\t\t\t<tr>\n";
				   			print "\t\t\t\t\t\t<td>".$entry['svnmodule']."</td>\n";
				   			print "\t\t\t\t\t\t<td>".$entry['modulepath']."</td>\n";
				   			print "\t\t\t\t\t\t<td>".$entry['reponame']."</td>\n";
				   			print "\t\t\t\t\t\t<td> </td>\n";
				   			print "\t\t\t\t\t\t<td>".$action."</td>\n";
				   			print "\t\t\t\t\t\t<td> </td>\n";
				   			print "\t\t\t\t\t</tr>\n";
				   		}
				   	?>
				   	<tr>
				      <td colspan="6">&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="6" class="hlp_center">
				      	<input class="button" type="submit" name="fSubmit" value="<?php print _("New project"); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				      	<input class="button" type="submit" name="fSubmit" value="<?php print _("Back"); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				      	<?php
				      		if( $tPrevDisabled != "disabled" ) {
				      			
				      			print "\t\t\t\t\t\t<input class='button' type='submit' name='fSubmit' value='"._("<<")."' /> \n";
				      			print "\t\t\t\t\t\t<input class='button' type='submit' name='fSubmit' value='"._("<")."' />          \n";
				      			
				      		}
				      		
				      		if( $tNextDisabled != "disabled" ) {
				      		
				      			print "\t\t\t\t\t\t<input class='button' type='submit' name='fSubmit' value='"._(">")."' /> \n";
				      			print "\t\t\t\t\t\t<input class='button' type='submit' name='fSubmit' value='"._(">>")."' />\n";
				      			
				      		}
				      	?>
				      </td>
				   	</tr>
				   	<tr>
				      <td colspan="6" class="standout">
				      	<?php print $tMessage; ?>
				      </td>
				   	</tr>
				</table>
			</form>
		</div>