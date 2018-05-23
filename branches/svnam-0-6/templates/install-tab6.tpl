<div class="form-group">                         
    <p class="form-control-static">
        <?php print _("To get additional information just move the mouse over the input field or the label.");?>                               
    </p>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="logging" data-toggle="tooltip" title="<?php print _("Decide if logging should be used.");?>"><?php print _("Use logging"); ?>:</label>
    <div class="col-sm-4">
        <label class="radio-inline"><input id="logging" type="radio" name="fLogging"  value="YES" <?php print $tLoggingYes; ?> ><?php print _("Yes"); ?></label>
        <label class="radio-inline"><input id="logging" type="radio" name="fLogging"  value="NO" <?php print $tLoggingNo; ?> ><?php print _("No"); ?></label>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="pagesize" data-toggle="tooltip" title="<?php print _("Enter the number of records of lists displayed on a page."); ?>"><?php print _("Page size"); ?>:</label>
    <div class="col-sm-4">
        <input type="number" class="form-control" id="pagesize" name="fPageSize" value="<?php print $tPageSize; ?>" data-toggle="tooltip" title="<?php print _("Enter the number of records of lists displayed on a page.");?>" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="minpwlen" data-toggle="tooltip" title="<?php print _("Enter the minimal length for administrator passwords."); ?>"><?php print _("Minimal length for admin passwords"); ?>:</label>
    <div class="col-sm-4">
        <input type="number" class="form-control" id="minpwlen" name="fMinAdminPwSize" value="<?php print $tMinAdminPwSize; ?>" data-toggle="tooltip" title="<?php print _("Enter the minimal length for administrator passwords.");?>" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="minuserpwlen" data-toggle="tooltip" title="<?php print _("Enter the minimal length for user passwords."); ?>"><?php print  _("Minimal length for user passwords"); ?>:</label>
    <div class="col-sm-4">
        <input type="number" class="form-control" id="minuerpwlen" name="fMinUserPwSize" value="<?php print $tMinUserPwSize; ?>" data-toggle="tooltip" title="<?php print _("Enter the minimal length for user passwords.");?>" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="dayspwvalid" data-toggle="tooltip" title="<?php print _("Enter the days a password is valid before it expires."); ?>"><?php print  _("Password expire days"); ?>:</label>
    <div class="col-sm-4">
        <input type="number" class="form-control" id="dayspwvalid" name="fPasswordExpire" value="<?php print $tPasswordExpire; ?>" data-toggle="tooltip" title="<?php print _("Enter the days a password is valid before it expires.");?>" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="warndays" data-toggle="tooltip" title="<?php print _("Enter the number of days after a user is warned before his password expires."); ?>"><?php print _("Warn password expires days"); ?>:</label>
    <div class="col-sm-4">
        <input type="number" class="form-control" id="warndays" name="fPasswordExpireWarn" value="<?php print $tPasswordExpireWarn; ?>" data-toggle="tooltip" title="<?php print _("Enter the number of days after a user is warned before his password expires.");?>" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="pwexpire" data-toggle="tooltip" title="<?php print _("Default value for password expiration.");?>"><?php print _("Passwords expire"); ?>:</label>
    <div class="col-sm-4">
        <label class="radio-inline"><input id="pwexpire" type="radio" name="fExpirePassword"  value="YES" <?php print $tExpirePasswordYes; ?> ><?php print _("Yes"); ?></label>
        <label class="radio-inline"><input id="pwexpire" type="radio" name="fExpirePassword"  value="NO" <?php print $tExpirePasswordNo; ?> ><?php print _("No"); ?></label>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="pwenc" data-toggle="tooltip" title="<?php print _("Choose password encryption.");?>"><?php print _("Password encryption"); ?>:</label>
    <div class="col-sm-4">
        <label class="radio-inline"><input id="pwenc" type="radio" name="fPwEnc"  value="sha" <?php print $fPwEnc; ?> ><?php print _("SHA"); ?></label>
        <label class="radio-inline"><input id="pwenc" type="radio" name="fPwEnc"  value="apr-md5" <?php print $fPwEnc; ?> ><?php print _("Apache MD5"); ?></label>
        <label class="radio-inline"><input id="pwenc" type="radio" name="fPwEnc"  value="md5" <?php print $tPwMd5; ?> ><?php print _("MD5"); ?></label>
        <label class="radio-inline"><input id="pwenc" type="radio" name="fPwEnc"  value="crypt" <?php print $tPwCrypt; ?> ><?php print _("Crypt"); ?></label>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="defaccess" data-toggle="tooltip" title="<?php print _("Set the default user access right for repositories.");?>"><?php print _("User default access right"); ?>:</label>
    <div class="col-sm-4">
        <label class="radio-inline"><input id="defaccess" type="radio" name="fUserDefaultAccess"  value="read" <?php print $tUserDefaultAccessRead; ?> ><?php print _("read"); ?></label>
        <label class="radio-inline"><input id="defaccess" type="radio" name="fUserDefaultAccess"  value="write" <?php print $tUserDefaultAccessWrite; ?> ><?php print _("write"); ?></label>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="cust1" data-toggle="tooltip" title="<?php print _("If you want to use custom fields fill in the label the field should have. If you do not fill in anything the custom field will not be used."); ?>"><?php print  _("Custom field 1"); ?>:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="cust1" name="fCustom1" value="<?php print $tCustom1; ?>" data-toggle="tooltip" title="<?php print _("If you want to use custom fields fill in the label the field should have. If you do not fill in anything the custom field will not be used.");?>" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="cust2" data-toggle="tooltip" title="<?php print _("If you want to use custom fields fill in the label the field should have. If you do not fill in anything the custom field will not be used."); ?>"><?php print  _("Custom field 2"); ?>:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="cust2" name="fCustom2" value="<?php print $tCustom2; ?>" data-toggle="tooltip" title="<?php print _("If you want to use custom fields fill in the label the field should have. If you do not fill in anything the custom field will not be used.");?>" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="cust3" data-toggle="tooltip" title="<?php print _("If you want to use custom fields fill in the label the field should have. If you do not fill in anything the custom field will not be used."); ?>"><?php print  _("Custom field 3"); ?>:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="cust3" name="fCustom3" value="<?php print $tCustom3; ?>" data-toggle="tooltip" title="<?php print _("If you want to use custom fields fill in the label the field should have. If you do not fill in anything the custom field will not be used.");?>" />
    </div>
</div>                            
