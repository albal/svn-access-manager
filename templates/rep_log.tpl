<div>       
    <h3 class="page-header"><?php print _("Log report"); ?></h3> 
</div>

<table id="logreport" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th >
                <?php print _("Date"); ?>
            </th>
            <th>
                <?php print _("Username"); ?>
            </th>
            <th>
                <?php print _("IP Address"); ?>
            </th>
            <th>
                <?php print _("Logmessage"); ?>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach( $tLogmessages as $entry ) {
            
                list($date, $time)      = splitDateTime( $entry['logtimestamp'] );
                
                print "    <tr>\n";
                print "        <td>".$date." ".$time."</td>\n";
                print "        <td>".$entry['username']."</td>\n";
                print "        <td>".$entry['ipaddress']."</td>\n";
                print "        <td>".$entry['logmessage']."</td>\n";
                print "    </tr>\n";
            }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th >
                <?php print _("Date"); ?>
            </th>
            <th>
                <?php print _("Username"); ?>
            </th>
            <th>
                <?php print _("IP Address"); ?>
            </th>
            <th>
                <?php print _("Logmessage"); ?>
            </th>
        </tr>
    </tfoot>
</table>

<?php 
    outputMessage($tMessage, $tMessageType);
?>
        
<script>
$(document).ready(function() {
    $('#logreport').DataTable({
        "order": [[ 0, "desc" ]],
        stateSave: true,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    });
} );
</script>
