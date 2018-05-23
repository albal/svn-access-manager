<div> 
    <h3 class="page-header"><?php print _("Group to show"); ?></h3> 
</div>
<div>
    <form class="form-horizontal" name="showgroupselect" method="post">
        <div class="form-group <?php print outputResponseClasses($tGroupError); ?>">
            <label class="col-sm-1 control-label" for="group"><?php print _("Group"); ?>:</label>
            <div class="col-sm-3">
                <select class="selectpicker" name="fGroup" id="group">
                    <?php
                        print "\t\t\t<option value='default'>"._("--- Select group ---")."</option>\n";
                        foreach( $tGroups as $entry ) {
                             
                            print "\t\t\t<option value='".$entry['id']."'>".$entry['groupname']."</option>\n";
                        }
                    ?>
                </select>
                <?php print outputResponseSpan($tGroupError); ?>
            </div>
        </div>
        <div class="input-group">
            <p>&nbsp;</p>
        </div>    
        <div class="input-group">
            <button class="btn btn-sm btn-primary btn-block" data-toggle="tooltip" type="submit" name="fSubmit_show" title="<?php print _("Create report"); ?>"><span class="glyphicon glyphicon-list"></span> <?php print _("Create report"); ?></button>
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
