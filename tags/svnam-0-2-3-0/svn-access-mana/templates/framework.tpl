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
<head>
  <title><?php print _("SVN Access Manager")." - ".$_SERVER['HTTP_HOST']; ?></title>
  <meta name="GENERATOR" content="Quanta Plus">
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15">
  <link rel="stylesheet" href="./stylesheet.css" type="text/css" />
</head>
<body>
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr valign="top">
			<td width="200px" bgcolor="#EAEAE8">
				<a href="http://www.svn-access-manager.org/" target="_blank">
					<img src="./images/svn-access-manager_200_60.jpg" width="200" height="60" border="0" />
				</a>
			</td>
			<td>
				<div id="header_right" bgcolor="#EAEAE8">
					<?php outputHeader($header); ?>
				</div>
			</td>
		</tr>
		<tr valign="top">
			<td width="200px">
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
			<td width="200px" bgcolor="#EAEAE8">
				<div id="left" class="leftMenu">
					<ul>
						<?php outputMenu($menu); ?>
					</ul>
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