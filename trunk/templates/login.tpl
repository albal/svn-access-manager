<?php
@header ("Expires: Sun, 16 Mar 2003 05:00:00 GMT");
@header ("Last-Modified: " . gmdate ("D, d M Y H:i:s") . " GMT");
@header ("Cache-Control: no-store, no-cache, must-revalidate");
@header ("Cache-Control: post-check=0, pre-check=0", false);
@header ("Pragma: no-cache");

include( "./include/output.inc.php" );
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
	<div id="wrap">
		<div id="header_login">
			<div id="header_left">
				<!--<img src="./images/svn-access-manager_200_60.jpg" width="200" height="60" border="0" />-->
			</div>
			<div id="header_right_login">
				
			</div>
			<div id="subheader">
				&nbsp;
			</div>
		</div>
		<div id="login">
			<form name="login" method="post" autocomplete="off">
				<table id="login_table" cellspacing="10">
				   <tr>
				      <td colspan="2" align="center">
				      	<a href="http://www.svn-access-manager.org/" target="_blank">
				      		<img src="./images/svn-access-manager_200_60.jpg" width="200" height="60" border="0" />
				      	</a>
				      </td>
				   </tr>
				   <tr>
				      <td colspan="2">&nbsp;</td>
				   </tr>
				   <tr>
				      <td colspan="2"><h4><?php print _('Subversion Administration Frontend'); ?></h4></td>
				   </tr>
				   <tr>
				      <td><?php print _('Username') . ":"; ?></td>
				      <td><input id="username" type="text" name="fUsername" value="<?php print $tUsername; ?>" /></td>
				   </tr>
				   <tr>
				      <td><?php print _('Password') . ":"; ?></td>
				      <td><input type="password" name="fPassword" autocomplete="off" /></td>
				   </tr>
				   <tr>
				      <td colspan="2">&nbsp;</td>
				   </tr>
				   <?php
				   		if( ( !isset($CONF['use_ldap']) ) or ((isset($CONF['use_ldap'])) and (strtoupper($CONF['use_ldap']) != "YES")) ) {
				   			
				   			print "\t\t\t\t\t\t<tr>\n";
				   	   		print "\t\t\t\t\t\t\t<td colspan=\"2\" class=\"hlp_center\"><a href=\"lostpassword.php\" target=\"_top\">"._("Lost password")."</a></td>\n";
				   			print "\t\t\t\t\t\t</tr>\n";
				   			
				   		}
				   ?>
				   <tr>
				      <td colspan="2">&nbsp;</td>
				   </tr>
				   <tr>
				      <td colspan="2" class="hlp_center">
				      	<input type="image" name="fSubmit_ok" src="./images/ok.png" value="<?php print _("Login"); ?>"  title="<?php print _("Login"); ?>" />
				      </td>
				   </tr>
				   <tr>
				      <td colspan="2" class="standout"><?php print $tMessage; ?></td>
				   </tr>
				</table>
			</form>
		</div>
	</div>
	<!--
	<div id="footer">
			<?php $datetime = strftime("%c"); ?>
			<table width="100%" cellspacing="0" border="0" cellpadding="0">
   				<tr>
       				<td nowrap>
						<?php print $CONF['copyright']; ?>
					</td>
					<td nowrap align="right">
						<?php print $datetime; ?>
					</td>
     			</tr>
 			</table>
	</div>
	-->
	<script type="text/javascript">
		document.forms.login.fUsername.focus();
	</script>
</body>
</html>