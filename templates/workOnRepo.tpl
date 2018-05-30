<div>      
    <h3 class="page-header"><?php print _("Repository administration"); ?></h3> 
</div>
<?php 
    outputMessage($tMessage, $tMessageType);
?>
<div>
    <form class="form-horizontal" name="workOnRepo" method="post">
        <div class="form-group <?php print outputResponseClasses($tReponameError); ?>">
            <label class="col-sm-3 control-label" for="name"><?php print _("Repository name"); ?>:</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="name" name="fReponame" value="<?php print $tReponame; ?>" data-toggle="tooltip" totle="<?php print _("Enter repository name"); ?>" />
                <?php print outputResponseSpan($tReponameError); ?>
            </div>
        </div>
        <div class="form-group <?php print outputResponseClasses($tRepopathError); ?>">
            <label class="col-sm-3 control-label" for="path"><?php print _("Repository path"); ?>:</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="path" name="fRepopath" value="<?php print $tRepopath; ?>" data-toggle="tooltip" title="<?php print _("Enter repository path"); ?>" />
                <?php print outputResponseSpan($tRepopathError); ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="user"><?php print _("Repository user"); ?>:</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="user" name="fRepouser" value="<?php print $tRepouser; ?>" data-toggle="tooltip" title="<?php print _("Enter the user to access a remote repository."); ?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="password"><?php print _("Repository password"); ?>:</label>
            <div class="col-sm-9">
                <input type="password" class="form-control" id="password" name="fRepopassword" value="<?php print $tRepopassword; ?>" data-toggle="tooltip" title="<?php print _("Enter the password to access a remote repository."); ?>" />
            </div>
        </div>
        <div class="form-group">
            <?php
                if( $_SESSION[SVNSESSID]['task'] == "change" ) {
                    $checked            = "disabled";
                } else {
                    if( $tCreateRepo == "1" ) {
                        $checked        = "checked";
                    } else {
                        $checked        = "";
                    }
                }
            ?>
            <label><?php print _("Create repository in filesystem"); ?> <input type="checkbox" name="fCreateRepo" value="1" <?php print $checked; ?> data-toggle="tooltip" title="<?php print _("Select if repository should be created in filesystem too. Appropriate rights for the webserver are mandatory."); ?>" ></label>
        </div>
        <?php
            if(isset( $CONF[SEPARATEFILESPERREPO]) && ($CONF[SEPARATEFILESPERREPO] != "YES")) {
                $tHidden = 'hidden';
            } else {
                $tHidden = '';
            }
        ?>
        <div class="form-group <?php print $tHidden; ?>">
            <p><<?php print _("If you need separate configuration files for each repository specify the locations of the files here. If you do not give a path and filename config parameters will be used and the filename will be replaced accordingly."); ?>/p>
        </div>
        <div class="form-group <?php print $tHidden; ?>">
            <label class="col-sm-3 control-label" for="authuser"><?php print _("Auth user file"); ?>:</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="authuser" name="fAuthUserFile" value="<?php print $tAuthUserFile; ?>" />
            </div>
        </div>
        <div class="form-group <?php print $tHidden; ?>">
            <label class="col-sm-3 control-label" for="authfile"><?php print _("SVN access file"); ?>:</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="authfile" name="fSvnAccessFile" value="<?php print $tSvnAccessFile; ?>" />
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
