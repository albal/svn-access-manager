<div>      
    <h3 class="page-header"><?php print _("Create Access Files"); ?></h3> 
</div>
<div>
    <form class="form-horizontal" name="createaccessfiles" method="post">
        <div class="form-group <?php print outputResponseClasses($tAuthUserError); ?>">
            <label class="col-sm-3 control-label" for="name"><?php print _("Result of auth user file"); ?>:</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php print $tRetAuthUser[ERRORMSG];?></p>
            </div>
        </div>
        <div class="form-group <?php print outputResponseClasses($tRetAccessError); ?>">
            <label class="col-sm-3 control-label" for="name"><?php print  _("Result of access file"); ?>:</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php print $tRetAccess[ERRORMSG];?></p>
            </div>
        </div>
        <div class="form-group <?php print outputResponseClasses($tRetViewvcError); ?> <?php print $tHidden; ?>">
            <label class="col-sm-3 control-label" for="name"><?php print _("Result of viewvc config"); ?>:</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php print $tRetViewvc[ERRORMSG];?></p>
            </div>
        </div>
        <div class="form-group ?php print outputResponseClasses($tRetReloadError); ?> <?php print $tHidden; ?>">
            <label class="col-sm-3 control-label" for="name"><?php print _("Result of webserver reload"); ?>:</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php print $tRetReload[ERRORMSG];?></p>
            </div>
        </div>
    </form>
</div>
