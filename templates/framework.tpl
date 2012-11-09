<?php
@header ("Expires: Sun, 16 Mar 2003 05:00:00 GMT");
@header ("Last-Modified: " . gmdate ("D, d M Y H:i:s") . " GMT");
@header ("Cache-Control: no-store, no-cache, must-revalidate");
@header ("Cache-Control: post-check=0, pre-check=0", false);
@header ("Pragma: no-cache");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<!--
    SVN Access Manager - a subversion access rights management tool
    Copyright (C) 2008 Thomas Krieger <tom@svn-access-manager.org>

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
  	<title><?php print _("SVN Access Manager")." - ".$_SERVER['HTTP_HOST']; ?></title>
  	<meta name="GENERATOR" content="Quanta Plus" />
  	<meta http-equiv="Content-Type" content="text/html; charset=<?php print $charset;?>" />
  	<!--<link rel="stylesheet" type="text/css" href="./style/lhelstyle.css" />-->
	<!--[if lt IE 9]>
		<link rel="stylesheet" type="text/css" href="./style/lhelie.css" />
	<![endif]-->
	<link type="text/css" href="./style/redmond/jquery-ui-1.8.17.custom.css" rel="stylesheet" />	
	<link rel="stylesheet" type="text/css" href="./style/jquery.tooltip.css" />
	<link rel="stylesheet" type="text/css" href="./style/chosen.css" />
	<link rel="stylesheet" type="text/css" href="./style/table.css" />
	<link rel="stylesheet" href="./stylesheet.css" type="text/css" />
	
	<script language="javascript" type="text/javascript" src="./lib/jquery/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="./lib/jquery-ui/js/jquery-ui-1.8.17.custom.min.js"></script>		
	<script language="javascript" type="text/javascript" src="./lib/jquery-ui/js/jquery.ui.datepicker-de.js"></script>
	<script language="JavaScript" type="text/javascript" src="./lib/jquery/jquery.tooltip.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="./lib/jquery/ui.ariaSorTable_min.js"></script>
	<script language="javaScript" type="text/javascript" src="./lib/jquery/jquery.timers-1.2.js"></script>
	<script language="javaScript" type="text/javascript" src="./lib/jquery/chosen.jquery.min.js"></script>
	<script language="javascript" type="text/javascript" src="./lib/jquery/table.js"></script>
	<script language="javascript" type="text/javascript">
		
		$(document).ready(function() {
            
            sess_ok = "<?php list( $ret, $dummy) = check_session_status(); print $ret;?>";
            if(sess_ok != "1") {
                    alert("<?php print _('Session expired. Please re-login!');?>");
                    window.location.href = "login.php";
            }
        });             
                
        $(document).everyTime(5000, function checkSession() {
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
</head>
<body  class="noscript">
	<script type="text/javascript">
		$("body").removeClass("noscript");
	</script>
	
	<div class="disabled">
		
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

	<div class="enabled">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr valign="top">
				<td width="200" bgcolor="#EAEAE8">
					<a href="http://www.svn-access-manager.org/" target="_blank">
						<img src="./images/svn-access-manager_200_60.jpg" width="200" height="60" border="0" />
					</a>
				</td>
				<td>
					<div id="header_right" class="bgEAEAE8">
						<?php outputHeader($header); ?>
					</div>
				</td>
			</tr>
			<tr valign="top">
				<td width="200">
					<div id="subheader_left">
						&nbsp;
					</div>
				</td>
				<td>
					<div id="subheader_right">
						<?php outputSubHeader($subheader); ?>
					</div>
				</td>
			</tr>
			<tr valign="top">
				<td width="200" bgcolor="#EAEAE8">
					<div id="left" class="leftMenu">
						<!--<ul> -->
							<?php outputMenu($menu); ?>
						<!--</ul>-->
					</div>
				</td>
				<td>
					<div id="right">
						<?php include( "./templates/".$template ); ?>
					</div>
				
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<?php include( "footer.tpl" ); ?>
				</td>
			</tr>
		</table>
	</div>  
	<div id="dialog-confirm" class="ui-dialog" style="display: none;">
			<p>
				<!--<span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;text-align:justified"></span>-->
				<span class="helpDialog">
				<?php 
					$dbh 										= db_connect ();
					$tText 										= array();
						
					if( isset( $_SESSION['svn_sessid']['helptopic'] ) ) {
							
						$schema									= db_determine_schema();
					    
						$lang									= check_language();
						$query									= "SELECT topic, headline_$lang AS headline, helptext_$lang AS helptext " .
																  "  FROM ".$schema."help " .
																  " WHERE topic = '".$_SESSION['svn_sessid']['helptopic']."'";
						$result									= db_query( $query, $dbh );
						
						if( $result['rows'] > 0 ) {
						
							$tText								= db_assoc( $result['result'] );
							
						} else {
							
							$tText['headline']					= _("No help found");
							$tText['helptext']					= sprintf( _("There is no help topic '%s' in the database"), $_SESSION['svn_sessid']['helptopic'] );
						}
						
					} else {
						
						$tText['headline']						= _("No help found");
						$tText['helptext']						= _("There is no help topic set");
							
					}
					
					$text_arr			= explode( "\r\n", $tText['helptext'] );
					print "<p class='helpDialog'>&nbsp;</p>\n";
					foreach( $text_arr as $text ) {
					
						print "<p class='helpDialog'>$text<br/>&nbsp;</p>\n";
						
					}
				?>
				</span>
			</p>
	</div>
	<script type="text/javascript">
		var dlg = $("#dialog-confirm").dialog({
			resizable: true,
			height: 300,
			width: 400,				
			modal: true,
			title: '<?php print _("Help")."::".$tText['headline'];?>',
			autoOpen: false,
		});
		
		$("#help").bind("click", function(event){
			event.preventDefault();
			
			if( event.stopPropagation ) {
				event.stopPropagation();
			} else {
				event.cancelBubble = true;
			}
			
			dlg.dialog("open");
		});
	</script>  
</body>
</html>