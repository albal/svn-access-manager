<div>    
    <h3 class="page-header"><?php print _("Repository administration"); ?></h3> 
</div>
<div>
    <form class="form-horizontal" name="general" method="post">
        <table id="accessrighttable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>
                        <?php print _("M"); ?>
                    </th>
                    <th>
                        <?php print _("Project"); ?>
                    </th>
                    <th>
                        <?php print _("Rights"); ?>
                    </th>
                    <th>
                        <?php print _("User"); ?>
                    </th>
                    <th>
                        <?php print _("Group"); ?>
                    </th>
                    <th>
                        <?php print _("Valid from"); ?>
                    </th>
                    <th>
                        <?php print _("Valid until"); ?>
                    </th>
                    <th>
                        <?php print _("Repository:Directory"); ?>
                    </th>
                    <th>
                        <?php print _("Action"); ?>
                    </th>
                </tr>   
            </thead>
            <tbody>
                <?php
                    outputAccessRights($tAccessRights, $rightAllowed);
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>
                        <?php print _("M"); ?>
                    </th>
                    <th>
                        <?php print _("Project"); ?>
                    </th>
                    <th>
                        <?php print _("Rights"); ?>
                    </th>
                    <th>
                        <?php print _("User"); ?>
                    </th>
                    <th>
                        <?php print _("Group"); ?>
                    </th>
                    <th>
                        <?php print _("Valid from"); ?>
                    </th>
                    <th>
                        <?php print _("Valid until"); ?>
                    </th>
                    <th>
                        <?php print _("Repository:Directory"); ?>
                    </th>
                    <th>
                        <?php print _("Action"); ?>
                    </th>
                </tr>   
            </tfoot>
        </table>
        <div class="input-group">
            <p>&nbsp;</p>
        </div>    
        <div class="input-group">
            <button class="btn btn-sm btn-primary" data-toggle="tooltip" type="submit" name="fSubmit_new" title="<?php print _("New access right"); ?>"><span class="glyphicon glyphicon-plus-sign"></span> <?php print _("New access right"); ?></button>
            <button class="btn btn-sm" data-toggle="tooltip" type="submit" name="fSubmit_delete" title="<?php print _("Delete selected"); ?>"><span class="glyphicon glyphicon-erase"></span> <?php print _("Delete selected"); ?></button>
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
$(document).ready(function() {
    $('#accessrighttable').DataTable({
        stateSave: true,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    });
} );
</script>
