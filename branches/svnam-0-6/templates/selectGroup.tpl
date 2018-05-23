<div>     
    <h3 class="page-header"><?php print _("Group acccess right administration / Step 1: select group"); ?></h3> 
</div>
<div>
    <form class="form-horizontal" name="selectgroup" method="post">
        <div class="form-group">
            <label class="col-sm-1 control-label" for="group"><?php print _("Group"); ?>:</label>
            <div class="col-sm-3">
                <select name="fGroup" id="group" title="<?php print _("Select group to work with.");?>">
                    <?php
                        foreach( $tGroups as $groupId => $groupName ) {
                                    
                            if( $tGroup == $groupId ) {
                            
                                print "\t\t\t\t\t\t\t\t<option value='".$groupId."' selected>".$groupName."</option>\n";
                                
                            } else {
                            
                                print "\t\t\t\t\t\t\t\t<option value='".$groupId."'>".$groupName."</option>\n";
                                
                            }
                        }
                    ?>
                </select>
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
