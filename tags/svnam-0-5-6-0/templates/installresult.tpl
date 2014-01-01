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
			
				<table>
					<tr>
				   		<td colspan="3">&nbsp;</td>
				   		<td>&nbsp;</td>
				   	</tr>
				  	<tr>
				   		<td colspan="3"><h3><?php print _("Installation results"); ?></h3></td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td colspan="3"><?php print _("Results of the installation process:"); ?></td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td colspan="3">
				   			<blockquote>
				   			<?php  
				   				
				   				foreach( $tResult as $entry ) {
				   				
				   					print "\t\t\t\t\t\t\t- ".$entry."<br />\n";
				   					
				   				}
				   				
				   				
				   			?>
				   			</blockquote>
				   		</td>
				   		<td>&nbsp;</td>
				   	</tr>
				   	<tr>
				      	<td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td colspan="3">
				   			<?php print _("You can now proceed to login to the application with the administrator user created during installation."); ?><br />
				   			<?php print _("Click <a href='../' target='_self'>here</a> to go to the login screen."); ?>
				   		</td>
				   	</tr>
				   	<tr>
				      	<td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td colspan="3">
				   			<?php print _("And don't forget to setup your apache webserver with a configuration similar to this:"); ?> 
				   		</td>
				   	</tr>
				   	<tr>
				      	<td colspan="3">&nbsp;</td>
				   	</tr>
				   	<tr>
				   		<td colspan="3">
				   			<?php 
				   			if( $_SESSION['svn_inst']['useLdap'] == "YES" ) {
				   				printf( "
&lt;----- snip ----&gt;<br />
&nbsp;<br />
LDAPSharedCacheSize&nbsp;200000<br />
LDAPCacheEntries&nbsp;1024<br />
LDAPCacheTTL&nbsp;600<br />
LDAPOpCacheEntries&nbsp;1024<br />
LDAPOpCacheTTL&nbsp;600<br />
&nbsp;<br />
LoadModule dav_svn_module&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;modules/mod_dav_svn.so<br />
LoadModule authz_svn_module&nbsp;&nbsp;&nbsp;modules/mod_authz_svn.so<br />
&nbsp;<br />
Alias /svnstyle /usr/share/doc/subversion-1.4.2/tools/xslt/<br />
&nbsp;<br />
<Location /svn/repos><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DAV svn<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SVNParentPath /svn/repos<br />
&nbsp;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SSLRequireSSL<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AllowOverride&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ALL<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Satisfy&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;All<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AuthType&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Basic<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AuthBasicProvider&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ldap<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AuthName&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\"SVN LDAP Auth Test\"<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AuthLDAPURL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\"ldap://%s:%s/%s?%s?sub?(objectclass=*)\"<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AuthLDAPBindDN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%s<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AuthLDAPBindPassword&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%s<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AuthLDAPGroupAttribute&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;member<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AuthLDAPGroupAttributeIsDN&nbsp;&nbsp;&nbsp;on<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AuthzLDAPAuthoritative&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;off<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AuthLDAPCompareDNOnServer&nbsp;&nbsp;&nbsp;&nbsp;On<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Require&nbsp;valid-user<br />
&nbsp;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AuthzSVNAccessFile&nbsp;%s<br />
", $_SESSION['svn_inst']['ldapHost'], $_SESSION['svn_inst']['ldapPort'], $_SESSION['svn_inst']['ldapUserdn'], $_SESSION['svn_inst']['ldapUserFilter'], $_SESSION['svn_inst']['ldapBinddn'], $_SESSION['svn_inst']['ldapBindpw'], $_SESSION['svn_inst']['svnAccessFile'] );
								print <<<EOM
 <br />
        SVNIndexXSLT /svnstyle/svnindex.xsl<br />
 <br />
</Location><br />
 <br />
LogFormat "%t %u %{SVN-ACTION}e" svn_common<br />
CustomLog svn_common env=SVN-ACTION<br />
 <br />
#CustomLog logs/svn.log "%t %u %{SVN-ACTION}e" env=SVN-ACTION<br />
&lt;----- snip ----&gt;
EOM;
				   			} else {
				   				print <<<EOM
&lt;----- snip -----&gt;<br />
 <br />
Alias /svnstyle /var/www/apache2-default<br />
 <br />
&lt;Location /svn/repos&gt;<br />
 <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DAV svn<br />
 <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SVNParentPath /svn/repos<br />
 <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AuthType Basic<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AuthName \"Subversion Repository\"<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AuthUserFile $tAuthUserFile<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Require valid-user<br />
 <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AuthzSVNAccessFile $tSvnAccessFile<br />
 <br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SVNIndexXSLT /svnstyle/svnindex.xsl<br />
 <br />
&lt;/Location&gt;<br />
 <br />
CustomLog logs/svn.log \"%t %u %{SVN-ACTION}e\" env=SVN-ACTION<br />
 <br />
 &lt;----- snip -----&gt;
EOM;
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