<div>    
    <h3 class="page-header"><?php print _("Project administration"); ?></h3> 
</div>
<div>
    <form class="form-horizontal" name="general" method="post">
        <table id="projecttable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>
                        <?php print _("Subversion project"); ?>
                    </th>
                    <th>
                        <?php print _("Subversion module path"); ?>
                    </th>
                    <th>
                        <?php print _("Repository"); ?>
                    </th>
                    <th>
                        <?php print _("Action"); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                    outputProjects($tProjects, $rightAllowed);
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>
                        <?php print _("Subversion project"); ?>
                    </th>
                    <th>
                        <?php print _("Subversion module path"); ?>
                    </th>
                    <th>
                        <?php print _("Repository"); ?>
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
            <button class="btn btn-sm btn-primary" data-toggle="tooltip" type="submit" name="fSubmit_new" title="<?php print _("New project"); ?>"><span class="glyphicon glyphicon-plus-sign"></span> <?php print _("New project"); ?></button>
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
    $('#projecttable').DataTable({
        stateSave: true, "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    });
} );
</script>
