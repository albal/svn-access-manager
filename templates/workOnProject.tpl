<div>      
    <h3 class="page-header"><?php print _("Project administration"); ?></h3> 
</div>
<div>
    <form class="form-horizontal" name="workOnGroup" method="post">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group <?php print outputResponseClasses($tProjectError); ?>">
                    <label class="col-sm-2 control-label" for="project"><?php print  _("Subversion project"); ?>:</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="project" name="fProject" value="<?php print $tProject; ?>" <?php print $tReadonly; ?> />
                        <?php print outputResponseSpan($tProjectError); ?>
                    </div>
                </div>
                <div class="form-group <?php print outputResponseClasses($tDescriptionError); ?>">
                    <label class="col-sm-2 control-label" for="description"><?php print  _("Project description"); ?>:</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="description" name="fDescription" value="<?php print $tDescription; ?>" />
                        <?php print outputResponseSpan($tDescriptionError); ?>
                    </div>
                </div>
                <div class="form-group <?php print outputResponseClasses($tModulePathError); ?>">
                    <label class="col-sm-2 control-label" for="path"><?php print  _("Subversion module path"); ?>:</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="path" name="fModulepath" value="<?php print $tModulepath; ?>" />
                        <?php print outputResponseSpan($tModulePathError); ?>
                    </div>
                </div>
                <div class="form-group ">
                    <label class="col-sm-2 control-label" for="repo"><?php print _("Repository"); ?>:</label>
                    <div class="col-sm-4">
                        <select id="repo" class="selectpicker" name="fRepo">
                            <?php
                                foreach( $tRepos as $repoId => $repoName ) {
                                                
                                    if( $tRepo == $repoId ) {
                                    
                                        print "\t\t\t\t\t\t\t\t<option value='".$repoId."' selected>".$repoName."</option>\n";
                                        
                                    } else {
                                    
                                        print "\t\t\t\t\t\t\t\t<option value='".$repoId."'>".$repoName."</option>\n";
                                        
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
                    <button class="btn btn-sm btn-primary" data-toggle="tooltip" type="submit" name="fSubmit_ok" title="<?php print _("Save"); ?>"><span class="glyphicon glyphicon-save"></span> <?php print _("Save"); ?></button>
                    <button class="btn btn-sm" data-toggle="tooltip" type="submit" name="fSubmit_back" title="<?php print _("Back"); ?>"><span class="glyphicon glyphicon-arrow-left"></span> <?php print _("Back"); ?></button>
                </div> 
            </div>
            <div class="col-sm-6">
                <div class="form-group ">
                    <label class="col-sm-2 control-label" for="members"><?php print _("Select project responsible users"); ?>:</label>
                    <div class="col-sm-4">
                        <select id="members" class="selectpicker" multiple name="members[]" size="13">
                            <?php
                                foreach($tMembers as $uid => $member) {
                                    $label = $member." [".$uid."]";
                                    print "\t\t\t\t\t\t\t<option value=\"$uid\" label=\"$label\">$label</option>\n";
                                } 
                            ?>
                        </select>
                    </div>
                </div>
                <div class="input-group">
                    <p>&nbsp;</p>
            </div>    
            <div class="input-group">
                <button class="btn btn-sm" data-toggle="tooltip" type="submit" name="fSubmit_add" title="<?php print _("Add member"); ?>"><?php print _("Add member"); ?></button>
                <button class="btn btn-sm" data-toggle="tooltip" type="submit" name="fSubmit_remove" title="<?php print _("Remove member"); ?>"><?php print _("Remove member"); ?></button>
            </div>
            </div>
        </div>
        <div class="input-group">
            <p>&nbsp;</p>
        </div>
        
         <?php 
            outputMessage($tMessage, $tMessageType);
        ?>
    </form>
</div>
<script type="text/javascript">
    
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip(); 
    });
</script>
