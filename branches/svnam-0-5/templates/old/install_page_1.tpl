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
<head>
  <title><?php print _("SVN Access Manager")." - ".$_SERVER['HTTP_HOST']; ?></title>
  <meta name="GENERATOR" content="Quanta Plus">
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15">
  <link rel="stylesheet" href="../stylesheet.css" type="text/css" />
</head>
<body>
	<div id="wrap">
		<div id="header_login">
		   	<table>
				<tr>
					<td><img src="../images/svn-access-manager_200_60_white.jpg" width="200" height="60" /></td>
					<td><h1><?php print _("SVN Access Manager Installation"); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php print _("Page 1/6"); ?></h1></td>
				</tr>
			</table>
		</div>
		<div id="login">
			<form name="install" method="post" id="installform">
				<table>
					<tr>
				   		<td colspan="3">&nbsp;</td>
				   		<td>&nbsp;</td>
				   	</tr>
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
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td colspan="3"><?php print _("Please fill in the values in the form below to start the installation of SVN Access Manager. For automatic database installation you need to have a database user with sufficient rights."); ?></td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td colspan="3"><?php print _("Please be sure that the webserver is able to write the config directory to create the config.inc.php file for you. To achieve this you can change the directory permissions to 'word writeable' for the time of installation. Please set the directory permissions back after installation."); ?></td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				      	<td colspan="3">&nbsp;</td>
				   	</tr>
				</table>
				
				<fieldset>
					<legend><strong><?php print " "._("Database settings - Part 1")." "; ?></strong></legend>
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
				      		<td colspan="4">&nbsp;</td>
				   		</tr>
					</table>
				</fieldset>
				
				<p>&nbsp;</p>
				
				<fieldset>
					<legend><strong><?php print " "._("LDAP settings - Part 1")." "; ?></strong></legend>
					
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
					   			<input type="text" name="fLdapBindpw" value="<?php print $tLdapBindpw; ?>" size="40" />
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
					</table>
				</fieldset>
				
				<p>&nbsp;</p>
				
				<table>
				   	<tr>
				      	<td colspan="4">&nbsp;</td>
				   	</tr>
				   	<tr>
				      	<td colspan="4" class="hlp_center">
				      		<input class="button" type="submit" name="fSubmit_next" value="<?php print _("Next page"); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				      		<input class="button" type="submit" name="fSubmit_testldap" value="<?php print _("Test LDAP connection"); ?>" />
				      	</td>
				   	</tr>
				</table>
			</form>
		</div>
	</div>
	<div id="footer">
			<?php 
				$datetime = strftime("%c");
				$datetime = str_replace( "ä", "&auml;", $datetime ); 
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
</body>
</html>