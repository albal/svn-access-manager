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
					<td><h1><?php print _("SVN Access Manager Installation"); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php print _("Page 2/6"); ?></h1></td>
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
				   	</tr>
				</table>
				
				<fieldset>
					<legend><strong><?php print " "._("Database settings - Part 2")." "; ?></strong></legend>
					<table>
						<tr>
				      		<td colspan="4">&nbsp;</td>
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
					   			<input type="text" name="fDatabasePassword" value="<?php print $tDatabasePassword; ?>" size="40" />
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
					</table>
				</fieldset>
				
				<p>&nbsp;</p>
				
				<fieldset>
					<legend><strong><?php print " "._("LDAP settings - Part 1")." "; ?></strong></legend>
					<table>
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
					</table>
				</fieldset>
				<p>&nbsp;</p>
				
				<table>
				   	<tr>
				      	<td colspan="4">&nbsp;</td>
				   	</tr>
				   	<tr>
				      	<td colspan="4" class="hlp_center">
				      		<input class="button" type="submit" name="fSubmit_back" value="<?php print _("Previous page"); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				      		<input class="button" type="submit" name="fSubmit_next" value="<?php print _("Next page"); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				      		<input class="button" type="submit" name="fSubmit_testdb" value="<?php print _("Test database connection"); ?>" />
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