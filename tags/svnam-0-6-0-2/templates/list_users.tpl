<div>    
    <h3 class="page-header"><?php print _("User administration"); ?></h3> 
</div>
<?php 
    outputMessage($tMessage, $tMessageType);
?>
<div>
    <form class="form-horizontal" name="general" method="post">
        <table id="usertable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr >
                    <th>
                        <?php print _("UserId"); ?>
                    </th>
                    <th>
                        <?php print _("Name"); ?>
                    </th>
                    <th>
                        <?php print _("Given name"); ?>
                    </th>
                    <th>
                        <?php print _("Email"); ?>
                    </th>
                    <?php
                            outputCustomFields();
                    ?>
                    <th>
                        <?php print _("Right"); ?>
                    </th>
                    <th>
                        <?php print _("Locked"); ?>
                    </th>
                    <th>
                        <?php print _("Password changed"); ?>
                    </th>
                    <th>
                        <?php print _("Password expires"); ?>
                    </th>
                    <th>
                        <?php print _("Administrator"); ?>
                    </th>
                    <?php
                            if( (isset($CONF[USE_LDAP])) && (strtoupper($CONF[USE_LDAP]) == "YES") ) {
                                    print "\t\t\t\t\t\t<th>\n";
                                    print "\t\t\t\t\t\t\t"._("LDAP User");
                                    print "\t\t\t\t\t\t</th>\n";
                            }
                    ?>
                    <th>
                        <?php print _("Action"); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                    outputUsers($tUsers, $rightAllowed);
                ?>
            </tbody>
            <tfoot>
                <tr >
                    <th>
                        <?php print _("UserId"); ?>
                    </th>
                    <th>
                        <?php print _("Name"); ?>
                    </th>
                    <th>
                        <?php print _("Given name"); ?>
                    </th>
                    <th>
                        <?php print _("Email"); ?>
                    </th>
                    <?php
                            outputCustomFields();
                    ?>
                    <th>
                        <?php print _("Right"); ?>
                    </th>
                    <th>
                        <?php print _("Locked"); ?>
                    </th>
                    <th>
                        <?php print _("Password changed"); ?>
                    </th>
                    <th>
                        <?php print _("Password expires"); ?>
                    </th>
                    <th>
                        <?php print _("Administrator"); ?>
                    </th>
                    <?php
                            if( (isset($CONF[USE_LDAP])) && (strtoupper($CONF[USE_LDAP]) == "YES") ) {
                                    print "\t\t\t\t\t\t<th>\n";
                                    print "\t\t\t\t\t\t\t"._("LDAP User");
                                    print "\t\t\t\t\t\t</th>\n";
                            }
                    ?>
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
            <button class="btn btn-sm btn-primary" data-toggle="tooltip" type="submit" name="fSubmit_new" title="<?php print _("New user"); ?>"><span class="glyphicon glyphicon-plus-sign"></span> <?php print _("New user"); ?></button>
            <button class="btn btn-sm" data-toggle="tooltip" type="submit" name="fSubmit_back" title="<?php print _("Back"); ?>"><span class="glyphicon glyphicon-menu-left"></span> <?php print _("Back"); ?></button>
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
} );
</script>
