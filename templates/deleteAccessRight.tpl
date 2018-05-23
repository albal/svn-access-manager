<div>    
    <h3 class="page-header"><?php print _("Access right administration / delete access rights"); ?></h3> 
</div>
<div>
    <form class="form-horizontal" name="deleteuser" method="post">
        <div class="form-group">
            <label class="col-sm-3 control-label" for="name"><?php print _("Project"); ?>:</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php print $tProjectName;?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="name"><?php print _("Subversion module path"); ?>:</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php print $tModulePath;?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="name"><?php print _("Selected directory"); ?>:</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php print $tPathSelected;?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="name"><?php print _("Access right"); ?>:</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php print $tAccessRight;?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="name"><?php print _("Valid from"); ?>:</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php print $tValidFrom;?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="name"><?php print _("Valid from"); ?>:</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php print $tValidUntil;?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="name"><?php print _("Allowed users"); ?>:</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php print $tUsers;?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="name"><?php print _("Allowed groups"); ?>:</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php print $tGroups;?></p>
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
