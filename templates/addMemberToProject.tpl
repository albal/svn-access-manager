<div>      
    <h3 class="page-header"><?php print _("Add project responsibles"); ?></h3> 
</div>
<div>
    <form class="form-horizontal" action="workOnProject.php" name="workOnProject" method="post">
        <div class="form-group">
            <label class="col-sm-3 control-label" for="members"><?php print _("Choose the responsibles to add"); ?>:</label>
            <div class="col-sm-9">
                <select id="members" name="membersadd[]" class="selectpicker" multiple size="15" data-toggle="tooltip" title="<?php print _("Select the users to add.");?>">
                <?php
                    foreach($tUsers as $uid => $name) {
                        
                        print "\t\t\t\t\t\t\t<option value=\"$uid\" label=\"$name ($uid)\">$name ($uid)</option>\n";
                        
                    }  
                ?>
                </select>
            </div>
        </div>
        <div class="input-group">
            <p>&nbsp;</p>
        </div>    
        <div class="input-group">
            <button class="btn btn-sm btn-primary" data-toggle="tooltip" type="submit" name="fSubmitAdd_ok" title="<?php print _("Add"); ?>"><span class="glyphicon glyphicon-plus-sign"></span> <?php print _("Add"); ?></button>
            <button class="btn btn-sm" data-toggle="tooltip" type="submit" name="fSubmitAdd_back" title="<?php print _("Cancel"); ?>"><span class="glyphicon glyphicon-remove-circle"></span> <?php print _("Cancel"); ?></button>
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
