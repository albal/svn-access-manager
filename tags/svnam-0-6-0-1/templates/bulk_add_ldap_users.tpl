<div>      
    <h3 class="page-header"><?php print _("Bulk add LDAP users"); ?></h3> 
</div>
<?php 
    outputMessage($tMessage, $tMessageType);
?>
<div>
    <form class="form-horizontal" name="workOnUser" method="post">
        <table id="ldapusertable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th class="ui-table-deactivate">
                        <?php print _("Action"); ?>
                    </th>
                    <th class="ui-table-default">
                        <?php print _("UserId"); ?>
                    </th>
                    <th class="ui-table-default">
                        <?php print _("Name"); ?>
                    </th>
                    <th class="ui-table-default">
                        <?php print _("Given name"); ?>
                    </th>
                    <th class="ui-table-default">
                        <?php print _("Email"); ?>
                    </th>
                    <th>
                        <?php print _("Repository user access").": ";?>
                        <select class="selectpicker" name="fUserRight" data-toggle="tooltip" title="<?php print _('This right overrules the repository access right settings. A user with read permission only can\'t get write access to any repository! All users will be added with the same right!'); ?>" >
                        <?php
                            if( $tUserRight == "read" ) {
                                print "\t\t\t\t\t\t\t\t<option value='read' selected>"._("read")."</option>\n";
                                print "\t\t\t\t\t\t\t\t<option value='write'>"._("write")."</option>\n";
                            } else {
                                print "\t\t\t\t\t\t\t\t<option value='read'>"._("read")."</option>\n";
                                print "\t\t\t\t\t\t\t\t<option value='write' selected>"._("write")."</option>\n";
                            }
                        ?>
                        </select>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach( $tUsers as $userid => $entry ) {
                        
                        if( $entry['selected'] == 1 ) {
                            $checked            = "checked=checked";
                        } else {
                            $checked            = "";
                        }
                        
                        print "\t\t\t\t\t<tr>\n";
                        print "\t\t\t\t\t\t<td><input type=\"checkbox\" name=\"fToAdd[]\" value=\"".$entry['userid']."\" $checked $tDisabled /></td>\n";
                        print "\t\t\t\t\t\t<td>".$entry['userid']."</td>\n";
                        print "\t\t\t\t\t\t<td>".$entry['name']."</td>\n";
                        print "\t\t\t\t\t\t<td>".$entry['givenname']."</td>\n";
                        print "\t\t\t\t\t\t<td>".$entry['emailaddress']."</td>\n";
                        print "\t\t\t\t\t\t<td>&nbsp;</td>\n";
                        if( $entry['added'] == 1 ) {
                            print "\t\t\t\t\t\t<td>"._("User successfully added")."</td>\n";
                        } else {
                            print "\t\t\t\t\t\t<td>&nbsp;</td>\n";
                        }
                        print "\t\t\t\t\t</tr>\n";
                        
                    }
                ?>
            </tbody>
        </table>
        <div class="input-group">
            <p>&nbsp;</p>
        </div>    
        <div class="input-group">
            <button class="btn btn-sm btn-primary" data-toggle="tooltip" type="submit" name="fSubmit_new" title="<?php print _("Add user"); ?>"><span class="glyphicon glyphicon-plus-sign"></span> <?php print _("Add user"); ?></button>
            <button class="btn btn-sm" data-toggle="tooltip" type="submit" name="fSubmit_back" title="<?php print _("Back"); ?>"><span class="glyphicon glyphicon-menu-left"></span> <?php print _("Back"); ?></button>
        </div>
        <div class="input-group">
            <p>&nbsp;</p>
        </div>
    </form>
</div>
<script type="text/javascript">
    
    $('#ldapusertable').DataTable({
        stateSave: true,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "pageLength": <?php print getCurrentPageSize(); ?>,
        <?php
            if( check_language() == 'de' ) {
                print '"language": {"url": "/lib/DataTables-1.10.16/i18n/German.json"}';
            }
        ?>
    });
    
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip({animation: true, delay: {show: <?php print $CONF[TOOLTIP_SHOW]; ?>, hide: <?php print $CONF[TOOLTIP_HIDE]; ?>}}); 
    });
</script>
