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
					<td><h1><?php print _("SVN Access Manager Installation"); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php print _("Page 4/6"); ?></h1></td>
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
				
				<table>
				   	<tr>
				      	<td colspan="4">&nbsp;</td>
				   	</tr>
				   	<tr>
				      	<td colspan="4" class="hlp_center">
				      		<input class="button" type="submit" name="fSubmit_back" value="<?php print _("Previous page"); ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				      		<input class="button" type="submit" name="fSubmit_next" value="<?php print _("Next page"); ?>" />
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