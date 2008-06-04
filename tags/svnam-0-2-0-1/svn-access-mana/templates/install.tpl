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
			<div id="header_left">
				<img src="../images/svn-access-manager_200_60_white.jpg" width="200" height="60" border="0" />
			</div>
			<div id="header_right">
				&nbsp;<br /><h1><?php print _("SVN Access Manager Installation"); ?></h1>
			</div>
			<div id="subheader">
				&nbsp;
			</div>
		</div>
		<div id="login">
			<form name="install" method="post">
				<table>
					<tr>
				   		<td colspan="3">&nbsp;</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td colspan="3" class="standout"><?php print $tMessage; ?></td>
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
					<legend><strong><?php print " "._("Database settings")." "; ?></strong></legend>
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
					   		<td><strong><?php print _("MySQL Database host").": "; ?></strong></td>
					   		<td>
					   			<input type="text" name="fDatabaseHost" value="<?php print $tDatabaseHost; ?>" size="40" />
					   		</td>
					   		<td>
					   			<?php print _("Enter the ip or the hostname of the MySQL database host"); ?>
					   		</td>
					   		<td>&nbsp;</td>
					   	</tr>
					   	<tr>
					   		<td><strong><?php print _("MySQL Database user").": "; ?></strong></td>
					   		<td>
					   			<input type="text" name="fDatabaseUser" value="<?php print $tDatabaseUser; ?>" size="40" />
					   		</td>
					   		<td>
					   			<?php print _("Enter the username for the MySQL database"); ?>
					   		</td>
					   		<td>&nbsp;</td>
					   	</tr>
					   	<tr>
					   		<td><strong><?php print _("MySQL Database password").": "; ?></strong></td>
					   		<td>
					   			<input type="text" name="fDatabasePassword" value="<?php print $tDatabasePassword; ?>" size="40" />
					   		</td>
					   		<td>
					   			<?php print _("Enter the password for the MySQL database"); ?>
					   		</td>
					   		<td>&nbsp;</td>
					   	</tr>
					   	<tr>
					   		<td><strong><?php print _("MySQL Database name").": "; ?></strong></td>
					   		<td>
					   			<input type="text" name="fDatabaseName" value="<?php print $tDatabaseName; ?>" size="40" />
					   		</td>
					   		<td>
					   			<?php print _("Enter the name of the MySQL database"); ?>
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
					<legend><strong><?php print " "._("Administrator account")." "; ?></strong></legend>
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
					   			<input type="password" name="fPassword" value="<?php print $tPassword; ?>" size="40" />
					   		</td>
					   		<td>
					   			<?php print _("Enter the password for the admin user. It must be 14 characters at least and consinst of digits, lower case characters, upper case characters and special characters."); ?>
					   		</td>
					   		<td>&nbsp;</td>
					   	</tr>
					   	<tr>
					   		<td nowrap><strong><?php print _("Retype admin password").": "; ?></strong></td>
					   		<td>
					   			<input type="password" name="fPassword2" value="<?php print $tPassword2; ?>" size="40" />
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
				</fieldset>
				
				<p>&nbsp;</p>
				
				<fieldset>
					<legend><strong><?php print " "._("SVN Webserver settings")." "; ?></strong></legend>
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
				</fieldset>
				
				<p>&nbsp;</p>
				
				<fieldset>
					<legend><strong><?php print " "._("Misc. settings")." "; ?></strong></legend>
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
				      		<td colspan="4">&nbsp;</td>
				   		</tr>
					</table>
				</fieldset>
				
				<table>
				   	<tr>
				      	<td colspan="4">&nbsp;</td>
				   	</tr>
				   	<tr>
				      	<td colspan="4" class="hlp_center">
				      		<input class="button" type="submit" name="fSubmit" value="<?php print _("Start installation"); ?>" />
				      	</td>
				   	</tr>
				</table>
			</form>
		</div>
	</div>
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
</body>
</html>