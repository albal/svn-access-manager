<?php

/*
    SVN Access Manager - a subversion access rights management tool
    Copyright (C) 2008 Thomas Krieger <tom@svn-access-manager.org>

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


if ( file_exists ( realpath ( "./config/config.inc.php" ) ) ) {
	require( "./config/config.inc.php" );
} elseif( file_exists ( realpath ( "../config/config.inc.php" ) ) ) {
	require( "../config/config.inc.php" );
} elseif( file_exists( "/etc/svn-access-manager/config.inc.php" ) ) {
	require( "/etc/svn-access-manager/config.inc.php" );
} else {
	die( "can't load config.inc.php. Check your installation!\n" );
}

$installBase					= isset( $CONF['install_base'] ) ? $CONF['install_base'] : "";

require ("$installBase/include/variables.inc.php");
require_once ("$installBase/include/functions.inc.php");
require_once ("$installBase/include/db-functions-adodb.inc.php");
include_once ("$installBase/include/output.inc.php");

$error                                                                  = 0;    
$tErrorClass                                                    		= "";   
$tMessage                                                               = "";
$callback                                                               = isset( $_GET['callback'] )            ? ( $_GET['callback'] )             : "";
$maxRows                                                                = isset( $_GET['maxRows'] )             ? ( $_GET['maxRows'] )              : 10;
$filter                                                                 = isset( $_GET['name_startsWith'] )     ? ( $_GET['name_startsWith'] )  	: "";
$db                                                                     = isset( $_GET['db'] )              	? ( $_GET['db'] )                   : "";
$userid																	= isset( $_GET['userid'] )				? ( $_GET['userid'] )				: "";
$user																	= isset( $_GET['user'] )				? ( $_GET['user'] )					: "";
$group																	= isset( $_GET['group'] )				? ( $_GET['group'] )				: "";
$project																= isset( $_GET['project'] )				? ( $_GET['project'] )				: "";
$admin																	= isset( $_GET['admin'] )				? ( $_GET['admin'] )				: "";
$allowed																= isset( $_GET['allowed'] )				? ( $_GET['allowed'] )				: "";
$tArray                                                                 = array();

list($ret, $SESSID_USERNAME)            								= check_session_status();
if( $ret == 0 ) {
    	
    $tArray[]           	                                            = array( "name" => "Session expired!" );
	$data             	    	                                        = json_encode($tArray);
	
} elseif( strtolower($db) == "people" ) {

	$dbh																= db_connect();
	$schema																= db_determine_schema();
	$query																= "SELECT id, name, givenname " .
																		  "  FROM ".$schema."svnusers " .
																		  " WHERE ((userid like '%$filter%') ".
																		  "    OR  (CONCAT(givenname, ' ', name) like '%$filter%') ".
																		  "    OR  (CONCAT(name, ' ', givenname) like '%$filter%') ".
																		  "    OR  (name like '%$filter%') ".
																		  "    OR  (givenname like '%$filter%')) ".
																		  "   AND (deleted = '00000000000000') ".
																		  "ORDER BY name ASC, givenname ASC";
	$result																= db_query( $query, $dbh );
	while( $row = db_assoc( $result['result'] ) ) {
		
		$data															= array();
		if( $row['givenname'] == "" ) {
			$data['name']												= $row['name'];
		} else {
			$data['name']												= $row['givenname']." ".$row['name'];
		}
		$data['id']														= $row['id'];
		$tArray[]														= $data;
		
	}
	
	db_disconnect( $dbh );
	
} elseif( strtolower($db) == "groups" ) {
	
	$dbh																= db_connect();
	$rightAllowed														= db_check_acl( $SESSID_USERNAME, "Group admin", $dbh );
	$tGroupsAllowed														= array();
	$schema																= db_determine_schema();
	
	if( $rightAllowed == "none" ) {
	
		$tGroupsAllowed													= db_check_group_acl( $_SESSION['svn_sessid']['username'], $dbh );
		if(count($tGroupsAllowed) == 0 ) {
			$groupAdmin													= 2;
		} else {
			$groupAdmin													= 1;
		}
		
	} else {
		$groupAdmin														= 2;
	}
	
	if( $groupAdmin == 1 ) {
		
		$grouplist														= "";
		
		foreach( $tGroupsAllowed as $groupid => $right ) {

			if( $grouplist == "" ) {
				$grouplist												= "'".$groupid."'";
			} else {
				$grouplist 												.= ",'".$groupid."'";
			}
		}
		
		$grouplist														= "(".$grouplist.")";
		
		$query															= "SELECT  * " .
																		  "  FROM ".$schema."svngroups " .
																		  " WHERE (deleted = '00000000000000') " .
																		  "   AND ((groupname like '%$filter%') ".
																		  "    OR (description like '%$filter%')) ".
																		  "   AND (id in $grouplist) " .
																		  "ORDER BY groupname ASC ";
											
	} else {
		
		$query															= "SELECT id, groupname " .
																		  "  FROM ".$schema."svngroups " .
																		  " WHERE ((groupname like '%$filter%') ".
																		  "    OR (description like '%$filter%')) ".
																		  "   AND (deleted = '00000000000000') ".
																		  "ORDER BY groupname ASC";
	}
	
	$result																= db_query( $query, $dbh );
	while( $row = db_assoc( $result['result'] ) ) {
		
		$data															= array();
		$data['name']													= $row['groupname'];
		$data['id']														= $row['id'];
		$tArray[]														= $data;
		
	}
	
	db_disconnect( $dbh );
	
} elseif( strtolower($db) == "repos" ) {

	$dbh																= db_connect();
	$schema																= db_determine_schema();
	$query																= "SELECT id, reponame " .
																		  "  FROM ".$schema."svnrepos " .
																		  " WHERE ((repouser like '%$filter%') ".
																		  "    OR (reponame like '%$filter%')) ".
																		  "   AND (deleted = '00000000000000') ".
																		  "ORDER BY reponame ASC";
	$result																= db_query( $query, $dbh );
	while( $row = db_assoc( $result['result'] ) ) {
		
		$data															= array();
		$data['name']													= $row['reponame'];
		$data['id']														= $row['id'];
		$tArray[]														= $data;
		
	}
	
	db_disconnect( $dbh );
		
} elseif( strtolower($db) == "projects" ) {
	
	$dbh																= db_connect();
	$schema																= db_determine_schema();
	$query																= "SELECT * ".
    											  						  "  FROM ".$schema."svnprojects ".
    											  						  " WHERE (svnmodule like '%$filter%') ".
    											  						  "   AND (deleted = '00000000000000') ".
    											  						  "ORDER BY svnmodule ASC";
  	$result																= db_query( $query, $dbh );
	while( $row = db_assoc( $result['result'] ) ) {
		
		$data															= array();
		$data['name']													= $row['svnmodule'];
		$data['id']														= $row['id'];
		$tArray[]														= $data;
		
	}
	
	db_disconnect( $dbh );
	
} elseif( strtolower($db) == "groupadmin" ) {
	
	$dbh																= db_connect();
	$schema																= db_determine_schema();
	$query																= "SELECT svnusers.name, svnusers.givenname, svn_groups_responsible.id, svnusers.userid ".
    											  						  "  FROM ".$schema."svn_groups_responsible,".$schema."svnusers, ".$schema."svngroups ".
    											  						  " WHERE (svn_groups_responsible.user_id = svnusers.id) " .
    											  						  "   AND (svnusers.deleted = '00000000000000') ".
    											  						  "   AND (svn_groups_responsible.deleted = '00000000000000') ".
    											  						  "   AND (svn_groups_responsible.group_id = svngroups.id) ".
    											  						  "   AND (svngroups.deleted = '00000000000000') ". 
    											  						  "   AND ((svnusers.name like '%$filter%') ".
    											  						  "    OR  (svnusers.givenname like '%$filter%') ".
    											  						  "    OR  (svnusers.userid like '%$filter%') ".
    											  						  "    OR  (svngroups.groupname like '%$filter%') ".
    											  						  "    OR  (svngroups.description like '%$filter%')) ".    											  						  
    											  						  "ORDER BY svnusers.name ASC, svnusers.givenname ASC";
  	$result																= db_query( $query, $dbh );
	while( $row = db_assoc( $result['result'] ) ) {
		
		$data															= array();
		if( $row['givenname'] != "" ) {
			$data['name']												= $row['givenname']." ".$row['name'];
		} else {
			$data['name']												= $row['name'];	
		}
		$data['id']														= $row['id'];
		$tArray[]														= $data;
		
	}
	
	db_disconnect( $dbh );
	
} elseif( strtolower($db) == "accessright" ) {
	
	error_log( "accessright: $userid" );
	$dbh																= db_connect();	
	$schema																= db_determine_schema();
	$tProjectIds														= "";
	$query																= "SELECT * " .
								  					      				  "  FROM ".$schema."svn_projects_responsible " .
	  					    							  				  " WHERE (deleted = '00000000000000')";
  	$result																= db_query( $query, $dbh );
  	while( $row = db_assoc( $result['result'] ) ) {
  		
  		if( $tProjectIds == "" ) {
  			
  			$tProjectIds 												= $row['project_id'];
  			
  		} else {
  			
  			$tProjectIds												= $tProjectIds.",".$row['project_id'];
  			
  		}
  		
  	}
	error_log("Project Ids: $tProjectIds");
	if( $tProjectIds != "" ) {
		
		$query															= "SELECT svn_access_rights.id AS rid, svnmodule, modulepath, svnrepos." .
																		  "       reponame, valid_from, valid_until, path, access_right, recursive," .
																		  "       svn_access_rights.user_id, svn_access_rights.group_id, repopath " .
																		  "  FROM ".$schema."svn_access_rights, ".$schema."svnprojects, ".$schema."svnrepos " .
																		  " WHERE (svnprojects.id = svn_access_rights.project_id) " .
																		  "   AND (svnprojects.id IN (".$tProjectIds."))" .
																		  "   AND (svnprojects.repo_id = svnrepos.id) " .
																		  "   AND (svn_access_rights.deleted = '00000000000000') " .
																		  "   AND ((svnmodule like '%$filter%') ".
																		  "    OR  (modulepath like '%$filter%') ".
																		  "    OR  (svnrepos.reponame like '%$filter%') ".
																		  "    OR  (path like '%$filter%') ".
																		  "    OR  (svnprojects.description like '%$filter%')) ".
																		  "ORDER BY svnrepos.reponame, svn_access_rights.path ";
		error_log( $query );
		$result															= db_query( $query, $dbh );
		
		while( $row = db_assoc( $result['result'] ) ) {
			
			$data														= array();
			$data['name']												= $row['repopath']."|".$row['path']."|".$row['reponame'];
			$data['id']													= $row['rid'];
			$tArray[]													= $data;
			
		}
	
	}
	
	db_disconnect( $dbh );
	
} elseif( strtolower($db) == "accessrighttable" ) {
	
	error_log( "user = $user - group = $group - project = $project" );
	$dbh																= db_connect();	
	$schema																= db_determine_schema();
	$tProjectIds														= "";
	
	if( $userid != -1 ) {
		$id									= db_getIdByUserid( $userid, $dbh );
		$tProjectIds						= "";
		$query								= "SELECT * " .
	  					      				  "  FROM ".$schema."svn_projects_responsible " .
	  					      				  " WHERE (user_id = $id) " .
	  					      				  "   AND (deleted = '00000000000000')";
	} else {
		
		$tProjectIds						= "";
		$query								= "SELECT * " .
	  					      				  "  FROM ".$schema."svn_projects_responsible " .
	  					      				  " WHERE (deleted = '00000000000000')";
	  					      				  
	}
	
  	$result									= db_query( $query, $dbh );
  	while( $row = db_assoc( $result['result'] ) ) {
  		
  		if( $tProjectIds == "" ) {
  			
  			$tProjectIds 					= $row['project_id'];
  			
  		} else {
  			
  			$tProjectIds					= $tProjectIds.",".$row['project_id'];
  			
  		}
  		
  	}
  	
  	error_log("Project Ids: $tProjectIds");
  	
  	print "<thead>\n";
	print "	<tr>\n";
	print "	<th align='center' class='ui-table-default'>\n";
	print "		<strong>"._('M')."</strong>\n";
	print "	</th>\n";
	print "	<th class='ui-table-default'>\n";
	print "		<strong>"._('Project')."</strong>\n";
	print "	</th>\n";
	print "	<th class='ui-table-default'>\n";
	print "		<strong>"._('Rights')."</strong>\n";
	print "	</th>\n";
	print "	<th align='center' class='ui-table-default'>\n";
	print "		<strong>"._('User')." </strong>\n";
	print "	</th>\n";
	print "	<th align='center' class='ui-table-default'>\n";
	print "		<strong>"._('Group')."</strong>\n";
	print "	</th>\n";
	print "	<th align='center' class='ui-table-default'>\n";
	print "		<strong>"._('Valid from')."</strong>\n";
	print "	</th>\n";
	print "	<th align='center' class='ui-table-default'>\n";
	print "		<strong>"._('Valid until')."</strong>\n";
	print "	</th>\n";
	print "	<th class='ui-table-default'>\n";
	print "		<strong>"._('Repository:Directory')."</strong>\n";
	print "	</th>\n";
	print "	<th class='ui-table-deactivate'>\n";
	print "		<strong>"._('Action')."</strong>\n";
	print "	</th>\n";
	print "</tr>\n";
	print "<tr class='ui-table-deactivate'>\n";
	print "	<td class='ui-table-deactivate'>\n";
	print "		<strong>"._('Filter:')."</strong>\n";
	print "	</td>\n";
	print "	<td class='ui-table-deactivate'>\n";
	print "		<input id='filterproject' class='large' type='text' name='fSearchProject' value='' title='"._('Search access rights by project.')."' />\n";
	print "	</td>\n";
	print "	<td class='ui-table-deactivate'>\n";
	print "		&nbsp;\n";
	print "	</td>\n";
	print "	<td class='ui-table-deactivate'>\n";
	print "		<input id='filteruser' class='large' type='text' name='fSearchUser' value='' title='"._('Search access rights by user.')."' />\n";
	print "	</td>\n";
	print "	<td class='ui-table-deactivate'>\n";
	print "		<input id='filtergroup' class='large' type='text' name='fSearchGroup' value='' title='"._('Search access rights by group.')."' />\n";
	print "	</td>\n";
	print "	<td class='ui-table-deactivate'>\n";
	print "		<input type='image' name='fSubmit_clear' src='./images/button_cancel.png' value='"._("Clear filter")." title='"._("Clear filter")."' />\n";
	print "	</td>\n";
	print "	<td class='ui-table-deactivate'>\n";
	print "		&nbsp;\n";
	print "	</td>\n";
	print "	<td class='ui-table-deactivate'>\n";
	print "		&nbsp;\n";
	print "	</td>\n";
	print "	<td class='ui-table-deactivate'>\n";
	print "		&nbsp;\n";
	print "	</td>\n";
	print "</tr>\n";
	print "</thead>\n";
	print "<tbody id='tbody'>\n";

  	
  	$curdate								= date( "Ymd" );
  	$query									= "SELECT svn_access_rights.id AS rid, svnmodule, modulepath, svnrepos." .
											  "       reponame, valid_from, valid_until, path, access_right, recursive," .
											  "       svn_access_rights.user_id, svn_access_rights.group_id, repopath " .
											  "  FROM ".$schema."svn_access_rights, ".$schema."svnprojects, ".$schema."svnrepos " .
											  " WHERE (svnprojects.id = svn_access_rights.project_id) " .
											  "   AND (svnprojects.id IN (".$tProjectIds."))" .
											  "   AND (svnprojects.repo_id = svnrepos.id) " .
											  "   AND (svn_access_rights.deleted = '00000000000000') ";
	if( $project != "" ) {
		$query								= $query."   AND ((svnprojects.svnmodule like '%$project%') ".
												     "    OR  (svnprojects.description like '%$projects%')) ";
	}											  
	$query									= $query."ORDER BY svnrepos.reponame, svn_access_rights.path ";
	error_log( $query );
	$result									= db_query( $query, $dbh );
	$i 										= 0;

	while( $row = db_assoc( $result['result'] ) ) {
	
		$use								= true;
		$name								= "";
		if( ( $row['user_id'] != 0 )  ) {
			list( $use, $name)				= checkData( 'user', $row['user_id'], $user, $dbh );
		}
		if( ( $row['group_id'] != 0 )  ) {
			list( $use, $name )				= checkdata( 'group', $row['group_id'], $group, $dbh );
		}
		
		if( $use ) { 
			$row['username']				= $name;
			$id								= $row['rid'];
			$validfrom						= splitValidDate( $row['valid_from'] );
			$validuntil						= splitValidDate( $row['valid_until'] );
			$field							= "fDelete".$i;
			$action							= "";
			
			if( $allowed == "edit" ) {
				$url						= htmlentities("workOnAccessRight.php?id=".$row['rid']."&task=change");
				$action						= "<a href=\"$url\" title=\""._("Change")."\" alt=\""._("Change")."\"><img src=\"./images/edit.png\" border=\"0\" /></a>";
			} elseif( $allowed == "delete" ) {
				$url						= htmlentities("workOnAccessRight.php?id=".$row['rid']."&task=change");
				$action						= "<a href=\"$url\" title=\""._("Change")."\" alt=\""._("Change")."\"><img src=\"./images/edit.png\" border=\"0\" /></a>     <a href=\"deleteAccessRight.php?id=".htmlentities($row['rid'])."&task=delete\" title=\""._("Delete")."\" alt=\""._("Delete")."\"><img src=\"./images/edittrash.png\" border=\"0\" /></a>";
			} elseif( $admin == "p" ) {
				$url						= htmlentities("workOnAccessRight.php?id=".$row['rid']."&task=change");
				$action						= "<a href=\"workOnAccessRight.php?id=".$row['rid']."&task=change\" title=\""._("Change")."\" alt=\""._("Change")."\"><img src=\"./images/edit.png\" border=\"0\" /></a>";
				$action						= "<a href=\"$url\" title=\""._("Change")."\" alt=\""._("Change")."\"><img src=\"./images/edit.png\" border=\"0\" /></a>     <a href=\"deleteAccessRight.php?id=".htmlentities($row['rid'])."&task=delete\" title=\""._("Delete")."\" alt=\""._("Delete")."\"><img src=\"./images/edittrash.png\" border=\"0\" /></a>";
			}
			
			print "\t\t\t\t\t<tr valign=\"top\">\n";
			print "\t\t\t\t\t\t<td><input type=\"checkbox\" name=\"".$field."\" value=\"".$row['rid']."\"/></td>\n";
			print "\t\t\t\t\t\t<td>".$row['svnmodule']."</td>\n";
			print "\t\t\t\t\t\t<td align=\"center\">".$row['access_right']."</td>\n";
			print "\t\t\t\t\t\t<td>".$row['username']."</td>\n";
			print "\t\t\t\t\t\t<td> </td>\n";
			print "\t\t\t\t\t\t<td>".$validfrom."</td>\n";
			print "\t\t\t\t\t\t<td>".$validuntil."</td>\n";
			print "\t\t\t\t\t\t<td>".$row['reponame'].":".$row['path']."</td>\n";
			print "\t\t\t\t\t\t<td nowrap>".$action."</td>\n";
			print "\t\t\t\t\t</tr>\n";
			
			$i++;
		}			   		
	}
	
	print "</tbody>";
	
	db_disconnect( $dbh );
}

if( strtolower($db) != "accessrighttable" ) {

	$data                                                                   = json_encode($tArray);
	print $callback."(".$data.");";	
}

function checkData( $type, $id, $value, $dbh ) {
	
	global $CONF;
	
	$schema																= db_determine_schema();
	if( $type == "group" ) {
		
		$query															= "SELECT groupname, description ".
																		  "  FROM svngroups ".
																		  " WHERE (id = $id)";
																		  
	} elseif( $type == "user" ) {
		
		$query															= "SELECT name, givenname, userid ".
																		  "  FROM svnusers ".
																		  " WHERE (id = $id)";
																		  
	} else {
		
		return( array(false, '') );
		
	}
	
	$result																= db_query( $query, $dbh );
	if( $result['rows'] == 1 ) {
		$row															= db_assoc( $result['result'] );
		
		if( $type == "group" ) {
			
			if( 
				( preg_match( "/$value/", $row['groupname']) ) 	or
				( preg_match( "/$value/", $row['description']) )
			) {
				return( array(true, '') );
			} else {
				
				return( array(false, '') );
			}
			
		} elseif( $type == "user" ) {
			
			if( 
				( preg_match( "/$value/", $row['name'] ) ) 		or
				( preg_match( "/$value/", $row['givenname'] ) )	or
				( preg_match( "/$value/", $row['userid'] ) )
			) {
				if( $row['givenname'] != "" ) {
					$name												= $row['givenname']." ".$row['name'];
				} else {
					$name												= $row['name'];
				}
				return( array(true, $name) );
			} else {
				return( array(false, '') );
			}
		}
		
	} else {
		return( array(false, '') );
	}
}
?>
