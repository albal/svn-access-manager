<div>    
    <h3 class="page-header"><?php print _("Group administration / delete group"); ?></h3> 
</div>
<div>
    <form class="form-horizontal" name="deletegroupaccessright" method="post">
        <div class="form-group">
            <label class="col-sm-3 control-label" for="name"><?php print _("Group"); ?>:</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php print $tGroup;?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="name"><?php print _("Description"); ?>:</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php print $tDescription;?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="name"><?php print_("Group members"); ?>:</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php print $tMembers;?></p>
            </div>
        </div>
        <div class="input-group">
            <p>&nbsp;</p>
        </div>    
        <div class="input-group">
            <button class="btn btn-sm btn-primary" data-toggle="tooltip" type="submit" name="fSubmit_ok" title="<?php print _("Delete"); ?>"><span class="glyphicon glyphicon-erase"></span> <?php print _("Delete"); ?></button>
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
$(document).ready(function() {
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip(); 
    });
} );
</script>
