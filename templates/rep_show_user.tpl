<div>     
    <h3 class="page-header"><?php print _("User to show"); ?></h3> 
</div>
<div>
    <form class="form-horizontal" name="showuserselect" method="post">
        <div class="form-group <?php print outputResponseClasses($tUserError); ?>">
            <label class="col-sm-1 control-label" for="user"><?php print _("User"); ?>:</label>
            <div class="col-sm-3">
                <select class="selectpicker" name="fUser" id="user">
                    <?php
                        print "\t\t\t<option value='default'>"._("--- Select user ---")."</option>\n";
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
