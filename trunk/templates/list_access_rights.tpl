		<div id="edit_form">
			<form name="list_access_rights" method="post">
				<table>
				   	<tr>
				      <td colspan="11"><h3><?php print _("Access rights administration"); ?></h3></td>
				   	</tr>
				   	<tr>
				      <td colspan="11">&nbsp;</td>
				   	</tr>
				   	<tr class="theader">
				   		<td align="center">
				   			<strong><?php print _("M"); ?></strong>
				   		</td>
				   		<td>
				   			<strong><?php print _("Project"); ?></strong>
				   		</td>
				   		<td>
				   			<strong><?php print _("Rights"); ?></strong>
				   		</td>
				   		<td align="center">
				   			<strong><?php print _("User"); ?></strong>
				   		</td>
				   		<td align="center">
				   			<strong><?php print _("Group"); ?></strong>
				   		</td>
				   		<td align="center">
				   			<strong><?php print _("Valid from"); ?></strong>
				   		</td>
				   		<td align="center">
				   			<strong><?php print _("Valid until"); ?></strong>
				   		</td>
				   		<td>
				   			<strong><?php print _("Repository:Directory"); ?></strong>
				   		</td>
				   		<td width="20">&nbsp;</td>
				   		<td>
				   			<strong><?php print _("Action"); ?></strong>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<?php
				   		$i 										= 0;
				   		$_SESSION['svn_sessid']['max_mark']		= 0;
				   		$_SESSUIN['svn_sessid']['mark']			= array();
				   		
				   		foreach( $tAccessRights as $entry ) {
				   		
				   			$id						= $entry['id'];
				   			$validfrom				= splitValidDate( $entry['valid_from'] );
				   			$validuntil				= splitValiddate( $entry['valid_until'] );
				   			$field					= "fDelete".$i;
				   			$action					= "";
				   			
				   			if( $rightAllowed == "edit" ) {
				   				$action					= "<a href=\"workOnAccessRight.php?id=".$entry['id']."&task=change\" title=\""._("Change")."\" alt=\""._("Change")."\"><img src=\"./images/edit.png\" border=\"0\" /></a>";
				   			} elseif( $rightAllowed == "delete" ) {
				   				$action					= "<a href=\"workOnAccessRight.php?id=".$entry['id']."&task=change\" title=\""._("Change")."\" alt=\""._("Change")."\"><img src=\"./images/edit.png\" border=\"0\" /></a>     <a href=\"deleteAccessRight.php?id=".htmlentities($entry['id'])."&task=delete\" title=\""._("Delete")."\" alt=\""._("Delete")."\"><img src=\"./images/edittrash.png\" border=\"0\" /></a>";
				   			} elseif( $_SESSION['svn_sessid']['admin'] == "p" ) {
				   				$action					= "<a href=\"workOnAccessRight.php?id=".$entry['id']."&task=change\" title=\""._("Change")."\" alt=\""._("Change")."\"><img src=\"./images/edit.png\" border=\"0\" /></a>";
				   				$action					= "<a href=\"workOnAccessRight.php?id=".$entry['id']."&task=change\" title=\""._("Change")."\" alt=\""._("Change")."\"><img src=\"./images/edit.png\" border=\"0\" /></a>     <a href=\"deleteAccessRight.php?id=".htmlentities($entry['id'])."&task=delete\" title=\""._("Delete")."\" alt=\""._("Delete")."\"><img src=\"./images/edittrash.png\" border=\"0\" /></a>";
				   			}
				   			
				   			print "\t\t\t\t\t<tr valign=\"top\">\n";
				   			print "\t\t\t\t\t\t<td><input type=\"checkbox\" name=\"".$field."\" /></td>\n";
				   			print "\t\t\t\t\t\t<td>".$entry['svnmodule']."</td>\n";
				   			print "\t\t\t\t\t\t<td align=\"center\">".$entry['access_right']."</td>\n";
				   			print "\t\t\t\t\t\t<td>".$entry['username']."</td>\n";
				   			print "\t\t\t\t\t\t<td>".$entry['groupname']."</td>\n";
				   			print "\t\t\t\t\t\t<td align=\"center\">".$validfrom."</td>\n";
				   			print "\t\t\t\t\t\t<td align=\"center\">".$validuntil."</td>\n";
				   			print "\t\t\t\t\t\t<td>".$entry['reponame'].":".$entry['path']."</td>\n";
				   			print "\t\t\t\t\t\t<td> </td>\n";
				   			print "\t\t\t\t\t\t<td nowrap>".$action."</td>\n";
				   			print "\t\t\t\t\t\t<td> </td>\n";
				   			print "\t\t\t\t\t</tr>\n";
				   			
				   			$_SESSION['svn_sessid']['mark'][$i]		= $entry['id'];
				   			
				   			$i++;
				   		}
				   		
				   		$_SESSION['svn_sessid']['max_mark'] = $i - 1;
				   	?>
				   	<tr>
				      <td colspan="11">&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="11" class="hlp_center">
				      	<input type="image" name="fSubmit_new" src="./images/edit_add.png" value="<?php print _("New access right"); ?>"  title="<?php print _("New access right"); ?>" />&nbsp;&nbsp;&nbsp;
				      	<input type="image" name="fSubmit_delete" src="./images/delete_all.png" value="<?php print _("Delete selected"); ?>"  title="<?php print _("Delete selected"); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				      	<input type="image" name="fSubmit_back" src="./images/button_cancel.png" value="<?php print _("Back"); ?>" title="<?php print _("Back"); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				      	<?php
				      		if( $tPrevDisabled != "disabled" ) {
				      			
				      			print "\t\t\t\t\t\t<input type='image' name='fSubmit_f' src='./images/first.png' value='"._("<<")."' /> \n";
				      			print "\t\t\t\t\t\t<input type='image' name='fSubmit_p' src='./images/previous.png' value='"._("<")."' />          \n";

				      		} else {
				      		
				      			print "\t\t\t\t\t\t<img src='./images/clear.gif' width='24' height='24'> \n";
				      			print "\t\t\t\t\t\t<img src='./images/clear.gif' width='24' height='24'>          \n";
				      		}
				      		
				      		if( $tNextDisabled != "disabled" ) {
				      		
				      			print "\t\t\t\t\t\t<input type='image' name='fSubmit_n' src='./images/next.png' value='"._(">")."' /> \n";
				      			print "\t\t\t\t\t\t<input type='image' name='fSubmit_l' src='./images/last.png' value='"._(">>")."' />\n";

				      		} else {
				      		
				      			print "\t\t\t\t\t\t<img src='./images/clear.gif' width='24' height='24'> \n";
				      			print "\t\t\t\t\t\t<img src='./images/clear.gif' width='24' height='24'>\n";
				      		}
				      	?>
				      </td>
				   	</tr>
				   	<tr>
				      <td colspan="11" class="standout">
				      	<?php print $tMessage; ?>
				      </td>
				   	</tr>
				</table>
			</form>
		</div>