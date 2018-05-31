<div>      
    <h3 class="page-header"><?php print _("Group administration / edit group"); ?></h3> 
</div>
<?php 
    outputMessage($tMessage, $tMessageType);
?>
<div>
    <form class="form-horizontal" name="workOnGroup" method="post">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group <?php print outputResponseClasses($tGroupError); ?>">
                    <label class="col-sm-2 control-label" for="group"><?php print  _("Group"); ?>:</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="group" name="fGroup" value="<?php print $tGroup; ?>" data-toggle="tooltip" title="<?php print _("Enter the name of the group. The name must be unique.");?>" />
                        <?php print outputResponseSpan($tGroupError); ?>
                    </div>
                </div>
                <div class="form-group <?php print outputResponseClasses($tDescriptionError); ?>">
                    <label class="col-sm-2 control-label" for="description"><?php print  _("Description"); ?>:</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="description" name="fDescription" value="<?php print $tDescription; ?>" data-toggle="tooltip" title="<?php print _("Enter the group description.");?>" />
                        <?php print outputResponseSpan($tDescriptionError); ?>
                    </div>
                </div>
                <div class="input-group">
                    <p>&nbsp;</p>
                </div>    
                <div class="input-group">
                    <button class="btn btn-sm btn-primary" data-toggle="tooltip" type="submit" name="fSubmit_ok" title="<?php print _("Submit"); ?>"><span class="glyphicon glyphicon-save"></span> <?php print _("Submit"); ?></button>
                    <button class="btn btn-sm" data-toggle="tooltip" type="submit" name="fSubmit_back" title="<?php print _("Back"); ?>"><span class="glyphicon glyphicon-menu-left"></span> <?php print _("Back"); ?></button>
                </div>    
            </div>
            <div class="col-sm-6">
                <div class="form-group <?php print outputResponseClasses($tMembersError); ?>">
                    <label class="col-sm-2 control-label" for="umembersser"><?php print _("Group members"); ?>:</label>
                    <div class="col-sm-4">
                        <select class="form-control" size="5" name="members[]" multiple id="members" data-toggle="tooltip" title="<?php print _("Select the group members."); ?>">
                            <?php
                                foreach($tMembers as $uid => $member) {
                                    $label = $member." [".$uid."]";
                                    print "\t\t\t\t\t\t\t<option value=\"$uid\" label=\"$label\">$label</option>\n";
                                }  
                            ?>
                        </select>
                    </div>
                </div>
                <div class="input-group">
                    <p>&nbsp;</p>
                </div>    
                <div class="input-group">
                    <button class="btn btn-sm" data-toggle="tooltip" type="submit" name="fSubmit_add" title="<?php print _("Add member"); ?>"><span class="glyphicon glyphicon-plus-sign"></span> <?php print _("Add member"); ?></button>
                    <button class="btn btn-sm" data-toggle="tooltip" type="submit" name="fSubmit_remove" title="<?php print _("Remove member"); ?>"><span class="glyphicon glyphicon-erase"></span> <?php print _("Remove member"); ?></button>
                </div>
            </div>
        </div>
        <div class="input-group">
            <p>&nbsp;</p>
        </div>
    </form>
</div>
<script type="text/javascript">
    
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip({animation: true, delay: {show: <?php print $CONF[TOOLTIP_SHOW]; ?>, hide: <?php print $CONF[TOOLTIP_HIDE]; ?>}}); 
    });
</script>
