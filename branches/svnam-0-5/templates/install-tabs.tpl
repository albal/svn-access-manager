<?php
@header ("Expires: Sun, 16 Mar 2003 05:00:00 GMT");
@header ("Last-Modified: " . gmdate ("D, d M Y H:i:s") . " GMT");
@header ("Cache-Control: no-store, no-cache, must-revalidate");
@header ("Cache-Control: post-check=0, pre-check=0", false);
@header ("Pragma: no-cache");

include( "../include/output.inc.php" );
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
  	
	<link type="text/css" href="../style/redmond/jquery-ui-1.8.6.custom.css" rel="stylesheet" />	
	<link rel="stylesheet" type="text/css" href="../style/jquery.tooltip.css" />
	<link rel="stylesheet" href="../stylesheet.css" type="text/css" />
	
	<script language="javascript" type="text/javascript" src="../lib/jquery/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="../lib/jquery-ui/js/jquery-ui-1.8.17.custom.min.js"></script>		
	<script language="javascript" type="text/javascript" src="../lib/jquery-ui/js/jquery.ui.datepicker-de.js"></script>
	<script language="JavaScript" type="text/javascript" src="../lib/jquery/jquery.tooltip.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="../lib/jquery/ui.ariaSorTable_min.js"></script>
</head>
<body class="noscript">
	<script type="text/javascript">
		$("body").removeClass("noscript");
	</script>
	
	<div class="disabled">
		
		<div class="logoBar">
			<a href="#">
				<img src="../images/svn-access-manager_200_60.jpg" width="200" height="60" border="0" />
			</a>
		</div>
		
		<!-- BEGIN Liquid Middle Column -->	
		<div class="Content">
		   
			<p><?php print _("Javascript is disabled. This site needs JavaScript to work correctly. Please enable JavaScript in your browser!"); ?></p>

		</div>
		<!-- END Liquid Middle Column -->	
	</div>

	<div class="enabled">
		<div id="wrap">
			<div id="header_login">
			   	<table>
					<tr>
						<td><img src="../images/svn-access-manager_200_60_white.jpg" width="200" height="60" /></td>
						<td><h1><?php print _("SVN Access Manager Installation"); ?></h1></td>
					</tr>
				</table>
			</div>
			<div id="install">
				<form name="install" method="post" id="installform1">
					
					<div id="installtabs">
						<ul>
							<li><a href="#tabs-0"><?php print _("Instructions");?></a></li>
							<li><a href="#tabs-1"><?php print _("Database");?></a></li>
							<li><a href="#tabs-2"><?php print _("LDAP");?></a></li>
							<li><a href="#tabs-3"><?php print _("Website");?></a></li>
							<li><a href="#tabs-4"><?php print _("Administrator");?></a></li>
							<li><a href="#tabs-5"><?php print _("SVN Webserver");?></a></li>
							<li><a href="#tabs-6"><?php print _("Misc.");?></a></li>
							<li><a href="#tabs-7"><?php print _("Install errors");?></a></li>
						</ul>
						<div id="tabs-0">
							<table>
								<tr>
									<td colspan="3"><h2><?php print _("Installation instructions");?></h2></td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td colspan="3">&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
							   		<td colspan="3">
							   			<?php 
							   				print _("Please fill in the values in the following tabs to start the installation of SVN Access Manager. For automatic database installation you need to have a database user with sufficient rights."); 
							   		    ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
									<td colspan="3">&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
							   	<tr>
							   		<td colspan="3">
							   			<?php 
							   				print _("Please be sure that the webserver is able to write the config directory '/etc/svn-access-manager/' on Unix/Linux to create the config.inc.php file for you. To achieve this you can either change the owner of the directory to the webserver user or change the directory permissions to 'word writable' for the time of installation. Please set the directory permissions back after installation if you set the permissions to 'world writable'."); 
							   			?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							      	<td colspan="3">&nbsp;</td>
							   	</tr>
							   	<?php
							   		if( ini_get( 'mysql.allow_persistent' ) != 1 ) {
							   			print "\t\t\t<tr>\n";
							   			print "\t\t\t\t<td colspan='3'>"._("Please make sure that in your php.ini file the varibale mysql.allow_persistent is set to on! Otherwise you may have problems with login after the installation. Please set the variable mysql.allow_persistent to on before you proceed with the installation. Don't forget to restart your webserver after changing the value of mysql.allow_persistent!")."</td>\n";
							   			print "\t\t\t\t<td>&nbsp;</td>\n";
							   			print "\t\t\t</tr>\n";
							   			print "\t\t\t<tr>\n";
							   			print "\t\t\t\t<td colspan='3'>&nbsp;</td>\n";
							   			print "\t\t\t\t<td>&nbsp;</td>\n";
							   			print "\t\t\t</tr>\n";
							   		}
							   	?>							   
							</table>
						</div>
						<div id="tabs-1" class="buttn">
							<table>
								<tr>
						      		<td colspan="4">&nbsp;</td>
						   		</tr>
						   		<tr>
							   		<td nowrap><strong><?php print _("Create datbase tables").": "; ?></strong></td>
							   		<td>
							   			<input type="radio" name="fCreateDatabaseTables" value="YES" <?php print $tCreateDatabaseTablesYes; ?> />&nbsp;&nbsp;<?php print _("Yes"); ?>&nbsp;&nbsp;&nbsp;
						   				<input type="radio" name="fCreateDatabaseTables" value="NO" <?php print $tCreateDatabaseTablesNo; ?> />&nbsp;&nbsp;<?php print _("No"); ?>
							   		</td>
							   		<td>
							   			&nbsp;
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td nowrap><strong><?php print _("Drop existing datbase tables").": "; ?></strong></td>
							   		<td>
							   			<input type="radio" name="fDropDatabaseTables" value="YES" <?php print $tDropDatabaseTablesYes; ?> />&nbsp;&nbsp;<?php print _("Yes"); ?>&nbsp;&nbsp;&nbsp;
						   				<input type="radio" name="fDropDatabaseTables" value="NO" <?php print $tDropDatabaseTablesNo; ?> />&nbsp;&nbsp;<?php print _("No"); ?>
							   		</td>
							   		<td>
							   			&nbsp;
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td nowrap><strong><?php print _("Database").": "; ?></strong></td>
							   		<td>
							   			<input type="radio" name="fDatabase" value="mysql" <?php print $tDatabaseMySQL; ?> />&nbsp;&nbsp;<?php print _("MySQL"); ?>&nbsp;&nbsp;&nbsp;
						   				<input type="radio" name="fDatabase" value="postgres8" <?php print $tDatabasePostgreSQL; ?> />&nbsp;&nbsp;<?php print _("PostgreSQL"); ?>&nbsp;&nbsp;&nbsp;
						   				<input type="radio" name="fDatabase" value="oci8" <?php print $tDatabaseOracle; ?> />&nbsp;&nbsp;<?php print _("Oracle"); ?>
							   		</td>
							   		<td>
							   			&nbsp;
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>					   	
							   	<tr>
							   		<td nowrap><strong><?php print _("Hold sessions in database").": "; ?></strong></td>
							   		<td>
							   			<input type="radio" name="fSessionInDatabase" value="YES" <?php print $tSessionInDatabaseYes; ?> />&nbsp;&nbsp;<?php print _("Yes"); ?>&nbsp;&nbsp;&nbsp;
						   				<input type="radio" name="fSessionInDatabase" value="NO" <?php print $tSessionInDatabaseNo; ?> />&nbsp;&nbsp;<?php print _("No"); ?>
							   		</td>
							   		<td>
							   			&nbsp;
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td><strong><?php print _("Database host").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fDatabaseHost" value="<?php print $tDatabaseHost; ?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the ip or the hostname of the database host"); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td><strong><?php print _("Database user").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fDatabaseUser" value="<?php print $tDatabaseUser; ?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the username for the database"); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td><strong><?php print _("Database password").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fDatabasePassword" value="<?php print $tDatabasePassword; ?>" size="40" autocomplete="off" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the password for the database"); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td><strong><?php print _("Database name").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fDatabaseName" value="<?php print $tDatabaseName; ?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the name of the database"); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td><strong><?php print _("Database charset").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fDatabaseCharset" value="<?php print $tDatabaseCharset; ?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the character set you want to use" ); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td><strong><?php print _("Database collation").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fDatabaseCollation" value="<?php print $tDatabaseCollation; ?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the collation you want to use" ); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td><strong><?php print _("Database schema").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fDatabaseSchema" value="<?php print $tDatabaseSchema; ?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the database schema you want to use" ); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
						      		<td colspan="4">&nbsp;</td>
						   		</tr>
						   		<tr>
						   			<td colspan="4">
						   				<input class="sbutton" type="submit" name="fSubmit_testdb" value="<?php print _("Test database connection"); ?>" />
						   			</td>
						   		</tr>
							</table>
						</div>
						<div id="tabs-2" class="buttn">
							<table>
								<tr>
						      		<td colspan="4">&nbsp;</td>
						   		</tr>
						   		<tr>
							   		<td nowrap><strong><?php print _("Use LDAP").": "; ?></strong></td>
							   		<td>
							   			<input type="radio" name="fUseLdap" value="YES" <?php print $tUseLdapYes; ?> />&nbsp;&nbsp;<?php print _("Yes"); ?>&nbsp;&nbsp;&nbsp;
						   				<input type="radio" name="fUseLdap" value="NO" <?php print $tUseLdapNo; ?> />&nbsp;&nbsp;<?php print _("No"); ?>
							   		</td>
							   		<td>
							   			&nbsp;
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td><strong><?php print _("LDAP host").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fLdapHost" value="<?php print $tLdapHost; ?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the ip or the hostname of the LDAP server"); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td><strong><?php print _("LDAP port").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fLdapPort" value="<?php print $tLdapPort; ?>" size="5" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the port to connect to the LDAP server"); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td nowrap><strong><?php print _("LDAP protocol").": "; ?></strong></td>
							   		<td>
							   			<input type="radio" name="fLdapProtocol" value="2" <?php print $tLdap2; ?> />&nbsp;&nbsp;<?php print _("2"); ?>&nbsp;&nbsp;&nbsp;
						   				<input type="radio" name="fLdapProtocol" value="3" <?php print $tLdap3; ?> />&nbsp;&nbsp;<?php print _("3"); ?>
							   		</td>
							   		<td>
							   			<?php print _("Choose the protocol for LDAP server communication."); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td><strong><?php print _("LDAP bind dn").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fLdapBinddn" value="<?php print $tLdapBinddn; ?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the dn to use for connect to the LDAP server."); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td><strong><?php print _("LDAP bind password").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fLdapBindpw" value="<?php print $tLdapBindpw; ?>" size="40" autocomplete="off" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the password for connect to the LDAP server."); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td><strong><?php print _("LDAP user dn").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fLdapUserdn" value="<?php print $tLdapUserdn; ?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the dn where the users are found on the LDAP server."); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td><strong><?php print _("LDAP user filter attribute").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fLdapUserFilter" value="<?php print $tLdapUserFilter; ?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the attribute to search for users."); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td><strong><?php print _("LDAP user object class").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fLdapUserObjectclass" value="<?php print $tLdapUserObjectclass; ?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the object class which identifies the users."); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td><strong><?php print _("LDAP user additional filter").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fLdapUserAdditionalFilter" value="<?php print $tLdapUserAdditionalFilter; ?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter additional filters for users if needed."); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td><strong><?php print _("Uid").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fLdapAttrUid" value="<?php print $tLdapAttrUid; ?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the attribute for the uid."); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td><strong><?php print _("Name").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fLdapAttrName" value="<?php print $tLdapAttrName; ?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the attribute for the name."); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td><strong><?php print _("Givenname").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fLdapAttrGivenname" value="<?php print $tLdapAttrGivenname; ?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the attribute for the given name."); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td><strong><?php print _("Mail").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fLdapAttrMail" value="<?php print $tLdapAttrMail; ?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the attribute for the email address."); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td><strong><?php print _("Password").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fLdapAttrPassword" value="<?php print $tLdapAttrPassword; ?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the attribute containing the user password."); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
						   			<td colspan="4">
						   				&nbsp;
						   			</td>
						   		</tr><tr>
						   			<td colspan="4">
						   				<input  id="ldapbutton" type="submit" name="fSubmit_testldap" value="<?php print _("Test LDAP connection"); ?>" />
						   			</td>
						   		</tr>
							</table>
						</div>
						<div id="tabs-3">
							<table>
								<tr>
						      		<td colspan="4">&nbsp;</td>
						   		</tr>
						   		<tr>
							   		<td nowrap><strong><?php print _("SVN Access Manager Website URL").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fWebsiteUrl" value="<?php print $tWebsiteUrl; ?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the URL which should be printed into expired password warning mail!" ); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
								<tr>
							   		<td nowrap><strong><?php print _("Website characterset").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fWebsiteCharset" value="<?php print $tWebsiteCharset; ?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the character set you want to use for the SVN Access Manager website. Please keep in mind that the characterset must be compatible to the database character set!" ); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td nowrap><strong><?php print _("Lost password mail sender").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fLpwMailSender" value="<?php print $tLpwMailSender;?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the email address to use as sender address for lost password emails."); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td nowrap><strong><?php print _("Lost password link valid").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fLpwLinkValid" value="<?php print $tLpwLinkValid;?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter tne number of days a lost password link will be valid."); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
						      		<td colspan="4">&nbsp;</td>
						   		</tr>
							</table>
						</div>
						<div id="tabs-4">
							<table>
								<tr>
						      		<td colspan="4">&nbsp;</td>
						   		</tr>
							   	<tr>
							   		<td><strong><?php print _("Admin username").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fUsername" value="<?php print $tUsername; ?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the username for the administrator account."); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td><strong><?php print _("Admin password").": "; ?></strong></td>
							   		<td>
							   			<input type="password" name="fPassword" value="<?php print $tPassword; ?>" size="40" autocomplete="off" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the password for the admin user. It must be 14 characters at least and consinst of digits, lower case characters, upper case characters and special characters."); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td nowrap><strong><?php print _("Retype admin password").": "; ?></strong></td>
							   		<td>
							   			<input type="password" name="fPassword2" value="<?php print $tPassword2; ?>" size="40" autocomplete="off" />
							   		</td>
							   		<td>
							   			<?php print _("Retype the admin's password."); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td nowrap><strong><?php print _("Admin's given name").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fGivenname" value="<?php print $tGivenname; ?>" size="40" />
							   		</td>
							   		<td>
							   			&nbsp;
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td><strong><?php print _("Admin's name").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fName" value="<?php print $tName; ?>" size="40" />
							   		</td>
							   		<td>
							   			&nbsp;
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td nowrap><strong><?php print _("Admin email address").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fAdminEmail" value="<?php print $tAdminEmail; ?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the email address of the administrator."); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
						      		<td colspan="4">&nbsp;</td>
						   		</tr>
							</table>
						</div>
						<div id="tabs-5">
							<table>
								<tr>
						      		<td colspan="4">&nbsp;</td>
						   		</tr>
						   		<tr>
							   		<td nowrap><strong><?php print _("Use SVN Access File").": "; ?></strong></td>
							   		<td>
							   			<input type="radio" name="fUseSvnAccessFile" value="YES" <?php print $tUseSvnAccessFileYes; ?> />&nbsp;&nbsp;<?php print _("Yes"); ?>&nbsp;&nbsp;&nbsp;
						   				<input type="radio" name="fUseSvnAccessFile" value="NO" <?php print $tUseSvnAccessFileNo; ?> />&nbsp;&nbsp;<?php print _("No"); ?>
							   		</td>
							   		<td>
							   			&nbsp;
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
						   		<tr>
							   		<td nowrap><strong><?php print _("SVN Access File").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fSvnAccessFile" value="<?php print $tSvnAccessFile; ?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the full path and the name of the SVN Access file. The webserver must be able to write the file."); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td nowrap><strong><?php print _("Access control level").": "; ?></strong></td>
							   		<td>
							   			<input type="radio" name="fAccessControlLevel" value="dirs" <?php print $tAccessControlLevelDirs; ?> />&nbsp;&nbsp;<?php print _("Directories"); ?>&nbsp;&nbsp;&nbsp;
						   				<input type="radio" name="fAccessControlLevel" value="files" <?php print $tAccessControlLevelFiles; ?> />&nbsp;&nbsp;<?php print _("Files"); ?>
							   		</td>
							   		<td>
							   			<?php print _("You can choose whether access control is possible on directories only or on directories and files."); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td nowrap><strong><?php print _("Use Auth User File").": "; ?></strong></td>
							   		<td>
							   			<input type="radio" name="fUseAuthUserFile" value="YES" <?php print $tUseAuthUserFileYes; ?> />&nbsp;&nbsp;<?php print _("Yes"); ?>&nbsp;&nbsp;&nbsp;
						   				<input type="radio" name="fUseAuthUserFile" value="NO" <?php print $tUseAuthUserFileNo; ?> />&nbsp;&nbsp;<?php print _("No"); ?>
							   		</td>
							   		<td>
							   			&nbsp;
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td nowrap><strong><?php print _("Auth User file").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fAuthUserFile" value="<?php print $tAuthUserFile; ?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the full path and the name of the Auth User file for the webserver authentication of users. The webserver must be able to write the file."); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td nowrap><strong><?php print _("Create per repository access files").": "; ?></strong></td>
							   		<td>
							   			<input type="radio" name="fPerRepoFiles" value="YES" <?php print $tPerRepoFilesYes; ?> />&nbsp;&nbsp;<?php print _("Yes"); ?>&nbsp;&nbsp;&nbsp;
						   				<input type="radio" name="fPerRepoFiles" value="NO" <?php print $tPerRepoFilesNo; ?> />&nbsp;&nbsp;<?php print _("No"); ?>
							   		</td>
							   		<td>
							   			&nbsp;
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td nowrap><strong><?php print _("Create ViewVC configuration").": "; ?></strong></td>
							   		<td>
							   			<input type="radio" name="fViewvcConfig" value="YES" <?php print $tViewvcConfigYes; ?> />&nbsp;&nbsp;<?php print _("Yes"); ?>&nbsp;&nbsp;&nbsp;
						   				<input type="radio" name="fViewvcConfig" value="NO" <?php print $tViewvcConfigNo; ?> />&nbsp;&nbsp;<?php print _("No"); ?>
							   		</td>
							   		<td>
							   			&nbsp;
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td nowrap><strong><?php print _("ViewVC configuration directory").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fViewvcConfigDir" value="<?php print $tViewvcConfigDir; ?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the full path to the directory where to save the viewvc configuration files. The webserver must be able to write the file."); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td nowrap><strong><?php print _("ViewVC realm").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fViewvcRealm" value="<?php print $tViewvcRealm; ?>" size="40" />
							   		</td>
							   		<td>
							   			&nbsp;
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td nowrap><strong><?php print _("ViewVC webserver alias").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fViewvcAlias" value="<?php print $tViewvcAlias; ?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the alias you used in your webserver:"); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td nowrap><strong><?php print _("ViewVC webserver reload command").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fViewvcApacheReload" value="<?php print $tViewvcApacheReload; ?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter a command to restart the Apache webserver. The command must be executable by the webserver user. You can use sudo to achieve this."); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td nowrap><strong><?php print _("svn command").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fSvnCommand" value="<?php print $tSvnCommand; ?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the full path and the name of the svn command."); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td nowrap><strong><?php print _("svnadmin command").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fSvnadminCommand" value="<?php print $tSvnadminCommand; ?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the full path and the name of the svnadmin command."); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td nowrap><strong><?php print _("grep command").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fGrepCommand" value="<?php print $tGrepCommand; ?>" size="40" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the full path and the name of the grep command."); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
						   		<tr>
						      		<td colspan="4">&nbsp;</td>
						   		</tr>
							</table>
						</div>
						<div id="tabs-6">
							<table>
								<tr>
						      		<td colspan="4">&nbsp;</td>
						   		</tr>
						   		<tr>
							   		<td nowrap><strong><?php print _("Use logging").": "; ?></strong></td>
							   		<td>
							   			<input type="radio" name="fLogging" value="YES" <?php print $tLoggingYes; ?> />&nbsp;&nbsp;<?php print _("Yes"); ?>&nbsp;&nbsp;&nbsp;
						   				<input type="radio" name="fLogging" value="NO" <?php print $tLoggingNo; ?> />&nbsp;&nbsp;<?php print _("No"); ?>
							   		</td>
							   		<td>
							   			&nbsp;
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<!--
							   	<tr>
							   		<td nowrap><strong><?php print _("Use JavaScript").": "; ?></strong></td>
							   		<td>
							   			<input type="radio" name="fJavaScript" value="YES" <?php print $tJavaScriptYes; ?> />&nbsp;&nbsp;<?php print _("Yes"); ?>&nbsp;&nbsp;&nbsp;
						   				<input type="radio" name="fJavaScript" value="NO" <?php print $tJavaScriptNo; ?> />&nbsp;&nbsp;<?php print _("No"); ?>
							   		</td>
							   		<td>
							   			&nbsp;
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	-->
							   	<tr>
							   		<td nowrap><strong><?php print _("Page size").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fPageSize" value="<?php print $tPageSize; ?>" size="4" maxsize="4" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the number of records of lists displayed on a page."); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
						   		<tr>
							   		<td nowrap><strong><?php print _("Minimal length for admin passwords").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fMinAdminPwSize" value="<?php print $tMinAdminPwSize; ?>" size="4" maxsize="4" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the minimal length for administrator passwords."); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
						   		<tr>
							   		<td nowrap><strong><?php print _("Minimal length for user passwords").": "; ?></strong></td>
							   		<td>
							   			<input type="text" name="fMinUserPwSize" value="<?php print $tMinUserPwSize; ?>" size="4" maxsize="4" />
							   		</td>
							   		<td>
							   			<?php print _("Enter the minimal length for user passwordws."); ?>
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
							   	<tr>
							   		<td nowrap><strong><?php print _("Use md5 encryption").": "; ?></strong></td>
							   		<td>
							   			<input type="radio" name="fUseMd5" value="md5" <?php print $tMd5Yes; ?> />&nbsp;&nbsp;<?php print _("Yes"); ?>&nbsp;&nbsp;&nbsp;
						   				<input type="radio" name="fUseMd5" value="crypt" <?php print $tMd5No; ?> />&nbsp;&nbsp;<?php print _("No"); ?>
							   		</td>
							   		<td>
							   			&nbsp;
							   		</td>
							   		<td>&nbsp;</td>
							   	</tr>
						   		<tr>
						      		<td colspan="4">&nbsp;</td>
						   		</tr>
							</table>
						</div>
						<div id="tabs-7" class="ui-tabs-hide">
							<table>
							   	<tr>
							   		<td colspan="3" class="standoutinst">
							   			<?php
							   				if( count($tErrors) > 0 ) {
							   				
							   					print "\t\t\t\t<p><strong>"._("Hints and errors").":</strong></p>\n";
							   					print "\t\t\t\t<ul>\n";
							   					
							   					foreach( $tErrors as $tMessage ) {
							   					
							   						print "\t\t\t\t\t<li>".$tMessage."</li>";
							   					
							   					} 
							   					
							   					print "\t\t\t\t</ul>\n";
							   				}
							   			?>
							   		</td>
							   	</tr>
							   	<tr>
							      	<td colspan="3">&nbsp;</td>
							   	</tr>
							</table>
						</div>
					</div>
					<!-- <div id="buttons" class="buttn"> -->
					<div class="buttn">
						<table>
						   	<tr>
						      	<td colspan="4">&nbsp;</td>
						   	</tr>
						   	<tr>
						      	<td colspan="4" class="hlp_center">
						      		<input  type="submit" name="fSubmit_install" value="<?php print _("Start installation"); ?>" />
						      	</td>
						   	</tr>
						</table>
					</div>
					<input type="hidden" id="errors" name="fPage" value="<?php print $tPage;?>" />
				</form>
				<script>
					$(function() {
						$( "#installtabs" ).tabs();
						var $tabs = $( "#installtabs" ).tabs();
						var $page = $( "#errors" ).val();
						$tabs.tabs('select', $page);
					});
					
					$(function() {
						$( "input:submit", ".buttn" ).button();
					});
				</script>
			</div>
		</div>
		<div id="footer">
				<?php 
					$datetime = strftime("%c");
					$datetime = str_replace( "�", "&auml;", $datetime ); 
				?>
				<table width="100%" cellspacing="0" border="0" cellpadding="0">
	   				<tr>
	       				<td nowrap>
							<?php $cr = isset( $CONF['copyright'] ) ? $CONF['copyright'] : ""; print $cr; ?>
						</td>
						<td nowrap align="right">
							<?php print $datetime; ?>
						</td>
	     			</tr>
	 			</table>
		</div>
	</div>
		
</body>
</html>
