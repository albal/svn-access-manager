<div>   
    <h3 class="page-header"><?php print _("Password change"); ?></h3> 
</div>
<?php 
      outputMessage($tMessage, $tMessageType);
?>
<div>
    <div class="alert alert-info">
        <?php
            if( ($_SESSION[SVNSESSID]['admin'] == "y") || ($_SESSION[SVNSESSID]['admin'] == "p") ) {
                
                $len            = $CONF['minPasswordlength'];
                
            } else {
                
                $len            = $CONF['minPasswordlengthUser'];
                
            }
            
            if( isset($CONF['minPasswordGroups']) ) {
                $minGroups      = $CONF['minPasswordGroups'];
            } else {
                $minGroups      = 4;
            }
            if( isset($CONF['minPasswordGroupsUser']) ) {
                $minGroupsUser  = $CONF['minPasswordGroupsUser'];
            } else {
                $minGroupsUser  = 3;
            }
            
            $msg = sprintf( _("<p><strong>Password policy</strong></p>
            <p>A password must consist of %s characters at least. It must include one character 
            of the %s groups digits, lower case characters. upper case characters and special 
            characters for adminitrator passwords. User passwords must include %s of the four 
            groups mentioned above.</p>
            <p>The following special characters are allowed: %s</p>"), $len, $minGroups, $minGroupsUser, htmlspecialchars($CONF['passwordSpecialCharsTxt']));
            print $msg;
        ?>
    </div>
    <p class="alert alert-info"><?php print _( "Note that your new password becomes valid for the SVN Access Manager Webinterface immediately, but may take some time for repository access itself. The latter depends from if and how your system administrator has setup the update-interval for passwords."); ?></p>
    <form name="passwordchange" method="post">
      <div class="form-group <?php print outputResponseClasses($tCurrentError); ?>">
        <label class="col-sm-2 control-label" for="currpass"><?php print _("Current password"); ?>:</label>
        <div class="col-sm-10">
            <input type="password" class="form-control" id="currpass" name="fPassword_current" autocomplete="off">
            <?php print outputResponseSpan($tCurrentError); ?>
        </div>
      </div>
      <div class="form-group <?php print outputResponseClasses($tPasswordError); ?>">
        <label class="col-sm-2 control-label" for="pwd"><?php print _("New password"); ?>:</label>
        <div class="col-sm-10">
            <input type="password" class="form-control" id="pwd" name="fPassword" autocomplete="off">
            <?php print outputResponseSpan($tPasswordError); ?>
        </div>
      </div>
      <div class="form-group <?php print outputResponseClasses($tPassword2Error); ?>">
        <label class="col-sm-2 control-label" for="pwd2"><?php print _("Retype new password"); ?>:</label>
        <div class="col-sm-10">
            <input type="password" class="form-control" id="pwd2" name="fPassword2" autocomplete="off">
            <?php print outputResponseSpan($tPassword2Error); ?>
        </div>
      </div>
      <div class="input-group">
          <p>&nbsp;</p>
      </div>    
      <div class="input-group">
          <button class="btn btn-sm btn-primary" data-toggle="tooltip" type="submit" name="fSubmit_ok" title="<?php print _("Change password"); ?>"><span class="glyphicon glyphicon-ok"></span> <?php print _("Change password"); ?></button>
          <button class="btn btn-sm" data-toggle="tooltip" type="submit" name="fSubmit_back" title="<?php print _("Back"); ?>"><span class="glyphicon glyphicon-menu-left"></span> <?php print _("Back"); ?></button>
      </div>
      <div class="input-group">
          <p>&nbsp;</p>
      </div>        
    </form>
</div>      
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip({animation: true, delay: {show: <?php print $CONF[TOOLTIP_SHOW]; ?>, hide: <?php print $CONF[TOOLTIP_HIDE]; ?>}}); 
    
    $( "#adminpw" ).blur(function() {
    
        var pw1 = $( "#pwd" ).val().trim();
        var pw2 = $( "#pwd2" ).val().trim();
        
        if((pw1 != '' ) && (pw2 != '') && (pw1 != pw2)) {
            $("#pwd").addClass("alert alert-danger");
            $("#pwd2").addClass("alert alert-danger");
        } else {
            $("#pwd").removeClass("alert alert-danger");
            $("#pwd2").removeClass("alert alert-danger");
        }
    });
    
    $("#pwd2").blur(function() {
    
        var pw1 = $("#pwd").val();
        var pw2 = $("#pwd2").val();
        
        if((pw1 != '') && (pw2 != '') && (pw1 != pw2)) {
            $("#pwd").addClass("alert alert-danger");
            $("#pwd2").addClass("alert alert-danger");
        } else {
            $("#pwd").removeClass("alert alert-danger");
            $("#pwd2").removeClass("alert alert-danger");
        }
    });
});
</script>
      