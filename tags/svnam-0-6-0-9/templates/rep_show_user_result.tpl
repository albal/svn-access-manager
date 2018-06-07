<div>    
    <h3 class="page-header"><?php print sprintf( _("Show user %s"), $tUser ); ?></h3> 
</div>
<?php 
    outputMessage($tMessage, $tMessageType);
?>
<div>
    <form class="form-horizontal" name="showuser" method="post">
        <div class="form-group">
            <label class="col-sm-3 control-label greycell" for="name"><?php print _("Username"); ?>:</label>
            <div class="col-sm-3">
                <p class="form-control-static"><?php print $tUsername;?></p>
            </div>
            <label class="col-sm-3 control-label greycell" for="name"><?php print _("Administrator"); ?>:</label>
            <div class="col-sm-3">
                <p class="form-control-static"><?php print $tAdministrator;?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label greycell" for="description"><?php print _("Name"); ?>:</label>
            <div class="col-sm-3">
                <p class="form-control-static"><?php print $tName;?></p>
            </div>
            <label class="col-sm-3 control-label greycell" for="description"><?php print _("Givenname"); ?>:</label>
            <div class="col-sm-3">
                <p class="form-control-static"><?php print $tGivenname;?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label greycell" for="name"><?php print _("Email address"); ?>:</label>
            <div class="col-sm-3">
                <p class="form-control-static"><?php print $tEmailAddress;?></p>
            </div>
            <label class="col-sm-3 control-label greycell" for="name"><?php print _("Locked"); ?>:</label>
            <div class="col-sm-3">
                <p class="form-control-static"><?php print $tLocked;?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label greycell" for="description"><?php print _("Password expires"); ?>:</label>
            <div class="col-sm-3">
                <p class="form-control-static"><?php print $tPasswordExpires;?></p>
            </div>
            <label class="col-sm-3 control-label greycell" for="description"><?php print _("Repository access right"); ?>:</label>
            <div class="col-sm-3">
                <p class="form-control-static"><?php print $tAccessRight;?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label greycell" for="description"><?php print _("Password last modified"); ?>:</label>
            <div class="col-sm-3">
                <p class="form-control-static"><?php print $tPasswordModified;?></p>
            </div>
            <label class="col-sm-3 control-label" for="description">&nbsp;</label>
            <div class="col-sm-3">
                <p class="form-control-static">&nbsp;</p>
            </div>
        </div>
        <div class="input-group">
            <p>&nbsp;</p>
        </div>
        
        <div class="input-group">
            <h3><?php print _("Group membership");?></h3>
        </div>
        <table id="grouptable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th><?php print _("Group name");?></th>
                    <th><?php print _("Description");?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach( $tGroups as $entry ) {
                        print "\t\t\t<tr>\n";
                        print "\t\t\t\t<td>".$entry['groupname']."</td>\n";
                        print "\t\t\t\t<td>".$entry['description']."</td>\n";
                        print "\t\t\t</tr>\n";
                    }
                ?>
            </tbody>
        </table>
        <div class="input-group">
            <p>&nbsp;</p>
        </div>
        
        <div class="input-group">
            <h3><?php print  _("Project responsible");?></h3>
        </div>
        <table id="projecttable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th><?php print _("SVN Module");?></th>
                    <th><?php print _("Repository name");?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach( $tProjects as $entry ) {
                        print "\t\t\t<tr>\n";
                        print "\t\t\t\t<td>".$entry['svnmodule']."</td>\n";
                        print "\t\t\t\t<td>".$entry['reponame']."</td>\n";
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
                    <th><?php print _("Access by");?></th>
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
                        print "\t\t\t\t<td>".$entry['access_by']."</td>\n";
                        print "\t\t\t</tr>\n";
                    }
                ?>
            </tbody>
        </table>
    </form>
</div>
        
<script>
$(document).ready(function() {
    $('#grouptable').DataTable({
        stateSave: true,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "pageLength": <?php print getCurrentPageSize(); ?>,
        <?php
            if( check_language() == 'de' ) {
                print '"language": {"url": "/lib/DataTables-1.10.16/i18n/German.json"}';
            }
        ?>
    });
    
    $('#projecttable').DataTable({
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
