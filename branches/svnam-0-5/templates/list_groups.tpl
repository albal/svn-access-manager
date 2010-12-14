		<div id="edit_form">
			<form name="group_list" method="post">
				<table>
				   	<tr>
				      <td colspan="5"><h3><?php print _("Group administration"); ?></h3></td>
				   	</tr>
				   	<tr>
				      <td colspan="5">&nbsp;</td>
				   	</tr>
				   	<tr class="theader">
				   		<td nowrap>
				   			<?php print _("Group name"); ?>
				   		</td>
				   		<td>
				   			<?php print _("Group description"); ?>
				   		</td>
				   		<td width="10">
				   			&nbsp;
				   		</td>
				   		<td>
				   			<?php print _("Action"); ?>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<?php
				   		foreach( $tGroups as $entry ) {
				   		
				   			$groupRight				=  isset($tGroupsAllowed[$entry['id']]) ? $tGroupsAllowed[$entry['id']] : "none";
				   			
				   			if( ($rightAllowed == "edit") or
				   			    ($rightAllowed == "delete") or
				   			    ($groupRight == "edit") or
				   			    ($groupRight == "delete") 
				   			   ) {
				   			   	$url				= htmlentities("workOnGroup.php?id=".$entry['id']."&task=change");
				   			    $edit				= "<a href=\"$url\" title=\""._("Change")."\" alt=\""._("Change")."\"><img src=\"./images/edit.png\" border=\"0\" /></a>";
				   			} else {
				   				$edit				= "";
				   			}
				   			
				   			
				   			if( ($rightAllowed == "delete") or ($groupRight == "delete") ) {
				   				$url				= htmlentities("deleteGroup.php?id=".$entry['id']."&task=delete");
				   				$delete				= "<a href=\"$url\" title=\""._("delete")."\" alt=\""._("Delete")."\"><img src=\"./images/edittrash.png\" border=\"0\" /></a>";
				   			} else {
				   				$delete				= "";
				   			}
				   			$action					= $edit."     ".$delete;
				   			
				   			print "\t\t\t\t\t<tr>\n";
				   			print "\t\t\t\t\t\t<td>".$entry['groupname']."</td>\n";
				   			print "\t\t\t\t\t\t<td>".$entry['description']."</td>\n";
				   			print "\t\t\t\t\t\t<td> </td>\n";
				   			print "\t\t\t\t\t\t<td>".$action."</td>\n";
				   			print "\t\t\t\t\t\t<td> </td>\n";
				   			print "\t\t\t\t\t</tr>\n";
				   		}
				   	?>
				   	<tr>
				      <td colspan="5">&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="5" class="hlp_center">
				        <?php
				      		if( ($rightAllowed == "add") or
				      		    ($rightAllowed == "edit") or
				      		    ($rightAllowed == "delete") ) {
				      		    
				      			print "<input type=\"image\" name=\"fSubmit_new\" src=\"./images/add_group.png\" value=\""._("New group")."\"  title=\""._("New group")."\" />     ";
				      		}
				      	?>
				      	
				      	<input type="image" name="fSubmit_back" src="./images/button_cancel.png" value="<?php print _("Back"); ?>" title="<?php print _("Back"); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				      	<?php
				      		if( $tPrevDisabled != "disabled" ) {
				      			
				      			print "\t\t\t\t\t\t<input type='image' name='fSubmit_f' src='./images/first.png' value='"._("<<")."' /> \n";
				      			print "\t\t\t\t\t\t<input type='image' name='fSubmit_p' src='./images/previous.png' value='"._("<")."' />          \n";

				      		} else {
				      		
				      			print "\t\t\t\t\t\t<img src='./images/clear.gif' width='24' height='24' /> \n";
				      			print "\t\t\t\t\t\t<img src='./images/clear.gif' width='24' height='24' />          \n";
				      		}
				      		
				      		if( $tNextDisabled != "disabled" ) {
				      		
				      			print "\t\t\t\t\t\t<input type='image' name='fSubmit_n' src='./images/next.png' value='"._(">")."' /> \n";
				      			print "\t\t\t\t\t\t<input type='image' name='fSubmit_l' src='./images/last.png' value='"._(">>")."' />\n";

				      		} else {
				      		
				      			print "\t\t\t\t\t\t<img src='./images/clear.gif' width='24' height='24' /> \n";
				      			print "\t\t\t\t\t\t<img src='./images/clear.gif' width='24' height='24' />\n";
				      		}
				      	?>
				      </td>
				   	</tr>
				   	<tr>
				      <td colspan="5" class="standout">
				      	<?php print $tMessage; ?>
				      </td>
				   	</tr>
				</table>
			</form>
		</div>