<div>       
    <h3 class="page-header"><?php print _("Report granted user rights"); ?></h3> 
</div>
<?php 
    outputMessage($tMessage, $tMessageType);
?>
<table id="grantedrightsreport" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th rowspan="2">
                &nbsp;
            </th>
            <th rowspan="2">
                <?php print _("Userid"); ?>
            </th>
            <th rowspan="2">
                <?php print _("Username"); ?>
            </th>
            <th colspan="<?php print $tRightsCount; ?>">
                <?php print _("Granted rights"); ?>
            </th>
        </tr>
        <tr>
            <?php
                foreach($tRights as $entry) {
                
                    print "\t\t\t\t<th>".$entry['right_name']."</th>\n";
                }
            ?>
        </tr>
    </thead>
    <tbody>
        <?php
            $oldUserid = '';
            $finish = 0;
            
            foreach( $tGrantedRights as $entry ) {
            
                $right = translateAccessRightReport($entry['allowed']);
                $locked = translateLockReport($entry['locked']);
                $name = ($entry['givenname'] != '') ? $entry['givenname'].' '.$entry['name'] : $entry['name'];
                $right = translateAccessRightReport($entry['allowed']);
                
                if($oldUserid == '') {
                
                    $oldUserid = $entry['userid'];                   
                   
                    print "\t\t\t<tr>\n";
                    print "\t\t\t\t<td>".$locked."</td>\n";
                    print "\t\t\t\t<td>".$entry['userid']."</td>\n";
                    print "\t\t\t\t<td>".$name."</td>\n";
                    
                    print "\t\t\t\t<td>".$right."</td>\n";
                    
                    $finish = 1;
                    
                } elseif($entry['userid'] != $oldUserid) {
                
                    print "\t\t\t</tr>\n";
                    
                    print "\t\t\t<tr>\n";
                    print "\t\t\t\t<td>".$locked."</td>\n";
                    print "\t\t\t\t<td>".$entry['userid']."</td>\n";
                    print "\t\t\t\t<td>".$name."</td>\n";
                    
                    print "\t\t\t\t<td>".$right."</td>\n";
                    
                    $finish = 1;
                    $oldUserid = $entry['userid'];
                
                } else {
                
                    print "\t\t\t\t<td>".$right."</td>\n";
                
                }
            }
            
            if( $finish == 1) {
                
                print "\t\t\t</tr>\n";
            }
            
        ?>
    </tbody>
    <tfoot>
    </tfoot>
</table>
        
<script>
$(document).ready(function() {
    $('#grantedrightsreport').DataTable({
        stateSave: true,
        "order": [[ 1, "asc" ]],
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
