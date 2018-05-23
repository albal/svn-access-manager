<div>    
    <h3 class="page-header"><?php print _("Repository administration / delete repository"); ?></h3> 
</div>
<div class="jumbotron">
  <h3>Notice</h3>
  <p class="lead"><?php print _("Please note that a repository can only be deleted when it is no longer used in any project. Removing a repository does not affect the subversion repository itself. It is only removed from the database!"); ?></p>
</div>
<div>
    <form class="form-horizontal" name="deleterepo" method="post">
        <div class="form-group">
            <label class="col-sm-3 control-label" for="name"><?php print _("Repository name"); ?>:</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php print $tReponame;?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="name"><?php print _("Repository path"); ?>:</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php print $tRepopath;?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="name"><?php print _("Repository user"); ?>:</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php print $tRepouser;?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="name"><?php print _("Repository password"); ?>:</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php print $tRepopassword;?></p>
            </div>
        </div>
        <div class="input-group">
            <p>&nbsp;</p>
        </div>    
        <div class="input-group">
            <button class="btn btn-sm btn-primary" data-toggle="tooltip" type="submit" name="fSubmit_ok" title="<?php print _("Delete"); ?>"<span class="glyphicon glyphicon-erase"></span> <?php print $tDisabled; ?> ><?php print _("Delete"); ?></button>
            <button class="btn btn-sm" data-toggle="tooltip" type="submit" name="fSubmit_back" title="<?php print _("Back"); ?>"><span class="glyphicon glyphicon-arrow-left"></span><?php print _("Back"); ?></button>
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
$(document).ready(function() {
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip(); 
    });
} );
</script>
