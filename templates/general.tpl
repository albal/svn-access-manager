<div>      
    <h3 class="page-header"><?php print _("Account information"); ?></h3> 
</div>
<div>
    <form class="form-horizontal" name="general" method="post">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="userid"><?php print _("Username"); ?>:</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" id="userid" name="fUserid" value="<?php print $tUserid; ?>" readonly />
                    </div>
                </div>
                <div class="form-group <?php print outputResponseClasses($tGivennameError); ?>">
                    <label class="col-sm-3 control-label" for="givename"><?php print _("Given name"); ?>:</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" id="givenname" name="fGivenname" value="<?php print $tGivenname; ?>" data-toggle="tooltip" title="<?php print _("Enter the given name of the user.");?>" />
                        <?php print outputResponseSpan($tGivennameError); ?>
                    </div>
                </div>
                <div class="form-group <?php print outputResponseClasses($tNameError); ?>">
                    <label class="col-sm-3 control-label" for="name"><?php print _("Name"); ?>:</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" id="name" name="fName" value="<?php print $tName; ?>" data-toggle="tooltip" title="<?php print _("Enter the name of the user.");?>" />
                        <?php print outputResponseSpan($tNameError); ?>
                    </div>
                </div>
                <div class="form-group <?php print outputResponseClasses($tEmailError); ?>">
                    <label class="col-sm-3 control-label" for="emailaddress"><?php print _("Email"); ?>:</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" id="emailaddress" name="fEmail" value="<?php print $tEmail; ?>" size="40" data-toggle="tooltip" title="<?php print _("Enter the email address of the user. Please fill in a valid email address. Otherwise the user will not be able to receive notifications.");?>" />
                        <?php print outputResponseSpan($tEmailError); ?>
                    </div>
                </div>
                <?php
                    if (isset($CONF['column_custom1'])) {
                        print '<div class="form-group '.outputResponseClasses($tCustom1Error).'">';
                        print '<label class="col-sm-3 control-label" for="custom1">'.$CONF['column_custom1'].'</label>';
                        print '<div class="col-sm-5">';
                        print '<input type="text" class="form-control" id="custom1" name="fCustom1" value="'.$tCustom1.'" />';
                        print outputResponseSpan($tCustom1Error);
                        print '</div>';
                        print '</div>';
                    }
                    if (isset($CONF['column_custom2'])) {
                       print '<div class="form-group '.outputResponseClasses($tCustom3Error).'">';
                        print '<label class="col-sm-3 control-label" for="custom2">'.$CONF['column_custom2'].'</label>';
                        print '<div class="col-sm-5">';
                        print '<input type="text" class="form-control" id="custom2" name="fCustom2" value="'.$tCustom2.'" />';
                        print outputResponseSpan($tCustom1Error);
                        print '</div>';
                        print '</div>';
                    }
                    if (isset($CONF['column_custom3'])) {
                        print '<div class="form-group '.outputResponseClasses($tCustom3Error).'">';
                        print '<label class="col-sm-3 control-label" for="custom3">'.$CONF['column_custom3'].'</label>';
                        print '<div class="col-sm-5">';
                        print '<input type="text" class="form-control" id="custom3" name="fCustom3" value="'.$tCustom3.'" />';
                        print outputResponseSpan($tCustom1Error);
                        print '</div>';
                        print '</div>';
                    }
                ?>
            </div>
            <div class="col-sm-6">
                <div class="form-group <?php print outputResponseClasses($tSecurityQuestionError); ?>">
                    <label class="col-sm-3 control-label" for="secquestion"><?php print _("Security question"); ?>:</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" id="secquestion" name="fSecurityQuestion" value="<?php print $tSecurityQuestion;?>" data-toggle="tooltip" title="<?php print _("Question to answer before a password reset."); ?>" />
                        <?php print outputResponseSpan($tSecurityQuestionError); ?>
                    </div>
                </div>
                <div class="form-group <?php print outputResponseClasses($tAnswerError); ?>">
                    <label class="col-sm-3 control-label" for="secquestionanswer"><?php print _("Security question answer"); ?>:</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" id="secquestionanswer" name="fAnswer" value="<?php print $tAnswer;?>" data-toggle="tooltip" title="<?php print _("Answer to the security question. The answer is case sensitive must be given exactly as written here.");?>" />
                        <?php print outputResponseSpan($tAnswerError); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="pwmod"><?php print _("Password modified"); ?>:</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" id="pwmod" name="fpwModified" value="<?php print $tPwModified; ?>" readonly />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="pwexpires"><?php print _("Password expires"); ?>:</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" id="pwexpires" name="fPasswordExpires" value="<?php print $tPasswordExpires; ?>" readonly />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="locked"><?php print _("Locked"); ?>:</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" id="locked" name="fLocked" value="<?php print $tLocked; ?>" readonly />
                    </div>
                </div>
            </div>
        </div>
        
        
        
        <div class="input-group">
            <p>&nbsp;</p>
        </div>
        <div class="input-group">
            <h3><?php print _("Group membership");?></h3>
        </div>
        <table id="showusergrouptable" class="table table-striped table-bordered" style="width:100%">
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
           <h3><?php print _("Project responsible");?></h3>
        </div>
        <table id="showprojecttable" class="table table-striped table-bordered" style="width:100%">
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
            <h3><?php print _("Access rights");?></h3>
        </div>
        <table id="showuserrighttable" class="table table-striped table-bordered" style="width:100%">
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
        
        <div class="input-group">
            <p>&nbsp;</p>
        </div>    
        <div class="input-group">
            <button class="btn btn-sm btn-primary" data-toggle="tooltip" type="submit" name="fSubmit_ok" title="<?php print _("Save"); ?>"><span class="glyphicon glyphicon-save"></span> <?php print _("Save"); ?></button>
            <button class="btn btn-sm" data-toggle="tooltip" type="submit" name="fSubmit_back" title="<?php print _("Back"); ?>"><span class="glyphicon glyphicon-arow-left"></span> <?php print _("Back"); ?></button>
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
    $('#showusergrouptable').DataTable({
        "order": [[ 0, "asc" ]],
        stateSave: true,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    });
    
    $('#showprojecttable').DataTable({
        "order": [[ 0, "asc" ]],
        stateSave: true,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    });
    
    $('#showuserrighttable').DataTable({
        "order": [[ 0, "asc" ]],
        stateSave: true,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    });
    
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip(); 
    });
} );
</script>
