<div>    
    <h3 class="page-header"><?php print _("Date for access rights report"); ?></h3> 
</div>
<div>
    <form class="form-horizontal" name="getDateforAccessRights" method="post">
        <div class="form-group <?php print outputResponseClasses($tDateError); ?>">
            <label class="col-sm-1 control-label" for="date"><?php print _("Date"); ?>:</label>
            <div class="col-sm-3">
                <input type="date" class="form-control" id="date" name="fDate" value="<?php print $tDate; ?>" data-toggle="tooltip" title="<?php print _("Select the date for the report.");?>" />
                <?php print outputResponseSpan($tDateError); ?>
            </div>
        </div>
        <div class="input-group">
            <p>&nbsp;</p>
        </div>    
        <div class="input-group">
            <button class="btn btn-sm btn-primary btn-block" data-toggle="tooltip" type="submit" name="fSubmit_date" title="<?php print _("Create report"); ?>"><span class="glyphicon glyphicon-list"></span> <?php print _("Create report"); ?></button>
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
