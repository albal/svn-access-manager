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
				      	<input type="image" name="fSubmit_ok" src="./images/ok.png" value="<?php print _("Submit"); ?>"  title="<?php print _("Submit"); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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