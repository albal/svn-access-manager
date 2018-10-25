<div>    
    <h3 class="page-header"><?php print _("Messages administration"); ?></h3> 
</div>
<?php 
    outputMessage($tMessage, $tMessageType);
?>
<div>
    <form class="form-horizontal" name="general" method="post">
        <table id="messagestable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th><?php print _("Valid from"); ?></th>
                    <th><?php print _("Valid until"); ?></th>
                    <th><?php print _("Message"); ?>
                    <th><?php print _("Action"); ?></th>
            </thead>
            <tbody>
                <?php
                    outputMessages($tUserMessages, $rightAllowed);
                ?>
            </tbody>
        </table>
        <div class="input-group">
            <p>&nbsp;</p>
        </div>    
        <div class="input-group">
            <button class="btn btn-sm btn-primary" data-toggle="tooltip" type="submit" name="fSubmit_new" title="<?php print _("New message"); ?>"><span class="glyphicon glyphicon-plus-sign"></span> <?php print _("New message"); ?></button>
            <button class="btn btn-sm" data-toggle="tooltip" type="submit" name="fSubmit_back" title="<?php print _("Back"); ?>"><span class="glyphicon glyphicon-menu-left"></span> <?php print _("Back"); ?></button>
        </div>
        <div class="input-group">
            <p>&nbsp;</p>
        </div>
    </form>
</div>
<script>
$(document).ready(function() {
    $('#messagestable').DataTable({
        stateSave: true, 
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "pageLength": <?php print getCurrentPageSize(); ?>,
        <?php
            if( check_language() == 'de' ) {
                print '"language": {"url": "/lib/DataTables-1.10.16/i18n/German.json"}';
            }
        ?>
    });
} );
</script>
