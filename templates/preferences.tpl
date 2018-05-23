<div>  
    <h3 class="page-header"><?php print _("Preferences"); ?></h3> 
</div>
<div>
    <form class="form-horizontal" name="preferences" method="post">
        <div class="form-group <?php print outputResponseClasses($tPageSizeError); ?>">
            <label class="col-sm-2 control-label" for="pagesize"><?php print _("Records per page"); ?>:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="pagesize" name="fPageSize" value="<?php print $tPageSize; ?>" size="3" maxsize="3" data-toggle="tooltip" title="<?php print _("Number of lines of a table shown on a page.");?>" />
                <?php print outputResponseSpan($tPageSizeError); ?>
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
