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
    
    websvn_config.php written by: Stephen M. Praus
*/


	require ("./config/config.websvn.inc.php");
	
	function setup_websvn()
	{
		$file = $CONF['websvn_conf_path'].$CONF['websvn_conf_file'];
		$websvn_conf = fopen($file, 'a');
		$changes = "\n\n\n" .
				   "### Edited by SVN Access Manager ###\n" .
				   "\n" .
				   'include		\"repo_list.php\"'."\n";
		fwrite($websvn_conf, $changes); 
		fclose($websvn_conf);
	
		if(!copy("./contrib/repo_list.php", $CONF['websvn_conf_path']))
		{
			return -1;
		}
	}

?>
