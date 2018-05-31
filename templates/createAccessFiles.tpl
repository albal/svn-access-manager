<div>      
    <h3 class="page-header"><?php print _("Create Access Files"); ?></h3> 
</div>
<?php 
    outputMessage($tMessage, $tMessageType);
?>
<div>
    <form class="form-horizontal" name="createaccessfiles" method="post">
        <div class="form-group <?php print $tHidden; ?>">
            <label class="col-sm-3 control-label" for="viewvcconfig"><?php print _("Create viewvc configuration"); ?>:</label>
            <div class="col-sm-9">
                <label class="radio-inline"><input id="viewvcconfig" type="radio" name="fViewvcConfig" data-toggle="tooltip" title="<?php print _("Viewvc configuration is genereated for Apache webservers only!"); ?>" value="YES" <?php print $tViewvcConfigYes; ?>><?php print _("Yes"); ?></label>
                <label class="radio-inline"><input id="viewvcconfig" type="radio" name="fViewvcConfig" data-toggle="tooltip" title="<?php print _("Viewvc configuration is genereated for Apache webservers only!"); ?>" value="NO" <?php print $tViewvcConfigNo; ?>><?php print _("No"); ?></label>
            </div>
        </div>
        <div class="form-group <?php print $tHidden; ?>">
            <label class="col-sm-3 control-label" for="reloadws"><?php print _("Command to reload webserver configuration"); ?>:</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="reloadws" name="fReload" value="<?php print $tReload; ?>" data-toggle="tooltip" title="<?php print _("The user running this webserver must be able to execute the command!"); ?>" />
            </div>
        </div>
        <div class="form-group">
            <p class="form-control-static"><?php print _("Create files for user authentication and access rights?");?></p>
        </div>
        <div class="input-group">
            <p>&nbsp;</p>
        </div>    
        <div class="input-group">
            <button class="btn btn-sm btn-primary" data-toggle="tooltip" type="submit" name="fSubmit_y" title="<?php print _("Yes"); ?>"><span class="glyphicon glyphicon-ok"></span> <?php print _("Yes"); ?></button>
            <button class="btn btn-sm" data-toggle="tooltip" type="submit" name="fSubmit_n" title="<?php print _("No"); ?>"><span class="glyphicon glyphicon-remove"></span> <?php print _("No"); ?></button>
        </div>
        <div class="input-group">
            <p>&nbsp;</p>
        </div>
    </form>
</div>
<script>
$(document).ready(function() {
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip({animation: true, delay: {show: <?php print $CONF[TOOLTIP_SHOW]; ?>, hide: <?php print $CONF[TOOLTIP_HIDE]; ?>}}); 
    });
} );
</script>
