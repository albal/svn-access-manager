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
    
    repo_list.php written by: Stephen M. Praus
*/

	include '/etc/svn-access-mana/config.php';
	
	//require '/usr/share/svn-access-mana/include/db-functions-adodb.inc.php';

	//$dbh 								= db_connect ();
	//$schema								= db_determine_schema();
	//$query								= "SELECT reponame, repopath, svn_access_file  " .
	//										  "  FROM svnrepos ";
	//$result							= db_qwery($query, $dbh);
	
	if ($CONF['database_type'] == "mysql")
	{
		mysql_connect($CONF['database_host'], $CONF['database_user'], $CONF['database_password'], password);
		mysql_select_db($CONF['database_name']) or die(mysql_error());
			
		$query								= "SELECT reponame, repopath, svn_access_file  " .
											  "  FROM svnrepos ";
		$result								= mysql_query( $query );	
	
		 
		while ($row = mysql_fetch_array($result))
		{
			echo $result;
					
		   $config->addRepositorySubpath($row['reponame'], $row['repopath']);
		   $config->useAuthenticationFile($row['svn_access_file'], $row['reponame']);
		}
	}
	else if ($CONF['database_type'] == "postgree")
	{
		//postgree sql statements
	}
	else if ($CONF['database_type'] == "oracle")
	{
		//oracle sql statements
	}
?>