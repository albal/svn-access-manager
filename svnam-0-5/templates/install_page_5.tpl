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
					<td><h1><?php print _("SVN Access Manager Installation"); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php print _("Page 5/6"); ?></h1></td>
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