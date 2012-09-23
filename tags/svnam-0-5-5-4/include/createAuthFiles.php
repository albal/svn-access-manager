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
	
	$schema									= db_determine_schema();
    
	if( (isset($CONF['separateFilesPerRepo'])) and ($CONF['separateFilesPerRepo'] == "YES") ) {
		
		$ret								= createAuthUserFilePerRepo( $dbh );
		
	} else {
		
		$retcode 							= 0;
		$tMessage							= "";
		$dir								= dirname( $CONF['AuthUserFile'] );
		$entropy							= create_salt();
		$os									= determineOS();
		$slash								= ($os == "windows") ? "\\" : "/";
		$tempfile							= $dir.$slash."authtemp_".$entropy;
			
		if( $CONF['createUserFile'] == "YES" ) {
			
			if( db_set_semaphore( 'createauthuserfile', 'sem', $dbh ) ) {
				
				if( $fileHandle	= @fopen( $tempfile, 'w' ) ) {
					
					$query						= "SELECT * " .
												  "  FROM ".$schema."svnusers " .
												  " WHERE (deleted = '00000000000000') " .
												  "   AND (locked = '0') " .
												  "ORDER BY userid";
					$result						= db_query( $query, $dbh );
					
					while( $row = db_assoc( $result['result'] ) ) {
						
						if( ! @fwrite( $fileHandle, $row['userid'].":".$row['password']."\n" ) ) {
							
							$retcode 			= 1;
							$tMessage			= _( "Can't write to AuthUser file" );
							db_unset_semaphore( 'createauthuserfile', 'sem', $dbh );
						}
						
					}
					
					@fclose( $fileHandle );
					
					if( $retcode == 0 ) {
						
						if( ($os == "windows") and file_exists( $CONF['AuthUserFile'] ) ) {
							unlink( $CONF['AuthUserFile'] );
						}
						
						if( @rename( $tempfile, $CONF['AuthUserFile'] ) ) {
							
							#if( @unlink( $tempfile ) ) {
								
								if( db_unset_semaphore( 'createauthuserfile', 'sem', $dbh ) ) {
									
									$tMessage			= _("Auth user file successfully created!" );
									
								} else {
									
									$retcode			= 1;
									$tMessage			= _("Auth user file created but semaphore could not be released");
									db_unset_semaphore( 'createauthuserfile', 'sem', $dbh );
								}
								
							#} else {
								
							#	$retcode				= 4;
							#	$tMessage				= sprintf( _("Delete of %s failed!"), $tempfile );
							#	db_unset_semaphore( 'createauthuserfile', 'sem', $dbh );
							#}
							
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
			
				$retcode							= 1;
				$tMessage							= _("Can't set semaphore, another process is writing Auth User File, try again later");
			}
			
		} else {
			
			$retcode								= 0;
			$tMessage								= _("Create of auth user file not configured!" );
		}
			
		
		$ret										= array();
		$ret['error']								= $retcode;
		$ret['errormsg']							= $tMessage;
	}
	
	return $ret;
}


function createAuthUserFilePerRepo( $dbh ) {
	
	global $CONF;
	
	$schema								= db_determine_schema();
    
	$retcode 							= 0;
	$tMessage							= "";
	$dir								= dirname( $CONF['AuthUserFile'] );
	$entropy							= create_salt();
	$os									= determineOS();
	$slash								= ($os == "windows") ? "\\" : "/";
	$tempfile							= $dir.$slash."authtemp_".$entropy;
	$curdate							= strftime( "%Y%m%d" );
		
	if( $CONF['createUserFile'] == "YES" ) {
		
		if( db_set_semaphore( 'createauthuserfile', 'sem', $dbh ) ) {
			
				
			$query						= "SELECT * " .
										  "  FROM ".$schema."svnrepos " .
										  " WHERE (deleted = '00000000000000')";
			$resultrepos				= db_query( $query, $dbh );
			while( $row = db_assoc( $resultrepos['result'] ) ) {
			
				$repoid						= $row['id'];
				$authuserfile				= $row['auth_user_file'];
				$reponame					= $row['reponame'];
				if( $authuserfile == "" ) {
					$authuserfile			= dirname( $CONF['AuthUserFile'] )."/svn-passwd.".$reponame;
				}
				
				if( $fileHandle	= @fopen( $tempfile, 'w' ) ) { 
												  
					$query						= "SELECT DISTINCT svnusers.userid, svnusers.password " .
                                                  "  FROM ".$schema."svnusers, ".$schema."svn_access_rights, ".$schema."svnrepos, ".$schema."svnprojects, ".$schema."svn_users_groups" .
                                                  " WHERE (svnprojects.repo_id=$repoid) " .
                                                  "   AND (svn_access_rights.project_id = svnprojects.id) " .
                                                  "   AND (svnrepos.deleted = '00000000000000') " .
                                                  "   AND (svn_access_rights.deleted = '00000000000000') " .
                                                  "   AND (svn_access_rights.valid_from <= '$curdate') " .
                                                  "   AND (svn_access_rights.valid_until >= '$curdate') " .
                                                  "   AND (svnprojects.deleted = '00000000000000') " .
                                                  "   AND (svnusers.locked = '0') " .
                                                  "   AND (" .
                                                  "    (svnusers.id = svn_access_rights.user_id) OR ( " .
                                                  "     (svn_users_groups.user_id = svnusers.id)" .
                                                  "     AND (svn_users_groups.group_id = svn_access_rights.group_id)" .
                                                  "     AND (svn_users_groups.deleted =  '00000000000000')" .
                                                  "    ))" .
                                                  "ORDER BY svnusers.userid";
					
												  
					$result						= db_query( $query, $dbh );
					
					while( $row = db_assoc( $result['result'] ) ) {
						
						if( ! @fwrite( $fileHandle, $row['userid'].":".$row['password']."\n" ) ) {
							
							$retcode 			= 1;
							$tMessage			= _( "Can't write to AuthUser file" );
							db_unset_semaphore( 'createauthuserfile', 'sem', $dbh );
						}
						
					}
					
					@fclose( $fileHandle );	
					
					if( $retcode == 0 ) {
						
						if( ($os == "windows") and file_exists( $authuserfile ) ) {
							unlink( $authuserfile );
						}
						
						if( @rename( $tempfile, $authuserfile ) ) {
							
						} else {
							
							$retcode					= 3;
							$tMessage					= sprintf( _("Copy from %s to %s failed!"), $tempfile, $CONF['AuthUserFile'] );
							db_unset_semaphore( 'createauthuserfile', 'sem', $dbh );
						}
						
					}
					
					#if( @unlink( $tempfile ) ) {
								
					#} else {
						
					#	$retcode				= 4;
					#	$tMessage				= sprintf( _("Delete of %s failed!"), $tempfile );
					#	db_unset_semaphore( 'createauthuserfile', 'sem', $dbh );
					#}
					
				} else {
					
					$retcode						= 2;
					$tMessage						= sprintf( _( "Cannot open file %s for writing!" ), $tempfile );
					db_unset_semaphore( 'createauthuserfile', 'sem', $dbh );
				
				}
			}
			
			if( $retcode == 0 ) {
				if( db_unset_semaphore( 'createauthuserfile', 'sem', $dbh ) ) {
										
					$tMessage			= _("Auth user file successfully created!" );
					
				} else {
					
					$retcode			= 1;
					$tMessage			= _("Auth user file created but semaphore could not be released");
					db_unset_semaphore( 'createauthuserfile', 'sem', $dbh );
				}
			}
			
		} else {
		
			$retcode							= 1;
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
	
	$schema									= db_determine_schema();
	
	if( (isset($CONF['separateFilesPerRepo'])) and ($CONF['separateFilesPerRepo'] == "YES") ) {
		
		$ret								= createAccessFilePerRepo( $dbh );
		
	} else {
	
		$retcode 							= 0;
		$tMessage							= "";
		$curdate							= strftime( "%Y%m%d" );
		$oldpath							= "";
		
		if( $CONF['createAccessFile'] == "YES" ) {
			
			if( db_set_semaphore( 'createaccessfile', 'sem', $dbh ) ) {
				
				$dir						= dirname( $CONF['SVNAccessFile'] );
				$entropy					= create_salt();
				$os							= determineOS();
				$slash						= ($os == "windows") ? "\\" : "/";
				$tempfile					= $dir.$slash."accesstemp_".$entropy;
			
				if( $fileHandle = @fopen ( $tempfile, 'w' ) ) {
				
					$groupwritten						= 0;
					
					if( $retcode == 0 ) {
					
						# write groups to file
						$query							= "  SELECT svngroups.groupname, svnusers.userid " .
														  "    FROM ".$schema."svngroups, ".$schema."svnusers, ".$schema."svn_users_groups " .
														  "   WHERE (svngroups.deleted = '00000000000000') " .
														  "     AND (svn_users_groups.user_id = svnusers.id) " .
														  "     AND (svn_users_groups.group_id = svngroups.id) " .
														  "     AND (svnusers.deleted = '00000000000000') " .
														  "     AND (svn_users_groups.deleted = '00000000000000') " .
														  "ORDER BY svngroups.groupname ASC";
						$result							= db_query( $query, $dbh );
						$oldgroup						= "";
						$users							= "";
						
						while( ($row = db_assoc( $result['result'] )) and ($retcode == 0) ) {
							
							if( $oldgroup != $row['groupname'] ) {
								
								if( $users != "" ) {
									
									if( $groupwritten == 0 ) {
										
										$groupwritten 	= 1;
										if( ! @fwrite( $fileHandle, "[groups]\n" ) ) {
							
											$retcode	= 1;
											$tMessage	= sprintf( _("Cannot write to %s"), $tempfile );
											db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
										} 
										
									}
									
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
							
							if( $groupwritten == 0 ) {
										
								$groupwritten 			= 1;
								if( ! @fwrite( $fileHandle, "[groups]\n" ) ) {
					
									$retcode			= 1;
									$tMessage			= sprintf( _("Cannot write to %s"), $tempfile );
									db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
								} 
								
							}
							
							fwrite( $fileHandle, $oldgroup." = ".$users."\n" );
							
						}
					}
					
					
					if( $retcode == 0 ) {
	
						$first						= 1;
						$query						= "SELECT * " .
												  	  "  FROM ".$schema."svnusers " .
												  	  " WHERE (superadmin = 1) " .
												  	  "   AND (deleted = '00000000000000')";
						$resultusr 					= db_query( $query, $dbh );
						while( $rowusr = db_assoc( $resultusr['result'] ) ) {
	
							if( $first == 1 ) {
								
								$first					= 0;
								
								# write superuser privileges for access to all repositories by http(s)
								if( ! @fwrite( $fileHandle, "\n[/]\n" ) ) {
											
									$retcode			= 8;
									$tMessage			= sprintf( _("Cannot write to %s"), $tempfile );
									db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
								}
							}	
							
							if( ! @fwrite( $fileHandle, $rowusr['userid']." = r\n" ) ) {
										
								$retcode				= 5;
								db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
								$tMessage				= sprintf( _("Cannot write to %s"), $tempfile );
							}
									
						}
						
					}
					
					
					if( $retcode == 0 ) {
						
						# write access rights to file
						
						$query							= "  SELECT svnmodule, modulepath, reponame, path, user_id, group_id, access_right, repo_id " .
														  "    FROM ".$schema."svn_access_rights, ".$schema."svnprojects, ".$schema."svnrepos " .
														  "   WHERE (svn_access_rights.deleted = '00000000000000') " .
														  "     AND (svn_access_rights.valid_from <= '$curdate') " .
														  "     AND (svn_access_rights.valid_until >= '$curdate') " .
														  "     AND (svn_access_rights.project_id = svnprojects.id) " .
														  "     AND (svnprojects.repo_id = svnrepos.id) " .
														  "ORDER BY svnprojects.repo_id ASC, LENGTH(svn_access_rights.path) DESC";
						error_log( $query );
						$result							= db_query( $query, $dbh );
						
						while( ($row = db_assoc( $result['result'] )) and ($retcode == 0) ) {
							
							if( $row['access_right'] == "none" ) {
								
								$right					= "";
								
							} elseif( $row['access_right'] == "read" ) {
								
								$right					= "r";
								
							} elseif( $row['access_right'] == "write" ) {
								
								$right					= "rw";
								
							} else {
								
								$right					= "";
								
							}
							
							$checkpath				= $row['repo_id'].$row['path'];
							if( $checkpath != $oldpath ) {
								
								$oldpath				= $row['repo_id'].$row['path'];
								$tPath					= preg_replace( '/\/$/', '', $row['path'] );
								if( $tPath == "" ) {
									$tPath				= "/";
								}
								if( ! @fwrite( $fileHandle, "\n[".$row['reponame'].":".$tPath."]\n" ) ) {
									
									$retcode			= 4;
									$tMessage			= sprintf( _("Cannot write to %s"), $tempfile );
									db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
								}
								
							} 
							
							if( ($row['user_id'] != "0") and (!empty($row['user_id'])) ) {
								
								$query					= "SELECT * " .
														  "  FROM ".$schema."svnusers " .
														  " WHERE (id = ".$row['user_id'].")";
								$resultusr				= db_query( $query, $dbh );
								
								if( $resultusr['rows'] == 1 ) {
									
									$rowusr				= db_assoc( $resultusr['result'] );
									if( ! @fwrite( $fileHandle, $rowusr['userid']." = ".$right."\n" ) ) {
										
										$retcode		= 5;
										$tMessage		= sprintf( _("Cannot write to %s"), $tempfile );
										db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
									}
									
								}
							}
							
							if( ($row['group_id'] != "0") and (!empty($row['group_id']) ) ) {
							
								$query					= "  SELECT * " .
														  "    FROM ".$schema."svngroups " .
														  "   WHERE (id = ".$row['group_id'].")";
								$resultgrp				= db_query( $query, $dbh );
								
								if( $resultgrp['rows'] == 1 ) {
									
									$rowgrp				= db_assoc( $resultgrp['result'] );
									if( ! @fwrite( $fileHandle, "@".$rowgrp['groupname']." = ".$right."\n" ) ) {
										
										$retcode		= 6;
										$tMessage		= sprintf( _("Cannot write to %s"), $tempfile );
										db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
									}
									
								} 	
							}
						}
						
						if( ! @fwrite( $fileHandle, "\n" ) ) {
							
							$retcode					= 7;
							$tMessage					= sprintf( _("Cannot write to %s"), $tempfile );
							db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
										
						} 
					
						@fclose( $fileHandle );
						
						if( ($os == "windows") and file_exists( $CONF['SVNAccessFile'] ) ) {
							unlink( $CONF['SVNAccessFile'] );
						}
						
						if( @rename( $tempfile, $CONF['SVNAccessFile'] ) ) {
							
							#if( @unlink( $tempfile ) ) {
								
								if( db_unset_semaphore( 'createaccessfile', 'sem', $dbh ) ) {
								
									$tMessage				= _( "Access file successfully created!" );
									
								} else {
									
									$retcode				= 1;
									$tMessage				= _("Access file successfully created but semaphore could nor be released");
									db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
									
								}	
								
							#} else {
								
							#	$retcode				= 4;
							#	$tMessage				= sprintf( _("Delete of %s failed!"), $tempfile );
							#	db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
							#}
							
						} else {
							
							$retcode					= 3;
							$tMessage					= sprintf( _("Copy from %s to %s failed!"), $tempfile, $CONF['SVNAccessFile'] );
							db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
						}	
					}
				
				} else {
					
					$retcode						= 1;
					$tMessage						= sprintf( _("Cannot open %s for wrtiting"), $tempfile );
					db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
					
				}
				
			} else {
				
				$retcode							= 1;
				$tMessage							= _("Can't set semaphore, another process is writing access file, try again later");
	
			}
		
		} else {
			
			$retcode							= 0;
			$tMessage							= _("Create of access file not configured!" );
				
		}
		
		$ret									= array();
		$ret['error']							= $retcode;
		$ret['errormsg']						= $tMessage;
	}

	return $ret;
}


function createAccessFilePerRepo( $dbh ) {
	
	global $CONF;
	
	$schema								= db_determine_schema();
	
	$retcode 							= 0;
	$tMessage							= "";
	$curdate							= strftime( "%Y%m%d" );
	$oldpath							= "";
	
	if( $CONF['createAccessFile'] == "YES" ) {
		
		if( db_set_semaphore( 'createaccessfile', 'sem', $dbh ) ) {
			
			$dir							= dirname( $CONF['SVNAccessFile'] );
			$entropy						= create_salt();
			$os								= determineOS();
			$slash							= ($os == "windows") ? "\\" : "/";
			$tempfile						= $dir.$slash."accesstemp_".$entropy;
			
			$query							= "SELECT * " .
											  "  FROM ".$schema."svnrepos " .
											  " WHERE (deleted = '00000000000000')";
			$resultrepos					= db_query( $query, $dbh );
			while( $row = db_assoc( $resultrepos['result'] ) ) {
			
				$repoid						= $row['id'];
				$authuserfile				= $row['auth_user_file'];
				$svnaccessfile				= $row['svn_access_file'];
				$reponame					= $row['reponame'];
				if( $svnaccessfile == "" ) {
					$svnaccessfile			= dirname( $CONF['SVNAccessFile'] )."/svn-access.".$reponame;
				}
		
				if( $fileHandle = @fopen ( $tempfile, 'w' ) ) {
				
					$groupwritten			= 0;
					
					if( $retcode == 0 ) {
					
						# write groups to file		
						$query							= "  SELECT svngroups.groupname, svnusers.userid " .
														  "    FROM ".$schema."svngroups, ".$schema."svnusers, ".$schema."svn_users_groups, ".$schema."svnprojects, ".$schema."svn_access_rights, ".$schema."svnrepos " .
														  "   WHERE (svn_users_groups.user_id = svnusers.id) " .
														  "     AND (svn_users_groups.group_id = svngroups.id) " .
														  "     AND (svnprojects.repo_id = svnrepos.id) " .
														  "     AND (svnprojects.repo_id=$repoid) " .
														  "     AND (svnprojects.id = svn_access_rights.project_id) " .
														  "     AND (svn_access_rights.group_id=svngroups.id) " .
														  "     AND (svn_access_rights.group_id != 0) " .
														  "     AND (svn_users_groups.deleted='00000000000000') " .
														  "     AND (svn_access_rights.deleted='00000000000000') " .
														  "     AND (svn_access_rights.valid_from <= '$curdate') " .
														  "     AND (svn_access_rights.valid_until >= '$curdate') " .
														  "     AND (svnprojects.deleted='00000000000000') " .
														  "     AND (svngroups.deleted='00000000000000') " .
														  "     AND (svnrepos.deleted='00000000000000') " .
														  "     AND (svnusers.deleted='00000000000000') " .
														  "ORDER BY svngroups.groupname ASC";
						$result							= db_query( $query, $dbh );
						$oldgroup						= "";
						$users							= "";
						
						while( ($row = db_assoc( $result['result'] )) and ($retcode == 0) ) {
							
							if( $oldgroup != $row['groupname'] ) {
								
								if( $users != "" ) {
									
										
										$groupwritten		= 1;
										
										if( ! @fwrite( $fileHandle, "[groups]\n" ) ) {
											
											$retcode		= 1;
											$tMessage		= sprintf( _("Cannot write to %s"), $tempfile );
									if( $groupwritten == 0 ) {
											db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
										} 
									}
									
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
							
							if( $groupwritten == 0 ) {
										
								$groupwritten		= 1;
								
								if( ! @fwrite( $fileHandle, "[groups]\n" ) ) {
									
									$retcode		= 1;
									$tMessage		= sprintf( _("Cannot write to %s"), $tempfile );
									db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
								} 
							}
							
							fwrite( $fileHandle, $oldgroup." = ".$users."\n" );
							
						}

					}
					
					
					if( $retcode == 0 ) {
	
						$first						= 1;
						$query						= "SELECT * " .
												  	  "  FROM ".$schema."svnusers " .
												  	  " WHERE (superadmin = 1) " .
												  	  "   AND (deleted = '00000000000000')";
						$resultusr 					= db_query( $query, $dbh );
						while( $rowusr = db_assoc( $resultusr['result'] ) ) {
	
							if( $first == 1 ) {
								
								$first					= 0;
								
								# write superuser privileges for access to all repositories by http(s)
								if( ! @fwrite( $fileHandle, "\n[/]\n" ) ) {
											
									$retcode			= 8;
									$tMessage			= sprintf( _("Cannot write to %s"), $tempfile );
									db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
								}
							}	
							
							if( ! @fwrite( $fileHandle, $rowusr['userid']." = r\n" ) ) {
										
								$retcode				= 5;
								$tMessage				= sprintf( _("Cannot write to %s"), $tempfile );
								db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
							}
									
						}
						
					}
					
					
					if( $retcode == 0 ) {
						
						# write access rights to file
						$query							= "  SELECT svnmodule, modulepath, reponame, path, user_id, group_id, access_right, repo_id " .
														  "    FROM ".$schema."svn_access_rights, ".$schema."svnprojects, ".$schema."svnrepos " .
														  "   WHERE (svn_access_rights.deleted = '00000000000000') " .
														  "     AND (svn_access_rights.valid_from <= '$curdate') " .
														  "     AND (svn_access_rights.valid_until >= '$curdate') " .
														  "     AND (svn_access_rights.project_id = svnprojects.id) " .
														  "     AND (svnprojects.repo_id = svnrepos.id) " .
														  "     AND (svnprojects.repo_id=$repoid) " .
														  "     AND (svnprojects.deleted='00000000000000') " .
														  "     AND (svnrepos.deleted='00000000000000') " .
														  "ORDER BY svnprojects.repo_id ASC, LENGTH(svn_access_rights.path) DESC";
						$result							= db_query( $query, $dbh );
						
						while( ($row = db_assoc( $result['result'] )) and ($retcode == 0) ) {
							
							if( $row['access_right'] == "none" ) {
								
								$right					= "";
								
							} elseif( $row['access_right'] == "read" ) {
								
								$right					= "r";
								
							} elseif( $row['access_right'] == "write" ) {
								
								$right					= "rw";
								
							} else {
								
								$right					= "";
								
							}
							
							$checkpath				= $row['repo_id'].$row['path'];
							if( $checkpath != $oldpath ) {
								
								$oldpath				= $row['repo_id'].$row['path'];
								$tPath					= preg_replace( '/\/$/', '', $row['path'] );
								if( $tPath == "" ) {
									$tPath				= "/";
								}
								if( ! @fwrite( $fileHandle, "\n[".$row['reponame'].":".$tPath."]\n" ) ) {
									
									$retcode			= 4;
									$tMessage			= sprintf( _("Cannot write to %s"), $tempfile );
									db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
								}
								
							} 
							
							if( ($row['user_id'] != "0") and (!empty($row['user_id'])) ) {
								
								$query					= "SELECT * " .
														  "  FROM ".$schema."svnusers " .
														  " WHERE (id = ".$row['user_id'].")";
								$resultusr				= db_query( $query, $dbh );
								
								if( $resultusr['rows'] == 1 ) {
									
									$rowusr				= db_assoc( $resultusr['result'] );
									if( ! @fwrite( $fileHandle, $rowusr['userid']." = ".$right."\n" ) ) {
										
										$retcode		= 5;
										$tMessage		= sprintf( _("Cannot write to %s"), $tempfile );
										db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
									}
									
								}
							}
							
							if( ($row['group_id'] != "0") and (!empty($row['group_id'])) ) {
							
								$query					= "  SELECT * " .
														  "    FROM ".$schema."svngroups " .
														  "   WHERE (id = ".$row['group_id'].")";
								$resultgrp				= db_query( $query, $dbh );
								
								if( $resultgrp['rows'] == 1 ) {
									
									$rowgrp				= db_assoc( $resultgrp['result'] );
									if( ! @fwrite( $fileHandle, "@".$rowgrp['groupname']." = ".$right."\n" ) ) {
										
										$retcode		= 6;
										$tMessage		= sprintf( _("Cannot write to %s"), $tempfile );
										db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
									}
									
								} 	
							}
						}
						
						if( ! @fwrite( $fileHandle, "\n" ) ) {
							
							$retcode					= 7;
							$tMessage					= sprintf( _("Cannot write to %s"), $tempfile );
							db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
										
						} 
					
						@fclose( $fileHandle );
						
						if( ($os == "windows") and file_exists( $svnaccessfile ) ) {
							unlink( $svnaccessfile );
						}
						
						if( @rename( $tempfile, $svnaccessfile ) ) {
							
						} else {
							
							$retcode					= 3;
							$tMessage					= sprintf( _("Copy from %s to %s failed!"), $tempfile, $CONF['SVNAccessFile'] );
							db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
						}	
					}
					
					#if( @unlink( $tempfile ) ) {
								
					#} else {
						
					#	$retcode				= 4;
					#	$tMessage				= sprintf( _("Delete of %s failed!"), $tempfile );
					#	db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
					#}
				
				} else {
					
					$retcode						= 1;
					$tMessage						= sprintf( _("Cannot open %s for wrtiting"), $tempfile );
					db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
					
				}
			
			} # end iteration over repos
			
			if( db_unset_semaphore( 'createaccessfile', 'sem', $dbh ) ) {
			
				$tMessage				= _( "Access file successfully created!" );
				
			} else {
				
				$retcode				= 1;
				$tMessage				= _("Access file successfully created but semaphore could nor be released");
				db_unset_semaphore( 'createaccessfile', 'sem', $dbh );
				
			}	

		} else {
			
			$retcode							= 1;
			$tMessage							= _("Can't set semaphore, another process is writing access file, try again later");

		}
	
	} else {
		
		$retcode							= 0;
		$tMessage							= _("Create of access file not configured!" );
			
	}
	
	$ret									= array();
	$ret['error']							= $retcode;
	$ret['errormsg']						= $tMessage;
	
	return $ret;
}


function getGroupMembers( $groupid, $dbh ) {
	
	global $CONF;
	
	$schema								= db_determine_schema();
    
	$members							= array();
	$query								= "  SELECT userid " .
										  "    FROM ".$schema."svnusers, ".$schema."svngroups, ".$schema."svn_users_groups " .
										  "   WHERE (svngroups.id = $groupid) " .
										  "     AND (svngroups.id = svn_users_groups.group_id) " .
										  "     AND (svnusers.id = svn_users_groups.user_id) " .
										  "ORDER BY userid ASC";
	$result								= db_query( $query, $dbh );
	while( $row = db_assoc( $result['result'] ) ) {
		$members[]						= $row['userid'];
	}
	
	return $members;
}



function deleteUser( $members, $userid ) {
	
	$new								= array();
	
	for( $i = 0; $i < count( $members); $i++ ) {
		
		if( $members[$i] != $userid ) {
			
			$new[]						= $members[$i];
			
		}
	}
		
	return $new;
}



function getUpperDirUsers( $checkpath, $repopathes ) {
	
	$parts								= explode( '/', $checkpath );
	$count								= count( $parts );
	$data								= array();
	
	if( $count >= 2 ) {
		
		array_pop( $parts );
		
		$path							= implode( '/', $parts );
		
		if( array_key_exists( $path, $repopathes ) ) {
			
			$data						= $repopathes[$path];
		
		} else {
			
			$data						= getUpperDirUsers( $path, $repopathes );
		
		}
	}
	
	return $data;
}



function createViewvcConfig( $dbh ) {

	global $CONF;
	
	$schema								= db_determine_schema();
	
	$retcode 							= 0;
	$tMessage							= "";
	$curdate							= strftime( "%Y%m%d" );
	$oldpath							= "";
	$oldgroup							= "";
	$modulepath							= "";
	$currentgroup						= "g".create_salt();
	$groups[$currentgroup]				= "";
	$repopathes							= array();
	
	if( $CONF['createViewvcConf'] == "YES" ) {
		
		if( db_set_semaphore( 'createviewvcconf', 'sem', $dbh ) ) {
			
			$dir							= dirname( $CONF['ViewvcConf'] );
			$entropy						= create_salt();
			$os								= determineOS();
			$slash							= ($os == "windows") ? "\\" : "/";
			$tempfile						= $dir.$slash."viewvc_conf_temp_".$entropy;
		
			if( $fileHandle = @fopen ( $tempfile, 'w' ) ) {
	
				$dir						= dirname( $CONF['ViewvcGroups'] );
				$entropy					= create_salt();
				$os							= determineOS();
				$slash						= ($os == "windows") ? "\\" : "/";
				$tempgroups					= $dir.$slash."viewvc_groups_temp_".$entropy;
				
				if( $groupHandle = @fopen( $tempgroups, 'w' ) ) {
			
					$query						= "  SELECT svnmodule, modulepath, reponame, path, user_id, group_id, access_right, repo_id " .
												  "    FROM ".$schema."svn_access_rights, ".$schema."svnprojects, ".$schema."svnrepos " .
												  "   WHERE (svn_access_rights.deleted = '00000000000000') " .
												  "     AND (svn_access_rights.valid_from <= '$curdate') " .
												  "     AND (svn_access_rights.valid_until >= '$curdate') " .
												  "     AND (svn_access_rights.project_id = svnprojects.id) " .
												  "     AND (svnprojects.repo_id = svnrepos.id) " .
												  "ORDER BY svnprojects.repo_id ASC, svn_access_rights.path ASC, svn_access_rights.access_right DESC";
					
					$result						= db_query( $query, $dbh );
					
					while( ($row = db_assoc( $result['result'] )) and ($retcode == 0) ) {
						
						$checkpath				= $row['repo_id'].$row['path'];
						
						if( $checkpath != $oldpath ) {
							
							$oldgroup				= $currentgroup;
							$currentgroup			= "g".create_salt();
							while( array_key_exists( $currentgroup, $groups ) ) {
								$currentgroup			= "g".create_salt();
							}
							
							if( ! array_key_exists( $checkpath, $repopathes ) ) {
								
								$data					= getUpperDirUsers( $checkpath, $repopathes );
								$repopathes[$checkpath]	= $data;
								
							} else {

								$data					= $repopathes[$checkpath];

							}
							
							$groups[$currentgroup]		= $data;			
							$oldpath					= $row['repo_id'].$row['path'];
							$modulepath					= $CONF['ViewvcLocation']."/".$row['reponame'].$row['path'];
							
							if( ! @fwrite( $fileHandle, "<Location $modulepath>\n" ) ) {
								$retcode			= 9;
								$tMessage			= sprintf( _("Cannot write to %s"), $tempfile );
								db_unset_semaphore( 'createviewvcconf', 'sem', $dbh );
							}
							
							if( $retcode == 0 ) {
								if( ! @fwrite( $fileHandle, "     AuthType Basic\n" ) ) {
									$retcode		= 9;
									$tMessage		= sprintf( _("Cannot write to %s"), $tempfile );
									db_unset_semaphore( 'createviewvcconf', 'sem', $dbh );
								}
							}
							
							if( $retcode == 0 ) {
								if( ! @fwrite( $fileHandle, "     AuthName \"Viewvc Access Control\"\n" ) ) {
									$retcode		= 9;
									$tMessage		= sprintf( _("Cannot write to %s"), $tempfile );
									db_unset_semaphore( 'createviewvcconf', 'sem', $dbh );
								}
							}
							
							if( $retcode == 0 ) {
								if( ! @fwrite( $fileHandle, "     AuthUserFile ".$CONF['AuthUserFile']."\n" ) ) {
									$retcode		= 9;
									$tMessage		= sprintf( _("Cannot write to %s"), $tempfile );
									db_unset_semaphore( 'createviewvcconf', 'sem', $dbh );
								}
							}
							
							if( $retcode == 0 ) {
								if( ! @fwrite( $fileHandle, "     AuthGroupFile ".$CONF['ViewvcGroups']."\n" ) ) {
									$retcode		= 9;
									$tMessage		= sprintf( _("Cannot write to %s"), $tempfile );
									db_unset_semaphore( 'createviewvcconf', 'sem', $dbh );
								}
							}
							
							if( $retcode == 0 ) {
								if( ! @fwrite( $fileHandle, "     Require group $currentgroup\n" ) ) {
									$retcode		= 9;
									$tMessage		= sprintf( _("Cannot write to %s"), $tempfile );
									db_unset_semaphore( 'createviewvcconf', 'sem', $dbh );
								}
							}
							
							if( $retcode == 0 ) {
								if( ! @fwrite( $fileHandle, "</Location>\n\n" ) ) {
									$retcode		= 9;
									$tMessage		= sprintf( _("Cannot write to %s"), $tempfile );
									db_unset_semaphore( 'createviewvcconf', 'sem', $dbh );
								}
							}

								
						}
						
						if( $row['access_right'] != "none" ) {

							if( ($row['user_id'] != "0") and (!empty($row['user_id'])) ) {
								
								$query					= "SELECT * " .
														  "  FROM ".$schema."svnusers " .
														  " WHERE (id = ".$row['user_id'].")";
								$resultusr				= db_query( $query, $dbh );
								
								if( $resultusr['rows'] == 1 ) {
									
									# add user to apache access group
									$rowusr					= db_assoc( $resultusr['result'] );
									
									if( ! in_array( $rowusr['userid'], $groups[$currentgroup] ) ) {
										
										$groups[$currentgroup][]	= $rowusr['userid'];
										$repopathes[$checkpath][]	= $rowusr['userid'];
										
									}
									
								}
							}
							
							if( ($row['group_id'] != "0") and (!empty($row['group_id'])) ) {
							
								$query					= "  SELECT * " .
														  "    FROM ".$schema."svngroups " .
														  "   WHERE (id = ".$row['group_id'].")";
								$resultgrp				= db_query( $query, $dbh );
								
								if( $resultgrp['rows'] == 1 ) {
									
									# get group members
									$rowgrp				= db_assoc( $resultgrp['result'] );
									$groupid			= $rowgrp['id'];
									$members			= getGroupMembers( $groupid, $dbh );
									
									foreach( $members as $member ) {
										
										if( ! in_array( $member, $groups[$currentgroup] ) ) {
											
											$groups[$currentgroup][] = $member;
											$repopathes[$checkpath][]= $member;
											
										}
									} 
								} 	
								
							} 
							
						} else {
						
							if( ($row['user_id'] != "0") and (!empty($row['user_id'])) ) {
								
								$query					= "SELECT * " .
														  "  FROM ".$schema."svnusers " .
														  " WHERE (id = ".$row['user_id'].")";
								$resultusr				= db_query( $query, $dbh );
								
								if( $resultusr['rows'] == 1 ) {
									
									# delete user from apache access group
									$rowusr					= db_assoc( $resultusr['result'] );
									
									if( in_array( $rowusr['userid'], $groups[$currentgroup] ) ) {
										
										$groups[$currentgroup]	= deleteUser($groups[$currentgroup], $rowusr['userid'] );
										$repopathes[$checkpath] = deleteUser($repopathes[$checkpath], $rowusr['userid']);
										
									}
									
								}
							}
							
							if( ($row['group_id'] != "0") and (!empty($row['group_id'])) ) {
							
								$query					= "  SELECT * " .
														  "    FROM ".$schema."svngroups " .
														  "   WHERE (id = ".$row['group_id'].")";
								$resultgrp				= db_query( $query, $dbh );
								
								if( $resultgrp['rows'] == 1 ) {
									
									# get group members
									$rowgrp				= db_assoc( $resultgrp['result'] );
									$groupid			= $rowgrp['id'];
									$members			= getGroupMembers( $groupid, $dbh );
									
									foreach( $members as $member ) {
										
										if( in_array( $member, $groups[$currentgroup] ) ) {
											
											$groups[$currentgroup] = deleteUser($groups[$currentgroup], $member );
											$repopathes[$checkpath]= deleteUser($repopathes[$checkpath], $member );
										}
									} 
								} 	
								
							} 
								
						}
						
					}
					
					foreach( $groups as $group => $members ) {
						
						if( count( $members ) != 0 ) {
							
							if( ! fwrite( $groupHandle, $group.":") ) {
								
								$retcode		= 10;
								$tMessage		= sprintf( _("Cannot write to %s"), $tempgroups );
								db_unset_semaphore( 'createviewvcconf', 'sem', $dbh );
								
							} else {
								
								if( is_array( $members) and ! empty( $members) ) {
									for( $i = 0; $i < count( $members ); $i++ ) {
										if( ! fwrite( $groupHandle, $members[$i]." ") ) {
											$retcode		= 10;
											$tMessage		= sprintf( _("Cannot write to %s"), $tempgroups );
											db_unset_semaphore( 'createviewvcconf', 'sem', $dbh );
										}
									}
								}
							}
							
							if( ! fwrite( $groupHandle, "\n") ) {
								
								$retcode		= 10;
								$tMessage		= sprintf( _("Cannot write to %s"), $tempgroups );
								db_unset_semaphore( 'createviewvcconf', 'sem', $dbh );
								
							}
						}
					}
						
					@fclose( $groupHandle );
					
				} else {
				 	
				}	
				
				if( ! @fwrite( $fileHandle, "<LocationMatch (^".$CONF['ViewvcLocation']."\$|^".$CONF['ViewvcLocation']."/\$)>\n" ) ) {
					$retcode		= 9;
					$tMessage		= sprintf( _("Cannot write to %s"), $tempfile );
					db_unset_semaphore( 'createviewvcconf', 'sem', $dbh );
				}
				
				if( ! @fwrite( $fileHandle, "      AuthType Basic\n" ) ) {
					$retcode		= 9;
					$tMessage		= sprintf( _("Cannot write to %s"), $tempfile );
					db_unset_semaphore( 'createviewvcconf', 'sem', $dbh );
				}
				
				if( ! @fwrite( $fileHandle, "      AuthName \"Viewvc Access Control\"\n" ) ) {
					$retcode		= 9;
					$tMessage		= sprintf( _("Cannot write to %s"), $tempfile );
					db_unset_semaphore( 'createviewvcconf', 'sem', $dbh );
				}
				
				if( ! @fwrite( $fileHandle, "      AuthUserFile /etc/svn/svn-passwd\n" ) ) {
					$retcode		= 9;
					$tMessage		= sprintf( _("Cannot write to %s"), $tempfile );
					db_unset_semaphore( 'createviewvcconf', 'sem', $dbh );
				}
				
				if( ! @fwrite( $fileHandle, "      Require valid-user\n" ) ) {
					$retcode		= 9;
					$tMessage		= sprintf( _("Cannot write to %s"), $tempfile );
					db_unset_semaphore( 'createviewvcconf', 'sem', $dbh );
				}
				
				if( ! @fwrite( $fileHandle, "</LocationMatch>\n" ) ) {
					$retcode		= 9;
					$tMessage		= sprintf( _("Cannot write to %s"), $tempfile );
					db_unset_semaphore( 'createviewvcconf', 'sem', $dbh );
				}
				
				@fclose( $fileHandle );
				
			} else {
				
			}
			
			if( $retcode == 0 ) {
				
				if( ($os == "windows") and file_exists( $CONF['ViewvcGroups'] ) ) {
					unlink( $CONF['ViewvcGroups'] );
				}
						
				if( @rename( $tempgroups, $CONF['ViewvcGroups'] ) ) {
						
					#if( @unlink( $tempgroups ) ) {
						
						if( ($os == "windows") and file_exists( $CONF['ViewvcConf'] ) ) {
							unlink( $CONF['ViewvcConf'] );
						}
				
						if( @rename( $tempfile, $CONF['ViewvcConf'] ) ) {
							
							#if( @unlink( $tempfile ) ) {
								
								if( db_unset_semaphore( 'createviewvcconf', 'sem', $dbh ) ) {
							
									$tMessage				= _( "Viewvc access configuration successfully created!" );
								
								} else {
								
									$retcode				= 1;
									$tMessage				= _("Viewvc access configuration successfully created but semaphore could nor be released");
									db_unset_semaphore( 'createviewvcconf', 'sem', $dbh );
								
								}
									
							#} else {
								
							#	$retcode				= 4;
							#	$tMessage				= sprintf( _("Delete of %s failed!"), $tempfile );
							#	db_unset_semaphore( 'ccreateviewvcconf', 'sem', $dbh );
							#}
							
						} else {
							
							$retcode					= 3;
							$tMessage					= sprintf( _("Copy from %s to %s failed!"), $tempgroups, $CONF['ViewvcGroups'] );
							db_unset_semaphore( 'createviewvcconf', 'sem', $dbh );
						}
						
					#} else {
						
					#	$retcode				= 4;
					#	$tMessage				= sprintf( _("Delete of %s failed!"), $tempgroups );
					#	db_unset_semaphore( 'ccreateviewvcconf', 'sem', $dbh );
					#}
					
				} else {
					
					$retcode					= 3;
					$tMessage					= sprintf( _("Copy from %s to %s failed!"), $tempfile, $CONF['ViewvcGroups'] );
					db_unset_semaphore( 'createviewvcconf', 'sem', $dbh );
				}	
			}
			
		} else {
			
			
			$retcode							= 1;
			$tMessage							= _("Can't set semaphore, another process is writing access file, try again later");
			
		}
		
	} else {
		
		$retcode							= 0;
		$tMessage							= _("Create of access file not configured!" );
	}
	
	$ret									= array();
	$ret['error']							= $retcode;
	$ret['errormsg']						= $tMessage;
	
	return $ret;
	
}
?>
