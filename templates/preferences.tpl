<div>  
    <h3 class="page-header"><?php print _("Preferences"); ?></h3> 
</div>
<?php 
    outputMessage($tMessage, $tMessageType);
?>
<div>
    <form class="form-horizontal" name="preferences" method="post">
        <div class="form-group <?php print outputResponseClasses($tPageSizeError); ?>">
            <label class="col-sm-2 control-label" for="pagesize"><?php print _("Records per page"); ?>:</label>
            <div class="col-sm-10">
                <!-- <input type="number" class="form-control" id="pagesize" name="fPageSize" value="<?php print $tPageSize; ?>" data-toggle="tooltip" size="3" maxsize="3" data-toggle="tooltip" title="<?php print _("Number of lines of a table shown on a page.");?>" /> -->
                <select class="form-control selectpicker" id="pagesize" name="fPageSize" data-toggle="tooltip" title="<?php print _("Number of lines of a table shown on a page.");?>">
                    <?php
                        foreach( $tRecordsPerPage as $key => $value ) {
                            $selected = ($tPageSize == $key) ? 'selected' : '';                            
                            print "\t\t\t\t<option value=\"".$key."\" $selected>".$value."</option>\n";
                        }
                    ?>
                </select>
                <?php print outputResponseSpan($tPageSizeError); ?>
            </div>
        </div>
        <div class="form-group <?php print outputResponseClasses($tTooltipShowError); ?>">
            <label class="col-sm-2 control-label" for="tooltipshow"><?php print _("Tooltip show (ms)"); ?>:</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" id="tooltipshow" name="fTooltipShow" value="<?php print $tTooltipShow; ?>" data-toggle="tooltip" size="5" maxsize="5" data-toggle="tooltip" title="<?php print _("Milliseconds until a tooltip shows up.");?>" />
                <?php print outputResponseSpan($tTooltipShowError); ?>
            </div>
        </div>
        <div class="form-group <?php print outputResponseClasses($tTooltipHideError); ?>">
            <label class="col-sm-2 control-label" for="tooltiphide"><?php print _("Tooltip hide (ms)"); ?>:</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" id="tooltiphide" name="fTooltipHide" value="<?php print $tTooltipHide; ?>" data-toggle="tooltip" size="5" maxsize="5" data-toggle="tooltip" title="<?php print _("Milliseconds until a tooltip vanishes.");?>" />
                <?php print outputResponseSpan($tTooltipHideError); ?>
            </div>
        </div>
        <div class="input-group">
            <p>&nbsp;</p>
        </div>    
        <div class="input-group">
            <button class="btn btn-sm btn-primary" data-toggle="tooltip" type="submit" name="fSubmit_ok" title="<?php print _("Submit"); ?>"><span class="glyphicon glyphicon-ok"></span> <?php print _("Submit"); ?></button>
            <button class="btn btn-sm" data-toggle="tooltip" type="submit" name="fSubmit_back" title="<?php print _("Back"); ?>"><span class="glyphicon glyphicon-menu-left"></span> <?php print _("Back"); ?></button>
        </div>
        <div class="input-group">
            <p>&nbsp;</p>
        </div>
    </form>
</div>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip({animation: true, delay: {show: <?php print $CONF[TOOLTIP_SHOW]; ?>, hide: <?php print $CONF[TOOLTIP_HIDE]; ?>}}); 
});
</script>
