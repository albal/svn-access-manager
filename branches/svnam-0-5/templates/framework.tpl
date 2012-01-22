<?php
@header ("Expires: Sun, 16 Mar 2003 05:00:00 GMT");
@header ("Last-Modified: " . gmdate ("D, d M Y H:i:s") . " GMT");
@header ("Cache-Control: no-store, no-cache, must-revalidate");
@header ("Cache-Control: post-check=0, pre-check=0", false);
@header ("Pragma: no-cache");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<!--
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
-->
<?php
	global $CONF;
	
	if( isset( $CONF['website_charset'] ) ) {
		$charset		= $CONF['website_charset'];
	} else {
		$charset		= "iso8859-15";
	}
?>
<head>
  	<title><?php print _("SVN Access Manager")." - ".$_SERVER['HTTP_HOST']; ?></title>
  	<meta name="GENERATOR" content="Quanta Plus" />
  	<meta http-equiv="Content-Type" content="text/html; charset=<?php print $charset;?>" />
  	<!--<link rel="stylesheet" type="text/css" href="./style/lhelstyle.css" />-->
	<!--[if lt IE 9]>
		<link rel="stylesheet" type="text/css" href="./style/lhelie.css" />
	<![endif]-->
	<link type="text/css" href="./style/redmond/jquery-ui-1.8.17.custom.css" rel="stylesheet" />	
	<link rel="stylesheet" type="text/css" href="./style/jquery.tooltip.css" />
	<link rel="stylesheet" href="./stylesheet.css" type="text/css" />
	
	<script language="javascript" type="text/javascript" src="./lib/jquery/jquery.js"></script>
	<!--<script language="javascript" type="text/javascript" src="./lib/jquery-ui/js/jquery-ui-1.8.6.custom.min.js"></script>-->
	<script language="javascript" type="text/javascript" src="./lib/jquery-ui/js/jquery-ui-1.8.17.custom.min.js"></script>		
	<script language="javascript" type="text/javascript" src="./lib/jquery-ui/js/jquery.ui.datepicker-de.js"></script>
	<script language="JavaScript" type="text/javascript" src="./lib/jquery/jquery.tooltip.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="./lib/jquery/ui.ariaSorTable_min.js"></script>
</head>
<body>
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr valign="top">
			<td width="200" bgcolor="#EAEAE8">
				<a href="http://www.svn-access-manager.org/" target="_blank">
					<img src="./images/svn-access-manager_200_60.jpg" width="200" height="60" border="0" />
				</a>
			</td>
			<td>
				<div id="header_right" class="bgEAEAE8">
					<?php outputHeader($header); ?>
				</div>
			</td>
		</tr>
		<tr valign="top">
			<td width="200">
				<div id="subheader_left">
					&nbsp;
				</div>
			</td>
			<td>
				<div id="subheader_right">
					<?php outputSubHeader($subheader); ?>
				</div>
			</td>
		</tr>
		<tr valign="top">
			<td width="200" bgcolor="#EAEAE8">
				<div id="left" class="leftMenu">
					<!--<ul> -->
						<?php outputMenu($menu); ?>
					<!--</ul>-->
				</div>
			</td>
			<td>
				<div id="right">
					<?php include( "./templates/".$template ); ?>
				</div>
			
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<?php include( "footer.tpl" ); ?>
			</td>
		</tr>
	</table>
</body>
</html>