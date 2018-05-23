<div>      
    <h3 class="page-header"><?php print _("Database error") ?></h3> 
</div>
<div class="alert alert-danger">
    <form class="form-horizontal" name="showuser" method="post">
        <div class_"input-group">
            <?php print _("A database error occured:"); ?>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label alert alert-danger" for="name"><?php print _("Errormessage"); ?>:</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php print $tDbError; ?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="name"><?php print _("Query"); ?>:</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php print $tQuery; ?></p>
            </div>
        </div>
    </form>
</div>

<?php 
    outputMessage($tMessage, $tMessageType);
?>
