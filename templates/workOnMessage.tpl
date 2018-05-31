<div>      
    <h3 class="page-header"><?php print _("Message administration"); ?></h3> 
</div>
<?php 
    outputMessage($tMessage, $tMessageType);
?>
<div>
    <form class="form-horizontal" name="workOnGroup" method="post">

        <div class="form-group <?php print outputResponseClasses($tValidFromError); ?>">
            <label class="col-sm-2 control-label" for="validfrom"><?php print  _("Valid from"); ?>:</label>
            <div class="col-sm-4">
                <input type="date" class="form-control" id="validfrom" name="fValidFrom" value="<?php print $tValidFrom; ?>" data-toggle="tooltip" title="<?php print _("Date the message is valid from"); ?>" />
                <?php print outputResponseSpan($tValidFromError); ?>
            </div>
        </div>
        <div class="form-group <?php print outputResponseClasses($tValidUntilError); ?>">
            <label class="col-sm-2 control-label" for="validuntil"><?php print  _("Valid until"); ?>:</label>
            <div class="col-sm-4">
                <input type="date" class="form-control" id="validuntil" name="fValidUntil" value="<?php print $tValidUntil; ?>" data-toggle="tooltip" title="<?php print _("Date until the message is valid"); ?>" />
                <?php print outputResponseSpan($tValidUntilError); ?>
            </div>
        </div>
        <div class="form-group <?php print outputResponseClasses($tUserMessageError); ?>">
            <label class="col-sm-2 control-label" for="message"><?php print  _("Message"); ?>:</label>
            <div class="col-sm-4">
                <textarea class="form-control" rows="5" id="message" name="fUserMessage"  data-toggle="tooltip" title="<?php print _("add a message"); ?>"><?php print $tUserMessage; ?></textarea>
                <?php print outputResponseSpan($tUserMessageError); ?>
            </div>
        </div>
        <div class="input-group">
            <p>&nbsp;</p>
        </div>    
        <div class="input-group">
            <button class="btn btn-sm btn-primary" data-toggle="tooltip" type="submit" name="fSubmit_ok" title="<?php print _("Save"); ?>"><span class="glyphicon glyphicon-save"></span> <?php print _("Save"); ?></button>
            <button class="btn btn-sm" data-toggle="tooltip" type="submit" name="fSubmit_back" title="<?php print _("Back"); ?>"><span class="glyphicon glyphicon-menu-left"></span> <?php print _("Back"); ?></button>
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
