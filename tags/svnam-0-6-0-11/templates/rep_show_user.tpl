<div id="dialoguser" class="modal toggle fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
        <form class="form-horizontal" name="showuserselect" method="post">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type="submit" name="fSubmit_back" id="cancel">&times;</button>
                <h4 class="modal-title"><?php print _("User to show");?></h4>
            </div>
            <div class="modal-body">
                <?php 
                    outputMessage($tMessage, $tMessageType);
                ?>
                
                <div class="form-group <?php print outputResponseClasses($tUserError); ?>">
                    <label class="col-sm-1 control-label" for="user"><?php print _("User"); ?>:</label>
                    <div class="col-sm-3">
                        <select class="selectpicker" name="fUser" id="user" data-toggle="tooltip" title="<?php print _("Select user."); ?>">
                            <?php
                                
                                foreach( $tUsers as $entry ) {
                                    
                                    if( $entry['givenname'] != "" ) {
                                        $name       = $entry['name'].", ".$entry['givenname'];
                                    } else {
                                        $name       = $entry['name'];
                                    }
                                     
                                    print "\t\t\t<option value='".$entry['id']."'>".$name." (".$entry['userid'].")</option>\n";
                                }
                            ?>
                        </select>
                        <?php print outputResponseSpan($tUserError); ?>
                    </div>
                </div>        
            </div>
            <div class="modal-footer">
                <div class="input-group">
                    <button class="btn btn-sm btn-primary" data-toggle="tooltip" type="submit" name="fSubmit_show" title="<?php print _("Create report"); ?>"><span class="glyphicon glyphicon-list"></span> <?php print _("Create report"); ?></button>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip({animation: true, delay: {show: <?php print $CONF[TOOLTIP_SHOW]; ?>, hide: <?php print $CONF[TOOLTIP_HIDE]; ?>}}); 
        
        $('#dialoguser').modal('show');
        
        $("button#submit").click(function() {
            $.ajax({
                type: "POST",
                url: "rep_show_user.php",
                data: $('form.showuserselect').serialize(),
                success: function(){
                    $("#dialoguser").modal('hide');
                }
            });
        });
        
        $("button#cancel").click(function(){
            window.location.assign("main.php");
        }); 
        
    });
</script>
