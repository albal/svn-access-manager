<div>       
    <h3 class="page-header"><?php print _("List of granted user rights"); ?></h3> 
</div>

<table id="grantedrightsreport" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>
                &nbsp;
            </th>
            <th>
                <?php print _("Userid"); ?>
            </th>
            <th>
                <?php print _("Username"); ?>
            </th>
            <th class=>
                <?php print _("Granted rights"); ?>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php
                                    
            foreach( $tGrantedRights as $entry ) {
            
                if( $entry['locked'] == 1 ) {
                    $locked             = "<img src='./images/locked_16_16.png' width='16' height='16' border='0' alt='"._("User locked")."' title='"._("User locked")."' />";
                } else {
                    $locked             = "&nbsp;";
                }
                
                print "\t\t\t\t\t<tr>\n";
                print "\t\t\t\t\t\t<td>".$locked."</td>\n";
                print "\t\t\t\t\t\t<td>".$entry['userid']."</td>\n";
                print "\t\t\t\t\t\t<td nowrap>".$entry['name']."</td>\n";
                print "\t\t\t\t\t\t<td>".$entry['rights']."</td>\n";
                print "\t\t\t\t\t</tr>\n";

            }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th>
                &nbsp;
            </th>
            <th>
                <?php print _("Userid"); ?>
            </th>
            <th>
                <?php print _("Username"); ?>
            </th>
            <th class=>
                <?php print _("Granted rights"); ?>
            </th>
        </tr>
    </tfoot>
</table>

<?php 
    outputMessage($tMessage, $tMessageType);
?>
        
<script>
$(document).ready(function() {
    $('#grantedrightsreport').DataTable({
        stateSave: true,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    });
} );
</script>
