<div>      
    <h3 class="page-header"><?php print _("Add group members"); ?></h3> 
</div>
<div>
    <form class="form-horizontal" action="workOnProject.php" name="workOnProject" method="post">
        <div class="form-group">
            <label class="col-sm-3 control-label" for="members"><?php print _("Choose the members to add"); ?>:</label>
            <div class="col-sm-9">
                <select id="members" name="groupadd[]" class="selectpicker" multiple size="15" data-toggle="tooltip" title="<?php print _("Select the groups to add.");?>">
                <?php
                    foreach($tGroups as $id => $name) {
                        
                        print "\t\t\t\t\t\t\t<option value=\"$id\" label=\"$name\">$name</option>\n";
                        
                    }  
                ?>
                </select>
            </div>
        </div>
        <div class="input-group">
            <p>&nbsp;</p>
        </div>    
        <div class="input-group">
            <button class="btn btn-sm btn-primary" data-toggle="tooltip" type="submit" name="fSubmitAddGroup_ok" title="<?php print _("Add"); ?>"><span class="glyphicon glyphicon-plus-sign"></span> <?php print _("Add"); ?></button>
            <button class="btn btn-sm" data-toggle="tooltip" type="submit" name="fSubmitAddGroup_back" title="<?php print _("Cancel"); ?>"><span class="glyphicon glyphicon-remove-circle"></span> <?php print _("Cancel"); ?></button>
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
