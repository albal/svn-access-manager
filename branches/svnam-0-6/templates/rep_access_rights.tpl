<div> 
    <h3 class="page-header"><?php print _("Log report"); ?></h3> 
</div>

<table id="accessrightsreport" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
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
        </tr>
    </thead>
    <tbody>
        <?php
            $i                                      = 0;
            $_SESSION[SVNSESSID]['max_mark']        = 0;
            $_SESSION[SVNSESSID]['mark']            = array();
            
            foreach( $tAccessRights as $entry ) {
            
                $id                     = $entry['id'];
     
                $validfrom              = splitValidDate( $entry['valid_from'] );
                $validuntil             = splitValiddate( $entry['valid_until'] );
                $field                  = "fDelete".$i;
                
                print "\t\t\t\t\t<tr>\n";
                print "\t\t\t\t\t\t<td>".$entry['svnmodule']."</td>\n";
                print "\t\t\t\t\t\t<td>".$entry['access_right']."</td>\n";
                print "\t\t\t\t\t\t<td>".$entry['username']."</td>\n";
                print "\t\t\t\t\t\t<td>".$entry['groupname']."</td>\n";
                print "\t\t\t\t\t\t<td>".$validfrom."</td>\n";
                print "\t\t\t\t\t\t<td>".$validuntil."</td>\n";
                print "\t\t\t\t\t\t<td>".$entry['reponame'].":".$entry['path']."</td>\n";
                print "\t\t\t\t\t</tr>\n";
                
                $_SESSION[SVNSESSID]['mark'][$i]        = $entry['id'];
                
                $i++;
            }
            
            $_SESSION[SVNSESSID]['max_mark'] = $i - 1;
        ?>
    </tbody>
    <tfoot>
        <tr>
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
        </tr>
    </tfoot>
</table>
<?php 
    outputMessage($tMessage, $tMessageType);
?>
        
<script>
$(document).ready(function() {
    $('#accessrightsreport').DataTable({
        "order": [[ 0, "desc" ]],
        stateSave: true,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    });
} );

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
</script>
