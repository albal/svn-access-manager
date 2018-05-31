<div class="form-group">                         
    <p class="form-control-static">
        <?php print _("To get additional information just move the mouse over the input field or the label.");?>                               
    </p>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label class="col-sm-3 control-label" for="useldap" data-toggle="tooltip" title="<?php print _("Decide if you want to use LDAP. If you decide to use LDAP you must complete the following fields.");?>"><?php print _("Use LDAP"); ?>:</label>
            <div class="col-sm-4">
                <label class="radio-inline"><input id="useldap" type="radio" name="fUseLdap"  value="YES" <?php print $tUseLdapYes; ?> ><?php print _("Yes"); ?></label>
                <label class="radio-inline"><input id="useldap" type="radio" name="fUseLdap"  value="NO" <?php print $tUseLdapNo; ?> ><?php print _("No"); ?></label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="ldaphost" data-toggle="tooltip" title="<?php print _("Enter the ip or the hostname of the LDAP server"); ?>"><?php print _("LDAP host"); ?>:</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="ldaphost" name="fLdapHost" value="<?php print $tLdapHost; ?>" data-toggle="tooltip" title="<?php print _("Enter the ip or the hostname of the LDAP server");?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="ldapport" data-toggle="tooltip" title="<?php print _("Enter the port to connect to the LDAP server"); ?>"><?php print _("LDAP port"); ?>:</label>
            <div class="col-sm-4">
                <input type="number" class="form-control" id="ldapport" name="fLdapPort" value="<?php print $tLdapPort; ?>" data-toggle="tooltip" title="<?php print _("Enter the port to connect to the LDAP server");?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="ldapproto" data-toggle="tooltip" title="<?php print _("Choose the protocol for LDAP server communication.");?>"><?php print _("LDAP protocol"); ?>:</label>
            <div class="col-sm-4">
                <label class="radio-inline"><input id="ldapproto" type="radio" name="fLdapProtocol"  value="2" <?php print $tLdap2; ?> ><?php print _("2"); ?></label>
                <label class="radio-inline"><input id="ldapproto" type="radio" name="fLdapProtocol"  value="3" <?php print $tLdap3; ?> ><?php print _("3"); ?></label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="ldapdn" data-toggle="tooltip" title="<?php print _("Enter the dn to use for connect to the LDAP server."); ?>"><?php print _("LDAP bind dn"); ?>:</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="ldapdn" name="fLdapBinddn" value="<?php print $tLdapBinddn; ?>" data-toggle="tooltip" title="<?php print _("Enter the dn to use for connect to the LDAP server.");?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="ldappw" data-toggle="tooltip" title="<?php print _("Enter the password for connect to the LDAP server."); ?>"><?php print _("LDAP bind password"); ?>:</label>
            <div class="col-sm-4">
                <input type="password" class="form-control" id="ldappw" name="fLdapBindpw" value="<?php print $tLdapBindpw; ?>" data-toggle="tooltip" title="<?php print _("Enter the password for connect to the LDAP server.");?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="ldapuserdn" data-toggle="tooltip" title="<?php print _("Enter the dn where the users are found on the LDAP server."); ?>"><?php print _("LDAP user dn"); ?>:</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="ldapuserdn" name="fLdapUserdn" value="<?php print $tLdapUserdn; ?>" data-toggle="tooltip" title="<?php print _("Enter the dn where the users are found on the LDAP server.");?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="ldapusersearch" data-toggle="tooltip" title="<?php print _("Enter the attribute to search for users."); ?>"><?php print _("LDAP user filter attribute"); ?>:</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="ldapusersearch" name="fLdapUserFilter" value="<?php print $tLdapUserFilter; ?>" data-toggle="tooltip" title="<?php print _("Enter the attribute to search for users.");?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="ldapuserobject" data-toggle="tooltip" title="<?php print _("Enter the object class which identifies the users."); ?>"><?php print _("LDAP user object class"); ?>:</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="ldapuserobject" name="fLdapUserObjectclass" value="<?php print $tLdapUserObjectclass; ?>" data-toggle="tooltip" title="<?php print _("Enter the object class which identifies the users.");?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="ldapadditional" data-toggle="tooltip" title="<?php print _("Enter additional filters for users if needed."); ?>"><?php print _("LDAP user additional filter"); ?>:</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="ldapadditional" name="fLdapUserAdditionalFilter" value="<?php print $tLdapUserAdditionalFilter; ?>" data-toggle="tooltip" title="<?php print _("Enter additional filters for users if needed.");?>" />
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label class="col-sm-3 control-label" for="ldapuid" data-toggle="tooltip" title="<?php print _("Enter the attribute for the uid."); ?>"><?php print _("Uid"); ?>:</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="ldapuid" name="fLdapAttrUid" value="<?php print $tLdapAttrUid; ?>" data-toggle="tooltip" title="<?php print _("Enter the attribute for the uid.");?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="ldapname" data-toggle="tooltip" title="<?php print _("Enter the attribute for the name."); ?>"><?php print _("Name"); ?>:</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="ldapname" name="fLdapAttrName" value="<?php print $tLdapAttrName; ?>" data-toggle="tooltip" title="<?php print _("Enter the attribute for the name.");?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="ldapgivenname" data-toggle="tooltip" title="<?php print  _("Enter the attribute for the given name."); ?>"><?php print _("Givenname"); ?>:</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="ldapgivenname" name="fLdapAttrGivenname" value="<?php print $tLdapAttrGivenname; ?>" data-toggle="tooltip" title="<?php print _("Enter the attribute for the given name.");?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="ldapemail" data-toggle="tooltip" title="<?php print  _("Enter the attribute for the email address."); ?>"><?php print _("Email address"); ?>:</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="ldapemail" name="fLdapAttrMail" value="<?php print $tLdapAttrMail; ?>" data-toggle="tooltip" title="<?php print _("Enter the attribute for the email address.");?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="ldappassword" data-toggle="tooltip" title="<?php print _("Enter the attribute containing the user password."); ?>"><?php print _("User password"); ?>:</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="ldappassword" name="fLdapAttrPassword" value="<?php print $tLdapAttrPassword; ?>" data-toggle="tooltip" title="<?php print _("Enter the attribute containing the user password.");?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="ldapsort" data-toggle="tooltip" title="<?php print _("Enter the attribute to be used for sorting users."); ?>"><?php print _("User sort attribute"); ?>:</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="ldapsort" name="fLdapAttrUserSort" value="<?php print $tLdapAttrUserSort; ?>" data-toggle="tooltip" title="<?php print _("Enter the attribute to be used for sorting users.");?>" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="ldapsortorder" data-toggle="tooltip" title="<?php print _("Select the sort order for LDAP users.");?>"><?php print _("LDAP user sort order"); ?>:</label>
            <div class="col-sm-4">
                <label class="radio-inline"><input id="ldapsortorder" type="radio" name="fLdapUserSort"  value="ASC" <?php print $tLdapUserSortAsc; ?> ><?php print _("ASC"); ?></label>
                <label class="radio-inline"><input id="ldapsortorder" type="radio" name="fLdapUserSort"  value="DESC" <?php print $tLdapUserSortDesc; ?> ><?php print _("DESC"); ?></label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="ldapad" data-toggle="tooltip" title="<?php print _("You should only say yes here if you are connecting against an Active Directory!");?>"><?php print _("LDAP bind with user login data"); ?>:</label>
            <div class="col-sm-4">
                <label class="radio-inline"><input id="ldapad" type="radio" name="fLdapBindUseLoginData"  value="1" <?php print $tLdapBindUseLoginDataYes; ?> ><?php print _("Yes"); ?></label>
                <label class="radio-inline"><input id="ldapad" type="radio" name="fLdapBindUseLoginData"  value="0" <?php print $tLdapBindUseLoginDataNo; ?> ><?php print _("No"); ?></label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="ldapsuffix" data-toggle="tooltip" title="<?php print _("Enter the LDAP bind dn suffix."); ?>"><?php print _("LDAP bind dn suffix"); ?>:</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="ldapsuffix" name="fLdapBindDnSuffix" value="<?php print $tLdapBindDnSuffix; ?>" data-toggle="tooltip" title="<?php print _("Enter the LDAP bind dn suffix.");?>" />
            </div>
        </div>
        <div class="input-group">
            <p>&nbsp;</p>
        </div>    
        <div class="input-group pull-right">
            <button class="btn btn-sm" data-toggle="tooltip" type="submit" name="fSubmit_testldap" title="<?php print  _("Do a LDAP connection test."); ?>"><span class="glyphicon glyphicon-ok"></span> <?php print  _("Test LDAP connection"); ?></button>
        </div>
    </div>
</div>
            



