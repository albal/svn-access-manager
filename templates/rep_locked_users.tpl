<div> 
    <h3 class="page-header"><?php print _("List of locked users"); ?></h3> 
</div>
<table id="lockreport" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>
                <?php print _("Last Modified"); ?>
            </th>
            <th>
                <?php print _("Modified by user"); ?>
            </th>
            <th>
                <?php print _("Username"); ?>
            </th>
            <th>
                <?php print _("Name"); ?>
            </th>
            <th>
                <?php print _("Given name"); ?>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php
            if( is_array( $tLockedUsers ) ) {
            
                foreach( $tLockedUsers as $entry ) {
                
                    list($date, $time)      = splitDateTime( $entry['modified'] );
                    
                    print "\t\t\t\t\t<tr>\n";
                    print "\t\t\t\t\t\t<td>".$date." ".$time."</td>\n";
                    print "\t\t\t\t\t\t<td>".$entry['modified_user']."</td>\n";
                    print "\t\t\t\t\t\t<td>".$entry['userid']."</td>\n";
                    print "\t\t\t\t\t\t<td>".$entry['name']."</td>\n";
                    print "\t\t\t\t\t\t<td>".$entry['givenname']."</td>\n";
                    print "\t\t\t\t\t</tr>\n";
                }
                
            }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th>
                <?php print _("Last Modified"); ?>
            </th>
            <th>
                <?php print _("Modified by user"); ?>
            </th>
            <th>
                <?php print _("Username"); ?>
            </th>
            <th>
                <?php print _("Name"); ?>
            </th>
            <th>
                <?php print _("Given name"); ?>
            </th>
        </tr>
    </tfoot>
</table>
<?php 
    outputMessage($tMessage, $tMessageType);
?>
<script>
$(document).ready(function() {
    $('#lockreport').DataTable({
        "order": [[ 2, "asc" ]],
        stateSave: true,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    });
} );
</script>
