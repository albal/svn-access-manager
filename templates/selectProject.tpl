		<div id="edit_form">
			<form name="selectProject" method="post">
				<table>
				   	<tr>
				      <td colspan="3"><h3><?php print _("Access right administration / Step 1: select project"); ?></h3></td>
				   	</tr>
				   	<tr>
				      <td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td nowrap><strong><?php print _("Project").": "; ?></strong></td>
				   		<td>
				   			<select name="fProject">
				   				<?php
				   					foreach( $tProjects as $projectId => $projectName ) {
				   					
				   						if( $tProject == $projectId ) {
				   						
				   							print "\t\t\t\t\t\t\t\t<option value='".$projectId."' selected>".$projectName."</option>\n";
				   							
				   						} else {
				   						
				   							print "\t\t\t\t\t\t\t\t<option value='".$projectId."'>".$projectName."</option>\n";
				   							
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
				      	<input class="button" type="submit" name="fSubmit" value="<?php print _("Select project"); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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