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
		$charset		= $CONF['website_charset'];
	} else {
		$charset		= "iso8859-15";
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
    
    <!-- site sprcific styles -->
    <link href="./style/svnam.css" rel="stylesheet">
    
    <!-- Bootstrap formhelpers -->
    <link href="./lib/bootstrap-3.3.7/css/bootstrap-formhelpers.css" rel="stylesheet">
    <link href="./lib/bootstrap-3.3.7/css/bootstrap-formhelpers-countries.flags.css" rel="stylesheet">
    <link href="./lib/bootstrap-3.3.7/css/bootstrap-formhelpers-currencies.flags.css" rel="stylesheet">
    
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <!--<script src="./lib/bootstrap-3.3.7/js/ie-emulation-modes-warning.js"></script>-->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="./lib/bootstrap-3.3.7/js/html5shiv.min.js"></script>
      <script src="./lib/bootstrap-3.3.7/js/respond.min.js"></script>
    <![endif]-->
    
    
    <script language="javascript" type="text/javascript">
        
        $(document).ready(function() {
        
            $("#noJS").hide();
            $("#site").show();
            
            sess_ok = "<?php list( $ret, $dummy) = check_session_status(); print $ret;?>";
            if(sess_ok != "1") {
                    alert("<?php print _('Session expired. Please re-login!');?>");
                    window.location.href = "login.php";
            }
        });             
                
        $(document).everyTime("5s", function checkSession() {
             var dDate = new Date();
             var iTimeStamp = dDate.getTime();
             $.ajax({
                url: 'checkSession.php?antiCache='+iTimeStamp,
                success: function(newVal) {
                    if (newVal != 1) {
                            alert('<?php print _("Your session expired! Please re-login!");?>');
                            window.location.href = "login.php";
                    }
                  }
                });
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
	
	<?php outputMenu(); ?>

	<div class="container" id="site">
	
		<?php include( "./templates/".$template ); ?>
		
	</div>  <!--  end container -->
	
	<footer class="footer">
      <div class="container">
        <p class="text-muted small">
     
                <?php 
                    $datetime = htmlentities(strftime("%c"));
                ?>
            
                <div class="row">
                    <div class="col-sm-6 small"><?php global $CONF; print $CONF['copyright']." - PHP Version: ".PHP_VERSION; ?></div>
                    <div class="col-sm-3 small"><?php print $tBuildInfo; ?></div>
                    <div class="col-sm-3 small"><?php print $datetime; ?></div>
                </div>

        </p>
      </div>
    </footer>
	
    <div id="dialog-confirm" class="modal toggle fade" role="dialog">
      <div class="modal-dialog">
        <?php 
            $dbh                                        = db_connect ();
            $tText                                      = array();
                
            if( isset( $_SESSION[SVNSESSID][HELPTOPIC] ) ) {
                    
                $schema                                 = db_determine_schema();
                
                $lang                                   = check_language();
                $query                                  = "SELECT topic, headline_$lang AS headline, helptext_$lang AS helptext " .
                                                          "  FROM ".$schema."help " .
                                                          " WHERE topic = '".$_SESSION[SVNSESSID][HELPTOPIC]."'";
                $result                                 = db_query( $query, $dbh );
                
                if( $result['rows'] > 0 ) {
                
                    $tText                              = db_assoc( $result['result'] );
                    
                } else {
                    
                    $tText[HEADLINE]                    = _("No help found");
                    $tText[HELPTEXT]                    = sprintf( _("There is no help topic '%s' in the database"), $_SESSION[SVNSESSID][HELPTOPIC] );
                }
                
            } else {
                
                $tText[HEADLINE]                        = _("No help found");
                $tText[HELPTEXT]                        = _("There is no help topic set");
                    
            }
        ?>
                
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?php print _("Help")."::".$tText[HEADLINE];?></h4>
          </div>
          <div class="modal-body">
            
                <?php
                    $text_arr           = explode( "\r\n", $tText[HELPTEXT] );
                    foreach( $text_arr as $text ) {
                    
                        print "<p>$text<br/>&nbsp;</p>\n";
                        
                    }
                ?>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
    
      </div>
    </div>
    
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="./lib/bootstrap-3.3.7/js/ie10-viewport-bug-workaround.min.js"></script>
	<script src="./lib/bootstrap-3.3.7/js/bootstrap.min.js"></script>
	<script src="./lib/bootstrap-3.3.7/js/datatables.min.js"></script>
	<script src="./lib/bootstrap-3.3.7/js/bootstrap-select.min.js"></script>
	<script src="./lib/DataTables-1.10.16/js/dataTables.bootstrap.min.js"></script>
	
	<script src="./lib/bootstrap-3.3.7/js/bootstrap-formhelpers-selectbox.js"></script>
    <script src="./lib/bootstrap-3.3.7/js/bootstrap-formhelpers-countries.en_US.js"></script>
    <script src="./lib/bootstrap-3.3.7/js/bootstrap-formhelpers-languages.codes.js"></script>
    <script src="./lib/bootstrap-3.3.7/js/bootstrap-formhelpers-languages.js"></script>
    
	<script type="text/javascript">
		
		$("#help").bind("click", function(event){
			event.preventDefault();
			
			if( event.stopPropagation ) {
				event.stopPropagation();
			} else {
				event.cancelBubble = true;
			}
			
			$('#dialog-confirm').modal('show');
		});
	</script>  
</body>
</html>
