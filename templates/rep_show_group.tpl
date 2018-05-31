<div id="dialoggroup" class="modal toggle fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
        <form class="form-horizontal" name="showgroupselect" method="post">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type="submit" name="fSubmit_back" id="cancel">&times;</button>
                <h4 class="modal-title"><?php print _("Group to show");?></h4>
            </div>
            <div class="modal-body">
                <?php 
                    outputMessage($tMessage, $tMessageType);
                ?>
                
                <div class="form-group <?php print outputResponseClasses($tGroupError); ?>">
                    <label class="col-sm-1 control-label" for="group"><?php print _("Group"); ?>:</label>
                    <div class="col-sm-3">
                        <select class="selectpicker" name="fGroup" id="group" data-toggle="tooltip" title="<?php print _("Select group."); ?>" >
                            <?php                               
                                foreach( $tGroups as $entry ) {                                   
                                    print "\t\t\t<option value='".$entry['id']."'>".$entry['groupname']."</option>\n";
                                }
                            ?>
                        </select>
                        <?php print outputResponseSpan($tGroupError); ?>
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
        
        $('#dialoggroup').modal('show');
        
        $("button#submit").click(function() {
            $.ajax({
                type: "POST",
                url: "rep_show_group.php",
                data: $('form.showgroupselect').serialize(),
                success: function(){
                    $("#dialoggroup").modal('hide');
                }
            });
        });
        
        $("button#cancel").click(function(){
            window.location.assign("/main.php");
        }); 
        
    });
</script>
