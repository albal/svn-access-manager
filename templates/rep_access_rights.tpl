		<div id="edit_form">
			<form action="rep_access_rights.php" name="rep_access_rights" method="post">
				<table>
				   	<tr>
				      <td colspan="8"><h3><?php print sprintf( _("List of access rights for %s"), $_SESSION['svn_sessid']['date'] ); ?></h3></td>
				   	</tr>
				   	<tr>
				      <td colspan="8">&nbsp;</td>
				   	</tr>
				   	<tr class="theader">
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
				   			
				   			print "\t\t\t\t\t<tr valign=\"top\">\n";
				   			print "\t\t\t\t\t\t<td>".$entry['svnmodule']."</td>\n";
				   			print "\t\t\t\t\t\t<td align=\"center\">".$entry['access_right']."</td>\n";
				   			print "\t\t\t\t\t\t<td>".$entry['username']."</td>\n";
				   			print "\t\t\t\t\t\t<td>".$entry['groupname']."</td>\n";
				   			print "\t\t\t\t\t\t<td align=\"center\">".$validfrom."</td>\n";
				   			print "\t\t\t\t\t\t<td align=\"center\">".$validuntil."</td>\n";
				   			print "\t\t\t\t\t\t<td>".$entry['reponame'].":".$entry['path']."</td>\n";
				   			print "\t\t\t\t\t\t<td> </td>\n";
				   			print "\t\t\t\t\t</tr>\n";
				   			
				   			$_SESSION['svn_sessid']['mark'][$i]		= $entry['id'];
				   			
				   			$i++;
				   		}
				   		
				   		$_SESSION['svn_sessid']['max_mark'] = $i - 1;
				   	?>
				   	<tr>
				      <td colspan="8">&nbsp;</td>
				   	</tr>
				   	<tr>
				      <td colspan="8" class="hlp_center">
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
				      <td colspan="8" class="standout">
				      	<?php print $tMessage; ?>
				      </td>
				   	</tr>
				</table>
			</form>
		</div>