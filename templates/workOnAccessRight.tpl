<?php
    
    
        echo "\t\t\t<script language=\"JavaScript1.3\">\n"; 
        echo "\t\t\t\tfunction onChangeDir() {\n";
        echo "\t\t\t\t\tdocument.workOnAccessRight.submit();\n";
        echo "\t\t\t\t}\n";
        echo "\t\t\t</script>\n";

        $tChangeFunction = 'onchange="onChangeDir();"';
    
?>
            
<div>    
    <h3 class="page-header"><?php print _("Access right administration / Step 2: select directory"); ?></h3> 
</div>
<?php 
    outputMessage($tMessage, $tMessageType);
?>
<div>
    <form class="form-horizontal" name="workOnAccessRight" method="post">
        <div class="form-group">
            <label class="col-sm-3 control-label" for="project"><?php print _("Project"); ?>:</label>
            <div class="col-sm-3">
                <p class="form-control-static"><?php print $tProjectName;?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="modilepath"><?php print _("Subversion module path"); ?>:</label>
            <div class="col-sm-3">
                <p class="form-control-static"><?php print $tModulePath;?></p>
            </div>
        </div>
        <div class="form-group ">
            <label class="col-sm-3 control-label" for="directory"><?php print  _("Selected directory"); ?>:</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="directory" name="fPathSelected" value="<?php print $tPathSelected; ?>" data-toggle="tooltip" title="<?php print _("You can edit the directory path above to your needs. But keep in mind that the path must match a valid path in the repository. Regular expressions are not allowed.");?>" />
            </div>
        </div>
        <div class="form-group ">
            <label class="col-sm-3 control-label" for="dir"><?php print  _("Select directory"); ?>:</label>
            <div class="col-sm-9">
                <select class="selectpicker" id="dir" name="fPath" size="15" <?php print $tChangeFunction; ?> data-toggle="tooltip" title="<?php print _("Select the directory you want to descend into and click 'Change to directory' afterwards if no JavaScript is enabled. '..' ascends one level if possible." ); ?>">
                    <?php
                        if( $_SESSION[SVNSESSID]['pathcnt'] > 0 ) {
                            print "\t\t\t\t\t\t\t\t<option value=\"[back]\">[..]</option>\n";
                        }
                        
                        if($fileSelect == 0) {
                            
                            $files = array();
                            foreach( $tRepodirs as $dir ) {
                                
                                if( preg_match( '/\/$/', $dir ) ) {
                                    print '\t\t\t\t\t\t\t\t<option value="'.$dir.'">'.$dir.'</option>\n';
                                } else {
                                    $files[] = $dir;
                                }
                                
                            }
                            
                            foreach( $files as $file ) {
                                print '\t\t\t\t\t\t\t\t<option value="'.$file.'">'.$file.'</option>\n';
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
            <button class="btn btn-sm" data-toggle="tooltip" type="submit" name="fSubmit_back" title="<?php print _("Back"); ?>"><span class="glyphicon glyphicon-menu-left"></span> <?php print _("Back"); ?></button>
            <button class="btn btn-sm" data-toggle="tooltip" type="submit" name="fSubmit_chdir" title="<?php print _("Change to directory"); ?>"><span class="glyphicon glyphicon-open"></span> <?php print _("Change to directory"); ?></button>
            <button class="btn btn-sm" data-toggle="tooltip" type="submit" name="fSubmit_set" title="<?php print _("Set access rights"); ?>"><span class="glyphicon glyphicon-menu-right"></span> <?php print _("Set access rights"); ?></button>
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
