<div>    
    <h3 class="page-header"><?php print _("Message administration / delete message"); ?></h3> 
</div>
<?php 
    outputMessage($tMessage, $tMessageType);
?>
<div>
    <form class="form-horizontal" name="deletemessage" method="post">
        <div class="form-group">
            <label class="col-sm-3 control-label" for="name"><?php print _("Valid from"); ?>:</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php print $tValidFrom;?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="name"><?php print _("Valid until"); ?>:</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php print $tValidUntil;?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="name"><?php print _("Message"); ?>:</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php print $tUserMessage;?></p>
            </div>
        </div>
        <div class="input-group">
            <p>&nbsp;</p>
        </div>    
        <div class="input-group">
            <button class="btn btn-sm btn-primary" data-toggle="tooltip" type="submit" name="fSubmit_ok" title="<?php print _("Delete"); ?>"><span class="glyphicon glyphicon-erase"></span> <?php print _("Delete"); ?></button>
            <button class="btn btn-sm" data-toggle="tooltip" type="submit" name="fSubmit_back" title="<?php print _("Back"); ?>"><span class="glyphicon glyphicon-menu-left"></span> <?php print _("Back"); ?></button>
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
