<div>      
    <h3 class="page-header"><?php print _("User administration / edit user"); ?></h3> 
</div>
<div>
    <form class="form-horizontal" name="workOnUser" method="post">
    
        <div class="form-group <?php print outputResponseClasses($tUseridError); ?>">
            <label class="col-sm-3 control-label" for="userid"><?php print _("Username"); ?>:</label>
            <div class="col-sm-9">
                <?php
                    if( (isset($CONF[USE_LDAP])) && (strtoupper($CONF[USE_LDAP]) == "YES") && ($_SESSION[SVNSESSID]['task'] == "new") ) {
                        
                        print "<select id=\"userid\" name=\"fUserid\" class=\"selectpicker\" $tReadonly onchange=\"changeUser();\">\n";
                        print "\t<option value=\"default\">"._("--- Please select user ---")."</option>\n";
                        foreach( $tUsers as $entry ) {
                            $value  = $entry['uid'].":".$entry['name'].":".$entry[GIVENNAME].":".$entry['emailaddress'].":";
                            if( $entry['uid'] == $tUserid ) {
                                print "\t<option value=\"".$value."\" selected>".$entry['name']." ".$entry[GIVENNAME]." (".$entry['uid'].")"."</option>\n";
                            } else {
                                print "\t<option value=\"".$value."\">".$entry['name']." ".$entry[GIVENNAME]." (".$entry['uid'].")"."</option>\n";
                            }
                        }
                        print "</select>\n";
                        
                    } else {
                        
                        print "<input type=\"text\" class=\"form-control\" id=\"userid\" name=\"fUserid\" value=\"".$tUserid."\" $tReadonly/>\n";
                        print outputResponseSpan($tUseridError);
                        
                    }
                ?>
            </div>
        </div>
        
        <div class="form-group <?php print outputResponseClasses($tNameError); ?>">
            <label class="col-sm-3 control-label" for="name"><?php print  _("Name"); ?>:</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="name" name="fName" value="<?php print $tName; ?>" data-toggle="tooltip" title="<?php print _("Enter the name of the user.");?>" />
                <?php print outputResponseSpan($tNameError); ?>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-3 control-label" for="givename"><?php print _("Given name"); ?>:</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="givenname" name="fGivenname" value="<?php print $tGivenname; ?>" data-toggle="tooltip" title="<?php print _("Enter the given name of the user.");?>" />
            </div>
        </div>
        
        <?php
            if( (isset($CONF[USE_LDAP])) && (strtoupper($CONF[USE_LDAP]) != "YES") ) {
            
                print "\t\t<div class=\"form-group ".outputResponseClasses($tPasswordError)."\">\n";
                print "\t\t\t<label class=\"col-sm-3 control-label\" for=\"password\">"._("Password").":</label>\n";
                print "\t\t\t<div class=\"col-sm-9\">\n";
                print "\t\t\t\t<input type=\"password\" class=\"form-control\" id=\"password\" name=\"fPassword\" value=\"".$tPassword."\" data-toggle=\"tooltip\" title=\"". _("Enter the password. Keep in mind that the password must be set accordingly to the password policy.")."\" />".outputResponseSpan($tPasswordError)."\n";
                print "\t\t\t</div>\n";
                print "\t\t</div>\n";
                
                print "\t\t<div class=\"form-group ".outputResponseClasses($tPassword2Error)."\">\n";
                print "\t\t\t<label class=\"col-sm-3 control-label\" for=\"password2\">"._("Retype password").":</label>\n";
                print "\t\t\t<div class=\"col-sm-9\">\n";
                print "\t\t\t\t<input type=\"password\" class=\"form-control\" id=\"password2\" name=\"fPassword2\" value=\"".$tPassword2."\" data-toggle=\"tooltip\" title=\"". _("Retype the password to avoid typos.")."\" />".outputResponseSpan($tPassword2Error)."\n";
                print "\t\t\t</div>\n";
                print "\t\t</div>\n";
            }
        ?>
        
        <div class="form-group <?php print outputResponseClasses($tEmailError); ?>">
            <label class="col-sm-3 control-label" for="name"><?php print  _("Email address"); ?>:</label>
            <div class="col-sm-9">
                <input type="email" class="form-control" id="name" name="fEmail" value="<?php print $tEmail; ?>" data-toggle="tooltip" title="<?php print _("Enter the email address of the user. Please fill in a valid email address. Otherwise the user will not be able to receive notifications.");?>" />
                <?php print outputResponseSpan($tEmailError); ?>
            </div>
        </div>
        
        <?php
            if (isset($CONF['column_custom1'])) {
                print "\t\t<div class=\"form-group\">\n";
                print "\t\t\t<label class=\"col-sm-3 control-label\" for=\"custom1\">".$CONF['column_custom1'].":</label>\n";
                print "\t\t\t<div class=\"col-sm-9\">\n";
                print "\t\t\t\t<input type=\"text\" class=\"form-control\" id=\"custom1\" name=\"fCustom1\" value=\"".$tCustom1."\" />\n";
                print "\t\t\t</div>\n";
                print "\t\t</div>\n";
            }
            if (isset($CONF['column_custom2'])) {
                print "\t\t<div class=\"form-group\">\n";
                print "\t\t\t<label class=\"col-sm-3 control-label\" for=\"custom2\">".$CONF['column_custom2'].":</label>\n";
                print "\t\t\t<div class=\"col-sm-9\">\n";
                print "\t\t\t\t<input type=\"text\" class=\"form-control\" id=\"custom2\" name=\"fCustom2\" value=\"".$tCustom2."\" />\n";
                print "\t\t\t</div>\n";
                print "\t\t</div>\n";
            }
            if (isset($CONF['column_custom3'])) {
                print "\t\t<div class=\"form-group\">\n";
                print "\t\t\t<label class=\"col-sm-3 control-label\" for=\"custom3\">".$CONF['column_custom3'].":</label>\n";
                print "\t\t\t<div class=\"col-sm-9\">\n";
                print "\t\t\t\t<input type=\"text\" class=\"form-control\" id=\"custom3\" name=\"fCustom3\" value=\"".$tCustom3."\" />\n";
                print "\t\t\t</div>\n";
                print "\t\t</div>\n";
            }
            if( (isset($CONF[USE_LDAP])) && (strtoupper($CONF[USE_LDAP]) != "YES") ) {
                print "\t\t<div class=\"form-group\">\n";
                print "\t\t\t<label class=\"col-sm-3 control-label\" for=\"custom1\">"._("Password expires").":</label>\n";
                print "\t\t\t<div class=\"col-sm-9\">\n";
                print "\t\t\t\t<select class=\"selectpicker\" id=\"userid\" name=\"fPasswordExpires\" class=\"selectpicker\" data-toggle=\"tooltip\" title=\""._("Select if the user password should expire.")."\" >\n";
                if( $tPasswordExpires == 0 ) {
                    print "\t\t\t\t\t<option value='0' selected>"._("no")."</option>\n";
                    print "\t\t\t\t\t<option value='1'>"._("yes")."</option>\n";
                } else {
                    print "\t\t\t\t\t<option value='0'>"._("no")."</option>\n";
                    print "\t\t\t\t\t<option value='1' selected>"._("yes")."</option>\n";
                }
                print "\t\t\t\t</select>\n";
                print "\t\t\t</div>\n";
                print "\t\t</div>\n";
            }
        ?>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="locked"><?php print  _("Locked"); ?>:</label>
            <div class="col-sm-9">
                <select id="locked" name="fLocked" class="selectpicker" <?php print $tDisabledAdmin;?> data-toggle="tooltip" title="<?php print _("A locked user can not work any longer with the subversion repositories. If the user password expiered, the user will be locked automatically.");?>">
                <?php
                        if( $tLocked == 0 ) {
                            print "\t\t\t\t\t<option value='0' selected>"._("no")."</option>\n";
                            print "\t\t\t\t\t<option value='1'>"._("yes")."</option>\n";
                        } else {
                            print "\t\t\t\t\t<option value='0'>"._("no")."</option>\n";
                            print "\t\t\t\t\t<option value='1' selected>"._("yes")."</option>\n";
                        }
                    ?>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-3 control-label" for="admin"><?php print  _("Administrator"); ?>:</label>
            <div class="col-sm-9">
                <select id="admin" name="fAdministrator" class="selectpicker" <?php print $tDisabledAdmin;?> data-toggle="tooltip" title="<?php print _("An administrator has more privileges as a normal user. Administrators have a stronger password policy as normal users.");?>">
                <?php
                        if( $tAdministrator == 0 ) {
                            print "\t\t\t\t\t<option value='0' selected>"._("no")."</option>\n";
                            print "\t\t\t\t\t<option value='1'>"._("yes")."</option>\n";
                        } else {
                            print "\t\t\t\t\t<option value='0'>"._("no")."</option>\n";
                            print "\t\t\t\t\t<option value='1' selected>"._("yes")."</option>\n";
                        }
                    ?>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-sm-3 control-label" for="userright"><?php print  _("Repository user right"); ?>:</label>
            <div class="col-sm-9">
                <select id="userright" name="fUserRight" class="selectpicker" <?php print $tDisabledAdmin;?> data-toggle="tooltip" title="<?php print _("This right overrules the repository access right settings. A user with read permission only can't get write access to any repository!");?>">
                <?php
                        if( $tUserRight == 'read' ) {
                            print "\t\t\t\t\t<option value='read' selected>"._("read")."</option>\n";
                            print "\t\t\t\t\t<option value='write'>"._("write")."</option>\n";
                        } else {
                            print "\t\t\t\t\t<option value='read'>"._("read")."</option>\n";
                            print "\t\t\t\t\t<option value='write' selected>"._("write")."</option>\n";
                        }
                    ?>
                </select>
            </div>
        </div>
        
        <div class="input-group">
            <label class="col-sm-12 control-label" for="text"><?php print  _("Select global user rights"); ?>:</label>
        </div>
        
        <?php
                                                
            $cnt = 0;
            foreach( $tRightsAvailable as $right ) {
            
                $id                     = $right['id'];
                
                print "\t\t<div class=\"form-group\">\n";
                print "\t\t\t<label class=\"col-sm-3 control-label\" for=\"label".$cnt."\">".$right['right_name'].":</label>\n";
                print "\t\t\t<div class=\"col-sm-9\">\n";
                print "\t\t\t\t<select id=\"label".$cnt."\" name=\"fId".$id."\" class=\"selectpicker\" data-toggle=\"tooltip\" title=\"".$right['description']."\">\n";
                
                $tNone                      = SELECTED;
                $tRead                      = "";
                $tAdd                       = "";
                $tEdit                      = "";
                $tDelete                    = "";           
                    
                if( isset($tRightsGranted[$id]) ) {
                    if(strtolower($tRightsGranted[$id]) == "read") {
                        $tNone              = "";
                        $tRead              = SELECTED;
                        $tAdd               = "";
                        $tEdit              = "";
                        $tDelete            = "";  
                    } elseif(strtolower($tRightsGranted[$id]) == "add" ) {
                        $tNone              = "";
                        $tRead              = "";
                        $tAdd               = SELECTED;
                        $tEdit              = "";
                        $tDelete            = "";
                    } elseif(strtolower($tRightsGranted[$id]) == "edit" ) {
                        $tNone              = "";
                        $tRead              = "";
                        $tAdd               = "";
                        $tEdit              = SELECTED;
                        $tDelete            = "";
                    } elseif(strtolower($tRightsGranted[$id]) == DELETE ) {
                        $tNone              = "";
                        $tRead              = "";
                        $tAdd               = "";
                        $tEdit              = "";
                        $tDelete            = SELECTED;
                    }
                }
                
                                            
                print "\t\t\t\t\t<option value='none' ".$tNone.">"._("none")."</option>\n";
                if( ($right[ALLOWED_ACTION] == "read")      ||  
                     ($right[ALLOWED_ACTION] == "add")      || 
                     ($right[ALLOWED_ACTION] == "edit")         || 
                     ($right[ALLOWED_ACTION] == DELETE) 
                  ) {
                    
                    print "\t\t\t\t\t<option value='read' ".$tRead.">"._("read")."</option>\n";
                }
                if( ($right[ALLOWED_ACTION] == "add" )      || 
                     ($right[ALLOWED_ACTION] == "edit")         || 
                     ($right[ALLOWED_ACTION] == DELETE) 
                  ) {
                    print "\t\t\t\t\t<option value='add' ".$tAdd.">"._("add")."</option>\n";
                }
                if( ($right[ALLOWED_ACTION] == "edit")      || 
                     ($right[ALLOWED_ACTION] == DELETE) 
                   ) {
                    
                    print "\t\t\t\t\t<option value='edit' ".$tEdit.">"._("edit")."</option>\n";
                }
                if($right[ALLOWED_ACTION] == DELETE) {
                
                    print "\t\t\t\t\t<option value='delete' ".$tDelete.">"._("delete")."</option>\n";
                }
                print "\t\t\t\t</select>\n";
                print "\t\t\t</div>\n";
                print "\t\t</div>\n";
                
                $cnt++;
            
            }
        ?>
        
        <div class="input-group">
            <p>&nbsp;</p>
        </div>    
        <div class="input-group">
            <button class="btn btn-sm btn-primary" data-toggle="tooltip" type="submit" name="fSubmit_ok" title="<?php print _("Save"); ?>"><span class="glyphicon glyphicon-save"></span> <?php print _("Save"); ?></button>
            <button class="btn btn-sm" data-toggle="tooltip" type="submit" name="fSubmit_back" title="<?php print _("Back"); ?>"><span class="glyphicon glyphicon-arrow-left"></span> <?php print _("Back"); ?></button>
        </div>
        <div class="input-group">
            <p>&nbsp;</p>
        </div>
        
         <?php 
            outputMessage($tMessage, $tMessageType);
        ?>
    </form>
</div>		
<script type="text/javascript">

    function changeUser() {
    
        var uid  = document.forms.workOnUser.fUserid.value;
        var arr  = uid.split(":");
        
        document.forms.workOnUser.fName.value = arr[1];
        document.forms.workOnUser.fGivenname.value = arr[2];
        document.forms.workOnUser.fEmail.value = arr[3];

    }
    
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip(); 
    });
</script>
