<?php
@header ("Expires: Sun, 16 Mar 2003 05:00:00 GMT");
@header ("Last-Modified: " . gmdate ("D, d M Y H:i:s") . " GMT");
@header ("Cache-Control: no-store, no-cache, must-revalidate");
@header ("Cache-Control: post-check=0, pre-check=0", false);
@header ("Pragma: no-cache");

include( "../include/output.inc.php" );
?>
<!DOCTYPE html>
<html lang="<?php print check_language(); ?>">
<!--
    SVN Access Manager - a subversion access rights management tool
    Copyright (C) 2008-2018 Thomas Krieger <tom@svn-access-manager.org>

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
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="SVN Access Manager Installation">
    <meta name="author" content="Thomas Krieger">
    <link rel="icon" href="favicon.ico">

    <title>SVN Access Manager Installer</title>

    <script src="../lib/jquery/jquery-3.3.1.min.js"></script>
    <script src="../lib/jquery/jquery.timers-1.2.js"></script>
    
    <!-- Bootstrap core CSS -->
    <link href="../lib/bootstrap-3.3.7/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="../lib/bootstrap-3.3.7/css/ie10-viewport-bug-workaround.min.css" rel="stylesheet">

    <!-- DataTable style -->
    <link href="../lib/bootstrap-3.3.7/css/datatables.min.css" rel="stylesheet">
    <link href="../lib/DataTables-1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="../lib/bootstrap-3.3.7/css/sticky-footer-navbar.css" rel="stylesheet">
    <link href="../lib/bootstrap-3.3.7/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="../style/svnam.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../lib/bootstrap-3.3.7/js/html5shiv.min.js"></script>
      <script src="../lib/bootstrap-3.3.7/js/respond.min.js"></script>
    <![endif]-->
    
    <script src="../lib/jquery/jquery-3.3.1.min.js"></script>
    <script src="../lib/bootstrap-3.3.7/js/bootstrap.min.js"></script>
     <script>
      $(document).ready(function() {
          $("#noJS").hide();
          $("#site").show();
      });
    </script>
    <style>
    #site {
        display: none;
    }
    
    .logoBar {
        vertical-align:bottom;
        background-color:#efeff8;
        width:716px;
        height:70px;
    }
    
    .helpdialog {
        text-align: left;
        font-size: 1.1em;
    }
    </style>
</head>

<body>    
    <noscript>
        <div id="noJS">        
            <div class="logoBar">
                <a href="#">
                    <img src="../images/svn-access-manager_200_60.jpg" width="200" height="60" border="0" />
                </a>
            </div>        
            <div class="Content">       
                <p><?php print _("Javascript is disabled. This site needs JavaScript to work correctly. Please enable JavaScript in your browser!"); ?></p>
            </div>
        </div>
    </noscript>
    
    <div class="container" id="site">
        <div>      
            <h3 class="page-header"><?php print _("SVN Access Manager Installation"); ?></h3> 
        </div>
        <div>
            <h4><?php print _("Installation results"); ?></h4>
            <p><?php print _("Results of the installation process:"); ?></p>
            <p>
                <blockquote>
                <?php  
                    
                    foreach( $tResult as $entry ) {
                    
                        print "\t\t\t\t\t\t\t- ".$entry."<br />\n";
                        
                    }
                    
                    
                ?>
                </blockquote>
            </p>
            <p>
                <?php print _("You can now proceed to login to the application with the administrator user created during installation."); ?><br />
                <?php print _("Click <a href='../' target='_self'>here</a> to go to the login screen."); ?>
            </p>
            <p>
                <?php print _("And don't forget to setup your apache webserver with a configuration similar to this:"); ?> 
            </p>
            <p>
            <?php 
                            if( $_SESSION[SVN_INST]['useLdap'] == "YES" ) {
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
", $_SESSION[SVN_INST]['ldapHost'], $_SESSION[SVN_INST]['ldapPort'], $_SESSION[SVN_INST]['ldapUserdn'], $_SESSION[SVN_INST]['ldapUserFilter'], $_SESSION[SVN_INST]['ldapBinddn'], $_SESSION[SVN_INST]['ldapBindpw'], $_SESSION[SVN_INST]['svnAccessFile'] );
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
            </p>
        </div>
    </div> <!-- /container -->
    
    <footer class="footer">
      <div class="container">
        <p class="text-muted small">
            &copy; 2018 Thomas Krieger. All rights reserved.
        </p>
      </div>
    </footer>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../lib/bootstrap-3.3.7/js/ie10-viewport-bug-workaround.min.js"></script>
    <script src="../lib/bootstrap-3.3.7/js/bootstrap.min.js"></script>
    <script src="../lib/bootstrap-3.3.7/js/datatables.min.js"></script>
    <script src="../lib/bootstrap-3.3.7/js/bootstrap-select.min.js"></script>
    <script src="../lib/DataTables-1.10.16/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript">
    
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip({animation: true, delay: {show: 00, hide: 100}}); 
            
            page = $( "#errors" ).val();
            ref = "#tabs-" + page;

            $('.nav-tabs li:eq(page) a').tab('show');
            $('.nav-tabs a[href="' + ref + '"]').tab('show');
        });
    </script>
  </body>
</html>
