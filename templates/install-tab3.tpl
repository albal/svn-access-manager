<div class="form-group">                         
    <p class="form-control-static">
        <?php print _("To get additional information just move the mouse over the input field or the label.");?>                               
    </p>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="url" data-toggle="tooltip" title="<?php print _("Enter the URL which should be printed into expired password warning mail!" ); ?>"><?php print _("SVN Access Manager Website URL"); ?>:</label>
    <div class="col-sm-4">
        <input type="url" class="form-control" id="url" name="fWebsiteUrl" value="<?php print $tWebsiteUrl; ?>" data-toggle="tooltip" title="<?php print _("Enter the URL which should be printed into expired password warning mail!" );?>" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="webchar"  data-toggle="tooltip" title="<?php print _("Select the character set you want to use for the SVN Access Manager website. Please keep in mind that the characterset must be compatible to the database character set!" );?>"><?php print _("Website character set"); ?>:</label>
    <div class="col-sm-4">
        <select name="fWebsiteCharset" id="webchar" data-toggle="tooltip" title="<?php print _("Select the character set you want to use for the SVN Access Manager website. Please keep in mind that the characterset must be compatible to the database character set!" );?>" >
            <?php
                if( $tWebsiteCharset == "" ) {
                    $selected               = "selected=selected";
                } else {
                    $selected               = "";
                }
                
                print "\t\t\t\t<option value='' $selected>"._("--- Select character set ---")."</option>\n";
                
                foreach( $WEBCHARSETS as $entry ) {
                
                    if( strtolower($tWebsiteCharset) == strtolower($entry) ) {
                        $selected           = "selected=selected";
                    } else {
                        $selected           = "";
                    }
                    
                    print "\t\t\t\t<option value='".strtolower($entry)."' $selected>".strtolower($entry)."</option>\n";
                }
            ?>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="email" data-toggle="tooltip" title="<?php print _("Enter the email address to use as sender address for lost password emails."); ?>"><?php print _("Lost password mail sender"); ?>:</label>
    <div class="col-sm-4">
        <input type="email" class="form-control" id="email" name="fLpwMailSender" value="<?php print $tLpwMailSender; ?>" data-toggle="tooltip" title="<?php print _("Enter the email address to use as sender address for lost password emails.");?>" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="daysvalid" data-toggle="tooltip" title="<?php print _("Enter tne number of days a lost password link will be valid."); ?>"><?php print _("Lost password link valid"); ?>:</label>
    <div class="col-sm-4">
        <input type="number" class="form-control" id="daysvalid" name="fLpwLinkValid" value="<?php print $tLpwLinkValid; ?>" data-toggle="tooltip" title="<?php print _("Enter tne number of days a lost password link will be valid.");?>" />
    </div>
</div>
