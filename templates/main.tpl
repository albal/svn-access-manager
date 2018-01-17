		
			<table>
					<tr>
						<td colspan='15' align="left"><h3><?php print _("General functions"); ?></h3></td>
					</tr>
					<tr>
						<td><a href="general.php"><img border="0" src="./images/personal.png" alt="User" /></a></td>
						<td width="5">&nbsp;</td>
						<td><a href="general.php"><?php print _("General"); ?></a></td>
						<td width="20">&nbsp;</td>
						<td><a href="password.php"><img border="0" src="./images/password.png" alt="Password" /></a></td>
						<td width="5">&nbsp;</td>
						<td><a href="password.php"><?php print _("Password"); ?></a></td>
						<td width="20">&nbsp;</td>
						<td><a href="password_policy.php"><img border="0" src="./images/info.png" alt="Password Policy" /></a></td>
						<td width="5">&nbsp;</td>
						<td><a href="password_policy.php"><?php print _("Password policy"); ?></a></td>
						<td width="20">&nbsp;</td>
						<td><a href="preferences.php"><img border="0" src="./images/macros.png" alt="Preferences" /></a></td>
						<td width="5">&nbsp;</td>
						<td><a href="preferences.php"><?php print _("Preferences"); ?></a></td>
					</tr>
					<?php
					    $tUsername                  = isset($_SESSION[SVNSESSID]['username']) ? $_SESSION[SVNSESSID]['username'] : 'undefined';
					    $tAdmin                     = isset($_SESSION[SVNSESSID]['admin']) ? $_SESSION[SVNSESSID]['admin'] : 'n';
 						$dbh						= db_connect();
						$rightUserAdmin				= db_check_acl( $tUsername, 'User admin', $dbh );
						$rightGroupAdmin			= db_check_acl( $tUsername, 'Group admin', $dbh );
						$rightProjectAdmin			= db_check_acl( $tUsername, 'Project admin', $dbh );
						$rightRepositoryAdmin		= db_check_acl( $tUsername, 'Repository admin', $dbh );
						$rightAccessRightAdmin		= db_check_acl( $tUsername, 'Access rights admin', $dbh );
						$rightCreateFiles			= db_check_acl( $tUsername, 'Create files', $dbh );
						$rightReports				= db_check_acl( $tUsername, 'Reports', $dbh );
						$tGroupsAllowed				= db_check_group_acl( $tUsername, $dbh );
						$count						= 0;
	
						if( ($tAdmin == "p" ) or
						    ($rightUserAdmin != "none") or
	    					($rightGroupAdmin != "none") or 
	    					($rightProjectAdmin != "none") or
	    					($rightRepositoryAdmin != "none") or
	    					($rightAccessRightAdmin != "none") or
	    					(count($tGroupsAllowed) > 0) or
	    					($rightCreateFiles != "none") ) 
						{
						
							print "\t\t\t\t\t<tr>\n";
							print "\t\t\t\t\t\t<td colspan='15'>&nbsp;</td>\n";
							print "\t\t\t\t\t</tr>\n";
							print "\t\t\t\t\t<tr>\n";
							print "\t\t\t\t\t\t<td colspan='15'><hr /></td>\n";
							print "\t\t\t\t\t</tr>\n";
							print "\t\t\t\t\t<tr>\n";
							print "\t\t\t\t\t\t<td colspan='15'>&nbsp;</td>\n";
							print "\t\t\t\t\t</tr>\n";
							print "\t\t\t\t\t<tr>\n";
							print "\t\t\t\t\t\t<td colspan='15' align=\"left\"><h3>"._("Administration")."</h3></td>\n";
							print "\t\t\t\t\t</tr>\n";
							print "\t\t\t\t\t<tr>\n";
							
						}
						
						if( $rightUserAdmin != "none" ) {
						
							print "\t\t\t\t\t\t<td><a href=\"list_users.php\"><img border=\"0\" src=\"./images/user.png\" alt=\"User\" /></a></td>\n";
							print "\t\t\t\t\t\t<td width=\"5\"> </td>\n";
							print "\t\t\t\t\t\t<td><a href=\"list_users.php\">"._("Users")."</a></td>";
							print "\t\t\t\t\t\t<td width=\"20\"> </td>\n";
							$count++;
							
						}
						
						if( ($rightGroupAdmin != "none") or (count($tGroupsAllowed) > 0) ) {
		
							print "\t\t\t\t\t\t<td><a href=\"list_groups.php\"><img border=\"0\" src=\"./images/group.png\" alt=\"Group\" /></a></td>\n";
							print "\t\t\t\t\t\t<td width=\"5\"> </td>\n";
							print "\t\t\t\t\t\t<td><a href=\"list_groups.php\">"._("Groups")."</a></td>\n";
							print "\t\t\t\t\t\t<td width=\"20\"> </td>\n";
							$count++;
		
						}
						
						if( $rightProjectAdmin != "none" ) {
							
							print "\t\t\t\t\t\t<td><a href=\"list_projects.php\"><img border=\"0\" src=\"./images/project.png\" alt=\"Projects\" /></a></td>\n";
							print "\t\t\t\t\t\t<td width=\"5\"> </td>\n";
							print "\t\t\t\t\t\t<td><a href=\"list_projects.php\">"._("Projects")."</a></td>";
							print "\t\t\t\t\t\t<td width=\"20\"> </td>\n";
							$count++;
							
						}
						
						if( $rightRepositoryAdmin != "none" ) {
							
							print "\t\t\t\t\t\t<td><a href=\"list_repos.php\"><img border=\"0\" src=\"./images/service.png\" alt=\"Repositories\" /></a></td>\n";
							print "\t\t\t\t\t\t<td width=\"5\"> </td>\n";
							print "\t\t\t\t\t\t<td><a href=\"list_repos.php\">"._("Repositories")."</a></td>\n";
							print "\t\t\t\t\t\t<td width=\"20\"> </td>\n";
							$count++;
							
						}
						
						if( ($rightAccessRightAdmin != "none") or ($tAdmin == "p" ) ) {
							
							if( $count >= 4 ) {
								
								print "\t\t\t\t\t</tr>\n";
								print "\t\t\t\t\t<tr>\n";
								$count 			= 0;
							}
							
							print "\t\t\t\t\t\t<td><a href=\"list_access_rights.php\"><img border=\"0\" src=\"./images/password.png\" alt=\"Repository access rights\" /></a></td>\n";
							print "\t\t\t\t\t\t<td width=\"5\"> </td>\n";
							print "\t\t\t\t\t\t<td><a href=\"list_access_rights.php\">"._("Repository access rights")."</a></td>\n";
							print "\t\t\t\t\t\t<td width=\"20\"> </td>\n";
							$count++;
							
						}
						
						if( $rightCreateFiles != "none" ) {
							
							if( $count >= 4 ) {
								
								print "\t\t\t\t\t</tr>\n";
								print "\t\t\t\t\t<tr>\n";
								$count 			= 0;
							}
							
							print "\t\t\t\t\t\t<td><a href=\"createAccessFiles.php\"><img border=\"0\" src=\"./images/password.png\" alt=\"Create access files\" /></a></td>\n";
							print "\t\t\t\t\t\t<td width=\"5\"> </td>\n";
							print "\t\t\t\t\t\t<td><a href=\"createAccessFiles.php\">"._("Create access files")."</a></td>\n";
							print "\t\t\t\t\t\t<td width=\"20\"> </td>\n";
							$count++;
						}
						
						if( $rightGroupAdmin != "none" ) {
		
							print "\t\t\t\t\t\t<td><a href=\"list_group_adminss.php\"><img border=\"0\" src=\"./images/group.png\" alt=\"Group\" /></a></td>\n";
							print "\t\t\t\t\t\t<td width=\"5\"> </td>\n";
							print "\t\t\t\t\t\t<td><a href=\"list_group_admins.php\">"._("Group administrators")."</a></td>\n";
							print "\t\t\t\t\t\t<td width=\"20\"> </td>\n";
							$count++;
		
						}
						
						
						if( ($tAdmin == "p" ) or
	    					($rightUserAdmin != "none") or
	    					($rightGroupAdmin != "none") or 
						    ($rightProjectAdmin != "none") or
	    					($rightRepositoryAdmin != "none") or
	    					($rightAccessRightAdmin != "none") or
	    					(count($tGroupsAllowed) > 0) or
	    					($rightCreateFiles != "none") ) 
						{
	    		
							print "\t\t\t\t\t</tr>\n";
		
						}
						
						if( $rightReports != "none" ) {
							
							print "\t\t\t\t\t<tr>\n";
							print "\t\t\t\t\t\t<td colspan='15'>&nbsp;</td>\n";
							print "\t\t\t\t\t</tr>\n";
							print "\t\t\t\t\t<tr>\n";
							print "\t\t\t\t\t\t<td colspan='15'><hr /></td>\n";
							print "\t\t\t\t\t</tr>\n";
							print "\t\t\t\t\t<tr>\n";
							print "\t\t\t\t\t\t<td colspan='15'>&nbsp;</td>\n";
							print "\t\t\t\t\t</tr>\n";
							print "\t\t\t\t\t<tr>\n";
							print "\t\t\t\t\t\t<td colspan='15' align=\"left\"><h3>"._("Reports")."</h3></td>\n";
							print "\t\t\t\t\t</tr>\n";
							print "\t\t\t\t\t<tr>\n";
							
							print "\t\t\t\t\t\t<td><a href=\"rep_access_rights.php\"><img border=\"0\" src=\"./images/reports.png\" alt=\"Show repository access report\" /></a></td>\n";
							print "\t\t\t\t\t\t<td width=\"5\"> </td>\n";
							print "\t\t\t\t\t\t<td><a href=\"rep_access_rights.php\">"._("Repository access rights")."</a></td>\n";
							print "\t\t\t\t\t\t<td width=\"20\"> </td>\n";
							
							print "\t\t\t\t\t\t<td><a href=\"rep_log.php\"><img border=\"0\" src=\"./images/reports.png\" alt=\"Show log report\" /></a></td>\n";
							print "\t\t\t\t\t\t<td width=\"5\"> </td>\n";
							print "\t\t\t\t\t\t<td><a href=\"rep_log.php\">"._("Logs")."</a></td>\n";
							print "\t\t\t\t\t\t<td width=\"20\"> </td>\n";
							
							print "\t\t\t\t\t\t<td><a href=\"rep_locked_users.php\"><img border=\"0\" src=\"./images/reports.png\" alt=\"Show locked user report\" /></a></td>\n";
							print "\t\t\t\t\t\t<td width=\"5\"> </td>\n";
							print "\t\t\t\t\t\t<td><a href=\"rep_locked_users.php\">"._("Locked users")."</a></td>\n";
							print "\t\t\t\t\t\t<td width=\"20\"> </td>\n";
							
							print "\t\t\t\t\t\t<td><a href=\"rep_granted_user_rights.php\"><img border=\"0\" src=\"./images/reports.png\" alt=\"Show granted user rights report\" /></a></td>\n";
							print "\t\t\t\t\t\t<td width=\"5\"> </td>\n";
							print "\t\t\t\t\t\t<td><a href=\"rep_granted_user_rights.php\">"._("Granted user rights")."</a></td>\n";
							print "\t\t\t\t\t\t<td width=\"20\"> </td>\n";
							
							print "\t\t\t\t\t</tr>\n";
							
							print "\t\t\t\t\t<tr>\n";
							
							print "\t\t\t\t\t\t<td><a href=\"rep_show_user.php\"><img border=\"0\" src=\"./images/reports.png\" alt=\"Show access rights of a user\" /></a></td>\n";
							print "\t\t\t\t\t\t<td width=\"5\"> </td>\n";
							print "\t\t\t\t\t\t<td><a href=\"rep_show_user.php\">"._("Show user access rights")."</a></td>\n";
							print "\t\t\t\t\t\t<td width=\"20\"> </td>\n";
							
							print "\t\t\t\t\t\t<td><a href=\"rep_show_group.php\"><img border=\"0\" src=\"./images/reports.png\" alt=\"Show access rights of a group\" /></a></td>\n";
							print "\t\t\t\t\t\t<td width=\"5\"> </td>\n";
							print "\t\t\t\t\t\t<td><a href=\"rep_show_group.php\">"._("Show group access rights")."</a></td>\n";
							print "\t\t\t\t\t\t<td width=\"20\"> </td>\n";
							
							print "\t\t\t\t\t\t<td> </td>\n";
							print "\t\t\t\t\t\t<td width=\"5\"> </td>\n";
							print "\t\t\t\t\t\t<td> </td>\n";
							print "\t\t\t\t\t\t<td width=\"20\"> </td>\n";
							
							print "\t\t\t\t\t\t<td> </td>\n";
							print "\t\t\t\t\t\t<td width=\"5\"> </td>\n";
							print "\t\t\t\t\t\t<td> </td>\n";
							print "\t\t\t\t\t\t<td width=\"20\"> </td>\n";
							
							print "\t\t\t\t\t</tr>\n";
							
						}

					?>
			</table>
		
