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
 
function createAuthUserFile( $dbh ) {
	
	global $CONF;
	
	$retcode 							= 0;
	$tMessage							= "";
	$dir								= dirname( $CONF['AuthUserFile'] );
	$entropy							= create_salt();
	$tempfile							= $dir."/authtemp_".$entropy;
		
	if( $CONF['createUserFile'] == "YES" ) {
		
		if( db_set_semaphore( 'createauthuserfile', 'sem', $dbh ) ) {
			
			if( $fileHandle	= @fopen( $tempfile, 'w' ) ) {
				
				$query						= "SELECT * " .
											  "  FROM svnusers " .
											  " WHERE (deleted = '0000-00-00 00:00:00') " .
											  "   AND (locked = '0') " .
											  "ORDER BY userid";
				$result						= db_query( $query, $dbh );
				
				while( $row = db_array( $result['result'] ) ) {
					
					if( ! @fwrite( $fileHandle, $row['userid'].":".$row['password']."\n" ) ) {
						
						$retcode 			= 1;
						$tMessage			= _( "Can't write to AuthUser file" );
						db_unset_semaphore( 'createauthuserfile', 'sem', $dbh );
					}
					
				}
				
				@fclose( $fileHandle );
				
				if( $retcode == 0 ) {
					
					if( @copy( $tempfile, $CONF['AuthUserFile'] ) ) {
						
						if( @unlink( $tempfile ) ) {
							
							if( db_unset_semaphore( 'createauthuserfile', 'sem', $dbh ) ) {
								
								$tMessage			= _("Auth user file successfully created!" );
								
							} else {
								
								$retcode			= 1;
								$tMessage			= _("Auth user file created but semaphore could not be released");
								db_unset_semaphore( 'createauthuserfile', 'sem', $dbh );
							}
							
						} else {
							
							$retcode				= 4;
							$tMessage				= sprintf( _("Delete of %s failed!"), $tempfile );
							db_unset_semaphore( 'createauthuserfile', 'sem', $dbh );
						}
						
					} else {
						
						$retcode					= 3;
						$tMessage					= sprintf( _("Copy from %s to %s failed!"), $tempfile, $CONF['AuthUserFile'] );
						db_unset_semaphore( 'createauthuserfile', 'sem', $dbh );
					}
					
				}
				
			} else {
				
				$retcode						= 2;
				$tMessage						= sprintf( _( "Cannot open file %s for writing!" ), $tempfile );
				db_unset_semaphore( 'createauthuserfile', 'sem', $dbh );
			}
			
		} else {
		
			$error								= 1;
			$tMessage							= _("Can't set semaphore, another process is writing Auth User File, try again later");
		}
		
	} else {
		
		$retcode								= 0;
		$tMessage								= _("Create of auth user file not configured!" );
	}
		
	
	$ret										= array();
	$ret['error']								= $retcode;
	$ret['errormsg']							= $tMessage;
	
	return $ret;
}


function createAccessFile( $dbh ) {
	
	global $CONF;
	
	$retcode 							= 0;
	$tMessage							= "";
	$curdate							= strftime( "%Y%m%d" );
	$oldpath							= "";
	
	if( $CONF['createAccessFile'] == "YES" ) {
		
		if( db_set_semaphore( 'createaccessfile', 'sem', $dbh ) ) {
			
			$dir							= dirname( $CONF['SVNAccessFile'] );
			$entropy						= create_salt();
			$tempfile						= $dir."/accesstemp_".$entropy;
		
			if( $fileHandle = @fopen ( $tempfile, 'w' ) ) {
			
				if( ! @fwrite( $fileHandle, "[groups]\n" ) ) {
					
					$retcode					= 1;
					$tMessage					= sprintf( _("Cannot write to %s"), $tempfile );
					db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
				} 
				
				if( $retcode == 0 ) {
				
					# write groups to file
					$query							= "  SELECT svngroups.groupname, svnusers.userid " .
													  "    FROM svngroups, svnusers, svn_users_groups " .
													  "   WHERE (svngroups.deleted = '0000-00-00 00:00:00') " .
													  "     AND (svn_users_groups.user_id = svnusers.id) " .
													  "     AND (svn_users_groups.group_id = svngroups.id) " .
													  "     AND (svnusers.deleted = '0000-00-00 00:00:00') " .
													  "     AND (svn_users_groups.deleted = '0000-00-00 00:00:00') " .
													  "ORDER BY svngroups.groupname ASC";
					$result							= db_query( $query, $dbh );
					$oldgroup						= "";
					$users							= "";
					
					while( ($row = db_array( $result['result'] )) and ($retcode == 0) ) {
						
						if( $oldgroup != $row['groupname'] ) {
							
							if( $users != "" ) {
								
								if( ! @fwrite( $fileHandle, $oldgroup." = ".$users."\n" ) )  {
									
									$retcode		= 1;
									$tMessage		= sprintf( _("Cannot write to %s"), $tempfile );
									db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
								}
								
							} 
							
							$users					= $row['userid'];
							$oldgroup				= $row['groupname'];
							
						} else {
							
							if( $users == "" ) {
								
								$users				= $row['userid'];
								
							} else {
								
								$users				= $users.", ".$row['userid'];
								
							}
							
						}
						
					}
					
					if( $users != "" ) {
						
						fwrite( $fileHandle, $oldgroup." = ".$users."\n" );
						
					}
				}
				
				
				if( $retcode == 0 ) {
				
					# write access rights to file
					$query							= "  SELECT svnmodule, modulepath, reponame, path, user_id, group_id, access_right, repo_id " .
													  "    FROM svn_access_rights, svnprojects, svnrepos " .
													  "   WHERE (svn_access_rights.deleted = '0000-00-00 00:00:00') " .
													  "     AND (svn_access_rights.valid_from <= '$curdate') " .
													  "     AND (svn_access_rights.valid_until >= '$curdate') " .
													  "     AND (svn_access_rights.project_id = svnprojects.id) " .
													  "     AND (svnprojects.repo_id = svnrepos.id) " .
													  "ORDER BY svn_access_rights.path ASC";
					$result							= db_query( $query, $dbh );
					
					while( ($row = db_array( $result['result'] )) and ($retcode == 0) ) {
						
						if( $row['access_right'] == "none" ) {
							
							$right					= "";
							
						} elseif( $row['access_right'] == "read" ) {
							
							$right					= "r";
							
						} elseif( $row['access_right'] == "write" ) {
							
							$right					= "rw";
							
						} else {
							
							$right					= "";
							
						}
						
						if( $row['path'] != $oldpath ) {
							
							$oldpath				= $row['repo_id'].$row['path'];
							if( ! @fwrite( $fileHandle, "\n[".$row['reponame'].":".$row['path']."]\n" ) ) {
								
								$retcode			= 4;
								$tMessage			= sprintf( _("Cannot write to %s"), $tempfile );
								db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
							}
							
						} 
						
						if( $row['user_id'] != "0" ) {
							
							$query					= "SELECT * " .
													  "  FROM svnusers " .
													  " WHERE (id = ".$row['user_id'].")";
							$resultusr				= db_query( $query, $dbh );
							
							if( $resultusr['rows'] == 1 ) {
								
								$rowusr				= db_array( $resultusr['result'] );
								if( ! @fwrite( $fileHandle, $rowusr['userid']." = ".$right."\n" ) ) {
									
									$retcode		= 5;
									$tMessage		= sprintf( _("Cannot write to %s"), $tempfile );
									db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
								}
								
							}
						}
						
						if( $row['group_id'] != "0" ) {
						
							$query					= "  SELECT * " .
													  "    FROM svngroups " .
													  "   WHERE (id = ".$row['group_id'].")";
							$resultgrp				= db_query( $query, $dbh );
							
							if( $resultgrp['rows'] == 1 ) {
								
								$rowgrp				= db_array( $resultgrp['result'] );
								if( ! @fwrite( $fileHandle, "@".$rowgrp['groupname']." = ".$right."\n" ) ) {
									
									$retcode		= 6;
									$tMessage		= sprintf( _("Cannot write to %s"), $tempfile );
									db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
								}
								
							} 	
						}
					}
					
				}
				
				@fclose( $fileHandle );
				
				if( @copy( $tempfile, $CONF['SVNAccessFile'] ) ) {
					
					if( @unlink( $tempfile ) ) {
						
						if( db_unset_semaphore( 'createaccessfile', 'sem', $dbh ) ) {
						
							$tMessage				= _( "Access file successfully created!" );
							
						} else {
							
							$error					= 1;
							$tMessage				= _("Access file successfully created but semaphore could nor be released");
							db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
							
						}	
						
					} else {
						
						$retcode				= 4;
						$tMessage				= sprintf( _("Delete of %s failed!"), $tempfile );
						db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
					}
					
				} else {
					
					$retcode					= 3;
					$tMessage					= sprintf( _("Copy from %s to %s failed!"), $tempfile, $CONF['SVNAccessFile'] );
					db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
				}
			
			} else {
				
				$retcode						= 1;
				$tMessage						= sprintf( _("Cannot open %s for wrtiting"), $tempfile );
				db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
				
			}
			
		} else {
			
			$error								= 1;
			$tMessage							= _("Can't set semaphore, another process is writing access file, try again later");
			db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
		}
	
	} else {
		
		$retcode							= 0;
		$tMessage							= _("Create of access file not configured!" );
			
	}
	
	$ret								= array();
	$ret['error']						= $retcode;
	$ret['errormsg']					= $tMessage;
	
	return $ret;
}
?>
