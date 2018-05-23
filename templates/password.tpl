<div> 
    <p>&nbsp;</p>       
    <h3 class="page-header"><?php print _("Password change"); ?></h3> 
</div>
<div>
    <p><?php print _( "Note that your new password becomes valid for the SVN Access Manager Webinterface immediately, but may take some time for repository access itself. The latter depends from if and how your system administrator has setup the update-interval for passwords."); ?></p>
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
          <button class="btn btn-sm btn-primary btn-block" type="submit"><span class="glyphicon glyphicon-ok"></span> <?php print _("Change password"); ?></button>
      </div>
      <div class="input-group">
          <p>&nbsp;</p>
      </div>        
      
      <?php 
            outputMessage($tMessage, $tMessageType);
      ?>
    </form>
</div>      
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
</script>
      