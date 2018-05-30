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
            <div class="logoBar nojs">
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
        <div> <!-- install content -->
            <form class="form-horizontal" name="installform" method="post">
                <ul class="nav nav-tabs">              
                    <li class="active"><a data-toggle="tab" href="#tabs-0"><?php print _("Instructions");?></a></li>
                    <li><a data-toggle="tab" href="#tabs-1"><?php print _("Database");?></a></li>
                    <li><a data-toggle="tab" href="#tabs-2"><?php print _("LDAP");?></a></li>
                    <li><a data-toggle="tab" href="#tabs-3"><?php print _("Website");?></a></li>
                    <li><a data-toggle="tab" href="#tabs-4"><?php print _("Administrator");?></a></li>
                    <li><a data-toggle="tab" href="#tabs-5"><?php print _("SVN Webserver");?></a></li>
                    <li><a data-toggle="tab" href="#tabs-6"><?php print _("Misc.");?></a></li>
                    <li><a data-toggle="tab" href="#tabs-7"><?php print _("Install results");?></a></li>
                </ul>
                
                <div class="tab-content"> <!-- install content -tabs -->
                    <div id="tabs-0" class="tab-pane fade in active">
                        <?php include ("../templates/install-tab0.tpl"); ?>                        
                    </div>
                      
                    <div id="tabs-1" class="tab-pane fade">
                        <?php include ("../templates/install-tab1.tpl"); ?> 
                    </div>
                      
                    <div id="tabs-2" class="tab-pane fade">
                        <?php include ("../templates/install-tab2.tpl"); ?> 
                    </div>
                      
                    <div id="tabs-3" class="tab-pane fade">
                        <?php include ("../templates/install-tab3.tpl"); ?> 
                    </div>
                      
                    <div id="tabs-4" class="tab-pane fade">
                       <?php include ("../templates/install-tab4.tpl"); ?> 
                    </div>
                      
                    <div id="tabs-5" class="tab-pane fade">
                        <?php include ("../templates/install-tab5.tpl"); ?> 
                    </div>
                      
                    <div id="tabs-6" class="tab-pane fade">
                        <?php include ("../templates/install-tab6.tpl"); ?> 
                    </div>
                      
                    <div id="tabs-7" class="tab-pane fade">
                        <?php include ("../templates/install-tab7.tpl"); ?> 
                    </div>
                  
                </div> <!-- install content tabs -->
                <div class="input-group">
                    <p>&nbsp;</p>
                </div>    
                <div class="input-group pull-left">
                    <button class="btn btn-primary btn-sm" data-toggle="tooltip" type="submit" name="fSubmit_install" title="<?php print _("Start installation"); ?>"><span class="glyphicon glyphicon-ok"></span> <?php print _("Start installation"); ?></button>
                </div>
                <div class="input-group">
                    <p>&nbsp;</p>
                </div>
                <input type="hidden" id="errors" name="fPage" value="<?php print $tPage;?>" />
            </form>
            
        </div> <!-- install content -->
        
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
            $('[data-toggle="tooltip"]').tooltip({animation: true, delay: {show: 800, hide: 100}}); 
            
            page = $( "#errors" ).val();
            ref = "#tabs-" + page;

            $('.nav-tabs li:eq(page) a').tab('show');
            $('.nav-tabs a[href="' + ref + '"]').tab('show');
        });
    </script>
  </body>
</html>
