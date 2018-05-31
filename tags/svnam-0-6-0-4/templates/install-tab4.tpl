<div class="form-group">                         
    <p class="form-control-static">
        <?php print _("To get additional information just move the mouse over the input field or the label.");?>                               
    </p>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="adminuser" data-toggle="tooltip" title="<?php print _("Enter the username for the administrator account. If you use LDAP you must use a admin user which exists in the LDAP!"); ?>"><?php print _("Admin username"); ?>:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="adminuser" name="fUsername" value="<?php print $tUsername; ?>" data-toggle="tooltip" title="<?php print _("Enter the username for the administrator account. If you use LDAP you must use a admin user which exists in the LDAP!");?>" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="adminpw" data-toggle="tooltip" title="<?php print _("Enter the password for the admin user. It must be 14 characters at least and consinst of digits, lower case characters, upper case characters and special characters. It is not needed if you use LDAP."); ?>"><?php print _("Admin password"); ?>:</label>
    <div class="col-sm-4">
        <input type="password" class="form-control" id="adminpw" name="fPassword" value="<?php print $tPassword; ?>" autocomplete="off" data-toggle="tooltip" title="<?php print _("Enter the password for the admin user. It must be 14 characters at least and consinst of digits, lower case characters, upper case characters and special characters. It is not needed if you use LDAP.");?>" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="adminpw2" data-toggle="tooltip" title="<?php print _("Retype admin password"); ?>"><?php print _("Retype admin password"); ?>:</label>
    <div class="col-sm-4">
        <input type="password" class="form-control" id="adminpw2" name="fPassword2" value="<?php print $tPassword2; ?>" autocomplete="off" data-toggle="tooltip" title="<?php print _("Retype admin password");?>" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="admingivenname" data-toggle="tooltip" title="<?php print _("Administrator's given name"); ?>"><?php print _("Administrator's given name"); ?>:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="admingivenname" name="fGivenname" value="<?php print $tGivenname; ?>" data-toggle="tooltip" title="<?php print _("Admin given name");?>" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="adminname" data-toggle="tooltip" title="<?php print _("Admin's name"); ?>"><?php print _("Admin's name"); ?>:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="adminname" name="fName" value="<?php print $tName; ?>" data-toggle="tooltip" title="<?php print _("Admin name");?>" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="adminemail" data-toggle="tooltip" title="<?php print _("Enter the email address of the administrator."); ?>"><?php print _("Admin email address"); ?>:</label>
    <div class="col-sm-4">
        <input type="email" class="form-control" id="adminemail" name="fAdminEmail" value="<?php print $tAdminEmail; ?>" data-toggle="tooltip" title="<?php print _("Enter the email address of the administrator.");?>" />
    </div>
</div>
<script>
    $( "#adminpw" ).blur(function() {
    
        var pw1 = $( "#adminpw" ).val().trim();
        var pw2 = $( "#adminpw2" ).val().trim();
        
        if((pw1 != '' ) && (pw2 != '') && (pw1 != pw2)) {
            $("#adminpw").addClass("alert alert-danger");
            $("#adminpw2").addClass("alert alert-danger");
        } else {
            $("#adminpw").removeClass("alert alert-danger");
            $("#adminpw2").removeClass("alert alert-danger");
        }
    });
    
    $("#adminpw2").blur(function() {
    
        var pw1 = $("#adminpw").val();
        var pw2 = $("#adminpw2").val();
        
        if((pw1 != '') && (pw2 != '') && (pw1 != pw2)) {
            $("#adminpw").addClass("alert alert-danger");
            $("#adminpw2").addClass("alert alert-danger");
        } else {
            $("#adminpw").removeClass("alert alert-danger");
            $("#adminpw2").removeClass("alert alert-danger");
        }
    });
</script>
