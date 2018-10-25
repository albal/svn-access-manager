<div id="dialogdate" class="modal toggle fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
        <form class="form-horizontal" name="getDateforAccessRights" method="post">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type="submit" name="fSubmit_cancel" id="cancel">&times;</button>
                <h4 class="modal-title"><?php print _("Date for access rights report");?></h4>
            </div>
            <div class="modal-body">
                <?php 
                    outputMessage($tMessage, $tMessageType);
                ?>
                
                <div class="form-group <?php print outputResponseClasses($tDateError); ?>">
                    <label class="col-sm-1 control-label" for="date"><?php print _("Date"); ?>:</label>
                    <div class="col-sm-4">
                        <input type="date" class="form-control" id="date" name="fDate" value="<?php print $tDate; ?>" data-toggle="tooltip" title="<?php print _("Select the date for the report.");?>" />
                        <?php print outputResponseSpan($tDateError); ?>
                    </div>
                </div>         
            </div>
            <div class="modal-footer">
                <div class="input-group">
                    <button class="btn btn-sm btn-primary btn-block" data-toggle="tooltip" type="submit" id="submit" name="fSubmit_date" title="<?php print _("Create report"); ?>"><span class="glyphicon glyphicon-list"></span> <?php print _("Create report"); ?></button>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip({animation: true, delay: {show: <?php print $CONF[TOOLTIP_SHOW]; ?>, hide: <?php print $CONF[TOOLTIP_HIDE]; ?>}}); 
        
        $('#dialogdate').modal('show');
        
        $("button#submit").click(function() {
            $.ajax({
                type: "POST",
                url: "rep_access_rights.php",
                data: $('form.getDateforAccessRights').serialize(),
                success: function(){
                    $("#dialogdate").modal('hide');
                }
            });
        });
        
        $("button#cancel").click(function(){
            window.location.assign("main.php");
        }); 
        
    });
</script>
