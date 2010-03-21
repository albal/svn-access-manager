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

/*

File:            config.websvn.inc.php.tpl
$LastChangedDate: 2010-03-18 15:45:12 -0600 (Thu, 18 Mar 2010) $
$LastChangedBy: spraus $

$Id: config.websvn.inc.php.tpl 01 2010-03-18 15:45:00Z spraus $

*/


if (ereg ("config.websvn.inc.php", $_SERVER['PHP_SELF'])) {
   
   header ("Location: login.php");
   exit;
   
}


// websvn configuration
$CONF['websvn_conf_path'] 		= '###WSVNPATH###';
$CONF['websvn_conf_file']		= '###WSVNCONF###';

?>