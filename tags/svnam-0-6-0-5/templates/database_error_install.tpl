<?php
@header ("Expires: Sun, 16 Mar 2003 05:00:00 GMT");
@header ("Last-Modified: " . gmdate ("D, d M Y H:i:s") . " GMT");
@header ("Cache-Control: no-store, no-cache, must-revalidate");
@header ("Cache-Control: post-check=0, pre-check=0", false);
@header ("Pragma: no-cache");
?>
<!DOCTYPE html>
<html>
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
<?php
    global $CONF;
    
    if( isset( $CONF['website_charset'] ) ) {
        $charset        = $CONF['website_charset'];
    } else {
        $charset        = "iso8859-15";
    }
?>

  <head>
    <meta charset="<?php print $charset; ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="SVN Access Manager">
    <meta name="author" content="Thomas Krieger">
    <link rel="icon" href="favicon.ico">

    <title>SVN Access Manager</title>

    <script src="./lib/jquery/jquery-3.3.1.min.js"></script>
    <script>window.jQuery || document.write('<script src="./lib/jquery/jquery-3.3.1.min.js"><\/script>')</script>
    <script src="./lib/jquery/jquery.timers-1.2.js"></script>
    
    <!-- Bootstrap core CSS -->
    <link href="./lib/bootstrap-3.3.7/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="./lib/bootstrap-3.3.7/css/ie10-viewport-bug-workaround.min.css" rel="stylesheet">

    <!-- DataTable style -->
    <link href="./lib/bootstrap-3.3.7/css/datatables.min.css" rel="stylesheet">
    <link href="./lib/DataTables-1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="./lib/bootstrap-3.3.7/css/sticky-footer-navbar.css" rel="stylesheet">
    <link href="./lib/bootstrap-3.3.7/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="./style/svnam.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="./lib/bootstrap-3.3.7/js/html5shiv.min.js"></script>
      <script src="./lib/bootstrap-3.3.7/js/respond.min.js"></script>
    <![endif]-->
    
    
    <script language="javascript" type="text/javascript">
        
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
                    <img src="./images/svn-access-manager_200_60.jpg" width="200" height="60" border="0" />
                </a>
            </div>
            
            <!-- BEGIN Liquid Middle Column --> 
            <div class="Content">
               
                <p><?php print _("Javascript is disabled. This site needs JavaScript to work correctly. Please enable JavaScript in your browser!"); ?></p>
    
            </div>
            <!-- END Liquid Middle Column -->   
        </div>
    </noscript>

    <div class="container" id="site">
        <div>      
            <h3 class="page-header"><?php print _("Database error") ?></h3> 
        </div>
        <div class="alert alert-danger">
            <form class="form-horizontal" name="showuser" method="post">
                <div class_"input-group">
                    <?php print _("A database error occured:"); ?>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="name"><?php print _("Query"); ?>:</label>
                    <div class="col-sm-9">
                        <p class="form-control-static"><?php print $tDbQuery; ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label alert alert-danger" for="name"><?php print _("Errormessage"); ?>:</label>
                    <div class="col-sm-9">
                        <p class="form-control-static"><?php print $tDbError; ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="name"><?php print _("DB Function"); ?>:</label>
                    <div class="col-sm-9">
                        <p class="form-control-static"><?php print $tDbFunction; ?></p>
                    </div>
                </div>
            </form>
        </div>

    </div> <!-- container end -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="./lib/bootstrap-3.3.7/js/ie10-viewport-bug-workaround.min.js"></script>
    <script src="./lib/bootstrap-3.3.7/js/bootstrap.min.js"></script>

</body>
</html>
