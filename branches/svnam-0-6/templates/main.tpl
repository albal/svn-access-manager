<div>  
    <h3 class="page-header"><?php print  _("General functions"); ?></h3> 
</div>
<div class="row">
  <div class="col-sm-1"><a href="general.php"><img class="img-responsive" src="./images/personal.png" alt="User">&nbsp;<?php print _("General"); ?></a></div>
  <div class="col-sm-1"><a href="password.php"><img class="img-responsive" src="./images/password.png" alt="Password">&nbsp;<?php print _("Password"); ?></a></div>
  <div class="col-sm-1"><a href="password_policy.php"><img class="img-responsive" src="./images/info.png" alt="Password Policy">&nbsp;<?php print _("Password policy"); ?></a></div>
  <div class="col-sm-1"><a href="preferences.php"><img class="img-responsive" src="./images/macros.png" alt="Preferences">&nbsp;<?php print _("Preferences"); ?></a></div>
</div>	
			
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
	
						if( ($tAdmin == "p" ) ||
						    ($rightUserAdmin != "none") || 
	    					($rightGroupAdmin != "none") || 
	    					($rightProjectAdmin != "none") || 
	    					($rightRepositoryAdmin != "none") || 
	    					($rightAccessRightAdmin != "none") || 
	    					(count($tGroupsAllowed) > 0) || 
	    					($rightCreateFiles != "none") ) 
						{
						
	
							print '<h3>'._("Administration").'</h3>';
                            print '<div class="row">';
                            
							
						}
						
						if( $rightUserAdmin != "none" ) {
						
						    print '<div class="col-sm-1"><a href="list_users.php"><img class="img-responsive" src="./images/user.png" alt="User">&nbsp;'._("Users").'</a></div>';
							$count++;
							
						}
						
						if( ($rightGroupAdmin != "none") || (count($tGroupsAllowed) > 0) ) {
		
		                    print '<div class="col-sm-1"><a href="list_groups.php"><img class="img-responsive" src="./images/group.png" alt="Group">&nbsp;'._("Groups").'</a></div>';
							$count++;
		
						}
						
						if( $rightProjectAdmin != "none" ) {
							
							print '<div class="col-sm-1"><a href="list_projects.php"><img class="img-responsive" src="./images/project.png" alt="Projects">&nbsp;'._("Projects").'</a></div>';
							$count++;
							
						}
						
						if( $rightRepositoryAdmin != "none" ) {
							
							print '<div class="col-sm-1"><a href="list_repos.php"><img class="img-responsive" src="./images/service.png" alt="Repositories">&nbsp;'._("Repositories").'</a></div>';
							$count++;
							
						}
						
						if( ($rightAccessRightAdmin != "none") || ($tAdmin == "p" ) ) {
						
							print '<div class="col-sm-1"><a href="list_access_rights.php"><img class="img-responsive" src="./images/password.png" alt="Repository access rights">&nbsp;'._("Repository access rights").'</a></div>';
							$count++;
							
						}
						
						if( $rightCreateFiles != "none" ) {
							
							print '<div class="col-sm-1"><a href="createAccessFiles.php"><img class="img-responsive" src="./images/password.png" alt="Create access files">&nbsp;'._("Create access files").'</a></div>';
							$count++;
						}
						
						if( $rightGroupAdmin != "none" ) {
		
		                    print '<div class="col-sm-1"><a href="list_group_admins.php"><img class="img-responsive" src="./images/group.png" alt="Group administrators">&nbsp;'._("Group administrators").'</a></div>';
							$count++;
		
						}
						
						
						if( ($tAdmin == "p" ) || 
	    					($rightUserAdmin != "none") || 
	    					($rightGroupAdmin != "none") || 
						    ($rightProjectAdmin != "none") || 
	    					($rightRepositoryAdmin != "none") || 
	    					($rightAccessRightAdmin != "none") || 
	    					(count($tGroupsAllowed) > 0) || 
	    					($rightCreateFiles != "none") ) 
						{
	    		
							print '</div>';
		
						}
						
						if( $rightReports != "none" ) {
						
						    print '<h3>'._("Reports").'</h3>';
                            print '<div class="row">';
                            print '<div class="col-sm-1"><a href="rep_access_rights.php"><img class="img-responsive" src="./images/reports.png" alt="GShow repository access report">&nbsp;'._("Repository access rights").'</a></div>';
                            print '<div class="col-sm-1"><a href="rep_log.php"><img class="img-responsive" src="./images/reports.png" alt="Group administrators">&nbsp;'._("Logs").'</a></div>';
                            print '<div class="col-sm-1"><a href="rep_locked_users.php"><img class="img-responsive" src="./images/reports.png" alt="Group administrators">&nbsp;'._("Locked users").'</a></div>';
                            print '<div class="col-sm-1"><a href="rep_granted_user_rights.php"><img class="img-responsive" src="./images/reports.png" alt="Group administrators">&nbsp;'._("Granted user rights").'</a></div>';
							print '<div class="col-sm-1"><a href="rep_show_user.php"><img class="img-responsive" src="./images/reports.png" alt="Group administrators">&nbsp;'._("Show user access rights").'</a></div>';
							print '<div class="col-sm-1"><a href="rep_show_group.php"><img class="img-responsive" src="./images/reports.png" alt="Group administrators">&nbsp;'._("Show group access rights").'</a></div>';
							print '</div>';
							
						}

					?>
			</table>
		

