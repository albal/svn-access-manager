<div>     
    <h3 class="page-header"><?php print  _("Access right administration / Step 3: set access rights"); ?></h3> 
</div>
<div>
    <form class="form-horizontal" name="selectproject" method="post">
        <div class="form-group">
            <label class="col-sm-3 control-label" for="project"><?php print _("Project"); ?>:</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php print $tProjectName;?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="modpath"><?php print _("Subversion module path"); ?>:</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php print $tModulePath;?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="modpath"><?php print _("Selected directory"); ?>:</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php print $tPathSelected;?></p>
            </div>
        </div>
        <div class="form-group <?php print outputResponseClasses($tAccessRightError); ?>">
            <label class="col-sm-3 control-label" for="accessright"><?php print _("Create viewvc configuration"); ?>:</label>
            <div class="col-sm-9">
                <label class="radio-inline"><input id="accessright" type="radio" name="fAccessRight"  value="none" <?php print $tNone; ?> ><?php print _("None"); ?></label>
                <label class="radio-inline"><input id="accessright" type="radio" name="fAccessRight"  value="read" <?php print $tRead; ?> ><?php print _("Read"); ?></label>
                <label class="radio-inline"><input id="accessright" type="radio" name="fAccessRight"  value="write" <?php print $tWrite; ?> ><?php print _("Write"); ?></label>
                <?php print outputResponseSpan($tAccessRightError); ?>
            </div>
        </div>
        <div class="form-group <?php print outputResponseClasses($tValidFromError); ?>">
            <label class="col-sm-3 control-label" for="validfrom"><?php print _("Valid from"); ?>:</label>
            <div class="col-sm-9">
                <input type="date" class="form-control" id="validfrom" name="fValidFrom" value="<?php print $tValidFrom;?>" data-toggle="tooltip" title="<?php print _("Select the date the access right should be valid from.");?>" />
                <?php print outputResponseSpan($tValidFromError); ?>
            </div>
        </div>
        <div class="form-group <?php print outputResponseClasses($tValidUntilError); ?>">
            <label class="col-sm-3 control-label" for="validuntil"><?php print _("Valid until"); ?>:</label>
            <div class="col-sm-9">
                <input type="date" class="form-control" id="validuntil" name="fValidUntil" value="<?php print $tValidUntil;?>" data-toggle="tooltip" title="<?php print _("Select the date the access right would be revoked automatically.");?>" />
                <?php print outputResponseSpan($tValidUntilError); ?>
            </div>
        </div>
        <div class="form-group <?php print outputResponseClasses($tUsersError); ?>">
            <label class="col-sm-3 control-label" for="users"><?php print _("Allowed users"); ?>:</label>
            <div class="col-sm-9">
                <select class="selectpicker" name="fUsers[]" id="users" multiple data-toggle="tooltip" title="<?php print _("Select the users allowed to access.");?>" <?php print $tReadonly; ?> >
                    <?php
                        foreach($tUsers as $uid => $name) {
                                    
                            if( $uid == $tUid ) {
                                print "\t\t\t\t\t\t\t<option value=\"$uid\" label=\"$name ($uid)\" selected>$name ($uid)</option>\n";
                            } else {
                                print "\t\t\t\t\t\t\t<option value=\"$uid\" label=\"$name ($uid)\">$name ($uid)</option>\n";
                            }
                            
                        }   
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group <?php print outputResponseClasses($tGroupsError); ?>">
            <label class="col-sm-3 control-label" for="groups"><?php print _("Allowed groups"); ?>:</label>
            <div class="col-sm-9">
                <select class="selectpicker" name="fGroups[]" id="groups" multiple data-toggle="tooltip" title="<?php print _("Select the groups allowed to access.");?>" <?php print $tReadonly; ?> >
                    <?php
                        foreach($tGroups as $gid => $name) {
                            
                            if( $gid == $tGid ) {
                                print "\t\t\t\t\t\t\t<option value=\"$gid\" label=\"$name\" selected>$name</option>\n";
                            } else {
                                print "\t\t\t\t\t\t\t<option value=\"$gid\" label=\"$name\">$name</option>\n";
                            }
                            
                        }   
                    ?>
                </select>
            </div>
        </div>
        <div class="input-group">
            <p>&nbsp;</p>
        </div>    
        <div class="input-group">
            <button class="btn btn-sm btn-primary" data-toggle="tooltip" type="submit" name="fSubmit_ok" title="<?php print _("Submit"); ?>"><span class="glyphicon glyphicon-ok"></span> <?php print _("Submit"); ?></button>
            <button class="btn btn-sm" data-toggle="tooltip" type="submit" name="fSubmit_back" title="<?php print _("Back"); ?>"><span class="glyphicon glyphicon-arrow-left"></span> <?php print _("Back"); ?></button>
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
