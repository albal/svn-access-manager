<div>      
    <h3 class="page-header"><?php print _("Group access right administration / Step 2: select user and access rights"); ?></h3> 
</div>
<div>
    <form class="form-horizontal" name="workOnGroupAccessRight" method="post">
        <div class="form-group <?php print outputResponseClasses($tUserError); ?>">
            <label class="col-sm-3 control-label" for="name"><?php print _("Group"); ?>:</label>
            <div class="col-sm-3">
                <p class="form-control-static"><?php print $tGroupName;?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="user"><?php print _("Group members"); ?>:</label>
            <div class="col-sm-4">
                <select class="selectpicker" name="fUser" id="user" <?php print $tReadonly;?> data-toggle="tooltip" title="<?php print _("Select the user to grant access to the group.");?>" >
                    <?php
                        foreach($tMembers as $uid => $member) {
                            $label = $member." [".$uid."]";
                            print "\t\t\t\t\t\t\t<option value=\"$uid\" label=\"$label\">$label</option>\n";
                        }  
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group <?php print outputResponseClasses($tRightError); ?>">
            <label class="col-sm-2 control-label" for="right"><?php print _("Access right"); ?>:</label>
            <div class="col-sm-4">
                <select class="selectpicker" name="fRight" id="right" data-toggle="tooltip" title="<?php print _("Select the access right to the group.");?>" >
                    <?php
                        $none                           = "";
                        $read                           = "";
                        $edit                           = "";
                        $delete                         = "";
                        
                        if( $tRight == "none" ) {
                            $none                       = SELECTED;
                        }
                        if( $tRight == "read" ) {
                            $read                       = SELECTED;
                        }
                        if( $tRight == "edit" ) {
                            $edit                       = SELECTED;
                        }
                        if( $tRight == "delete" ) {
                            $delete                     = SELECTED;
                        }
                    
                        print "\t\t\t\t\t\t\t<option value='read' ".$read." >"._("read")."</option>\n";
                        print "\t\t\t\t\t\t\t<option value='edit' ".$edit." >"._("edit")."</option>\n";
                        print "\t\t\t\t\t\t\t<option value='delete' ".$delete." >"._("delete")."</option>\n"; 
                    ?>
                </select>
            </div>
        </div>
        <div class="input-group">
            <p>&nbsp;</p>
        </div>    
        <div class="input-group">
            <button class="btn btn-sm btn-primary" data-toggle="tooltip" type="submit" name="fSubmit_ok" title="<?php print _("Save"); ?>"><span class="glyphicon glyphicon-save"></span> <?php print _("Save"); ?></button>
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
<script type="text/javascript">
    
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip(); 
    });
</script>
