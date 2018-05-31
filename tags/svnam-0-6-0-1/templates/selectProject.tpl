<div>     
    <h3 class="page-header"><?php print _("Access right administration / Step 1: select project"); ?></h3> 
</div>
<?php 
    outputMessage($tMessage, $tMessageType);
?>
<div>
    <form class="form-horizontal" name="selectproject" method="post">
        <div class="form-group">
            <label class="col-sm-1 control-label" for="project"><?php print _("Project"); ?>:</label>
            <div class="col-sm-3">
                <select name="fProject" id="project" data-toggle="tooltip" title="<?php print _("Select project to work with.");?>">
                    <?php
                        foreach( $tProjects as $projectId => $projectName ) {
                                    
                            if( $tProject == $projectId ) {
                            
                                print "\t\t\t\t\t\t\t\t<option value='".$projectId."' selected>".$projectName."</option>\n";
                                
                            } else {
                            
                                print "\t\t\t\t\t\t\t\t<option value='".$projectId."'>".$projectName."</option>\n";
                                
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
