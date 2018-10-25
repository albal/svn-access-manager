<div>     
    <h3 class="page-header"><?php print sprintf( _("Show group %s"), $tGroupname ); ?></h3> 
</div>
<?php 
    outputMessage($tMessage, $tMessageType);
?>
<div>
    <form class="form-horizontal" name="general" method="post">
        <div class="form-group">
            <label class="col-sm-3 control-label greycell" for="name"><?php print _("Group Name"); ?>:</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php print $tGroupname;?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label greycell" for="description"><?php print _("Username")?>:</label>
            <div class="col-sm-9">
                <p class="form-control-static"><?php print $tDescription;?></p>
            </div>
        </div>
        <div class="input-group">
            <p>&nbsp;</p>
        </div>
        
        <div class="input-group">
            <h3><?php print _("Users in group");?></h3>
        </div>
        <table id="usertable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th><?php print _("Username");?></th>
                    <th><?php print _("Givenname");?></th>
                    <th><?php print _("Name");?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach( $tUsers as $entry ) {
                        print "\t\t\t<tr>\n";
                        print "\t\t\t\t<td>".$entry['userid']."</td>\n";
                        print "\t\t\t\t<td>".$entry['givenname']."</td>\n";
                        print "\t\t\t\t<td>".$entry['name']."</td>\n";
                        print "\t\t\t</tr>\n";
                    }
                ?>
            </tbody>
        </table>
        <div class="input-group">
            <p>&nbsp;</p>
        </div>
        
        <div class="input-group">
            <h3><?php print _("Group administrators");?></h3>
        </div>
        <table id="groupadmintable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th><?php print _("Username");?></th>
                    <th><?php print _("Givenname");?></th>
                    <th><?php print _("Name");?></th>
                    <th><?php print _("Access right");?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach( $tAdmins as $entry ) {
                        print "\t\t\t<tr>\n";
                        print "\t\t\t\t<td>".$entry['userid']."</td>\n";
                        print "\t\t\t\t<td>".$entry['givenname']."</td>\n";
                        print "\t\t\t\t<td>".$entry['name']."</td>\n";
                        print "\t\t\t\t<td>".$entry['allowed']."</td>\n";
                        print "\t\t\t</tr>\n";
                    }
                ?>
            </tbody>
        </table>
        <div class="input-group">
            <p>&nbsp;</p>
        </div>
        
        <div class="input-group">
            <h3><?php print _("Access rights"); ?></h3>
        </div>
        <table id="accessrighttable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th><?php print _("SVN Module");?></th>
                    <th><?php print _("Reporitory");?></th>
                    <th><?php print _("Path");?></th>
                    <th><?php print _("Module path");?></th>
                    <th><?php print _("Access right");?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach( $tAccessRights as $entry ) {
                        print "\t\t\t<tr>\n";
                        print "\t\t\t\t<td>".$entry['svnmodule']."</td>\n";
                        print "\t\t\t\t<td>".$entry['reponame']."</td>\n";
                        print "\t\t\t\t<td>".$entry['path']."</td>\n";
                        print "\t\t\t\t<td>".$entry['modulepath']."</td>\n";
                        print "\t\t\t\t<td>".$entry['access_right']."</td>\n";
                        print "\t\t\t</tr>\n";
                    }
                ?>
            </tbody>
        </table>
    </form>
</div>
        
<script>
$(document).ready(function() {
    $('#usertable').DataTable({
        stateSave: true,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "pageLength": <?php print getCurrentPageSize(); ?>,
        <?php
            if( check_language() == 'de' ) {
                print '"language": {"url": "/lib/DataTables-1.10.16/i18n/German.json"}';
            }
        ?>
    });
    
    $('#groupadmintable').DataTable({
        stateSave: true,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "pageLength": <?php print getCurrentPageSize(); ?>,
        <?php
            if( check_language() == 'de' ) {
                print '"language": {"url": "/lib/DataTables-1.10.16/i18n/German.json"}';
            }
        ?>
    });
    
    $('#accessrighttable').DataTable({
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

