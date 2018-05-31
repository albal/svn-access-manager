<div class="form-group">                         
    <p class="form-control-static">
        <?php print _("To get additional information just move the mouse over the input field or the label.");?>                               
    </p>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="accessfile" data-toggle="tooltip" title="<?php print _("Decide if a svn access file should be generated.");?>"><?php print _("Use SVN Access File"); ?>:</label>
    <div class="col-sm-4">
        <label class="radio-inline"><input id="accessfile" type="radio" name="fUseSvnAccessFile"  value="YES" <?php print $tUseSvnAccessFileYes; ?> ><?php print _("Yes"); ?></label>
        <label class="radio-inline"><input id="accessfile" type="radio" name="fUseSvnAccessFile"  value="NO" <?php print $tUseSvnAccessFileNo; ?> ><?php print _("No"); ?></label>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="svnfile" data-toggle="tooltip" title="<?php print _("Enter the full path and the name of the SVN Access file. The webserver must be able to write the file."); ?>"><?php print  _("SVN Access File"); ?>:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="svnfile" name="fSvnAccessFile" value="<?php print $tSvnAccessFile; ?>" data-toggle="tooltip" title="<?php print _("Enter the full path and the name of the SVN Access file. The webserver must be able to write the file.");?>" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="acl" data-toggle="tooltip" title="<?php print _("You can choose whether access control is possible on directories only or on directories and files.");?>"><?php print _("Access control level"); ?>:</label>
    <div class="col-sm-4">
        <label class="radio-inline"><input id="acl" type="radio" name="fAccessControlLevel"  value="dirs" <?php print $tAccessControlLevelDirs; ?> ><?php print _("Directories"); ?></label>
        <label class="radio-inline"><input id="acl" type="radio" name="fAccessControlLevel"  value="files" <?php print $tAccessControlLevelFiles; ?> ><?php print _("Files"); ?></label>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="userfile" data-toggle="tooltip" title="<?php print _("Decide if a auth user file should be generated.");?>"><?php print _("Use Auth User File"); ?>:</label>
    <div class="col-sm-4">
        <label class="radio-inline"><input id="userfile" type="radio" name="fUseAuthUserFile"  value="YES" <?php print $tUseAuthUserFileYes; ?> ><?php print _("Yes"); ?></label>
        <label class="radio-inline"><input id="userfile" type="radio" name="fUseAuthUserFile"  value="NO" <?php print $tUseAuthUserFileNo; ?> ><?php print _("No"); ?></label>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="authuserfile" data-toggle="tooltip" title="<?php print _("Enter the full path and the name of the Auth User file for the webserver authentication of users. The webserver must be able to write the file."); ?>"><?php print  _("Auth User file"); ?>:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="authuserfile" name="fAuthUserFile" value="<?php print $tAuthUserFile; ?>" data-toggle="tooltip" title="<?php print _("Enter the full path and the name of the Auth User file for the webserver authentication of users. The webserver must be able to write the file.");?>" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="perrepofile" data-toggle="tooltip" title="<?php print _("Decide if an access file for each repository or one access file for all repositories should be created.");?>"><?php print _("Create per repository access files"); ?>:</label>
    <div class="col-sm-4">
        <label class="radio-inline"><input id="perrepofile" type="radio" name="fPerRepoFiles"  value="YES" <?php print $tPerRepoFilesYes; ?> ><?php print _("Yes"); ?></label>
        <label class="radio-inline"><input id="perrepofile" type="radio" name="fPerRepoFiles"  value="NO" <?php print $tPerRepoFilesNo; ?> ><?php print _("No"); ?></label>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="sortorder" data-toggle="tooltip" title="<?php print _("Select the sort order in the access file.");?>"><?php print _("SVN access file sort order"); ?>:</label>
    <div class="col-sm-4">
        <label class="radio-inline"><input id="sortorder" type="radio" name="fPathSortOrder"  value="ASC" <?php print $tPathSortOrderAsc; ?> ><?php print _("ASC"); ?></label>
        <label class="radio-inline"><input id="sortorder" type="radio" name="fPathSortOrder"  value="DESC" <?php print $tPathSortOrderDesc; ?> ><?php print _("DESC"); ?></label>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label" for="anon" data-toggle="tooltip" title="<?php print  _("This option allowes you to create an entry '\$anonymous = r' for the top level directory of each repository.");?>"><?php print _("Anonymous read access"); ?>:</label>
    <div class="col-sm-4">
        <label class="radio-inline"><input id="anon" type="radio" name="fAnonAccess"  value="1" <?php print $tAnonAccessYes; ?> ><?php print _("Yes"); ?></label>
        <label class="radio-inline"><input id="anon" type="radio" name="fAnonAccess"  value="0" <?php print $tAnonAccessNo; ?> ><?php print _("No"); ?></label>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="viewvc" data-toggle="tooltip" title="<?php print _("Decide if a ViewVC configuration file should be generated.");?>"><?php print _("Create ViewVC configuration"); ?>:</label>
    <div class="col-sm-4">
        <label class="radio-inline"><input id="viewvc" type="radio" name="fViewvcConfig"  value="YES" <?php print $tViewvcConfigYes; ?> ><?php print _("Yes"); ?></label>
        <label class="radio-inline"><input id="viewvc" type="radio" name="fViewvcConfig"  value="NO" <?php print $tViewvcConfigNo; ?> ><?php print _("No"); ?></label>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="viewvcdir" data-toggle="tooltip" title="<?php print _("Enter the full path to the directory where to save the viewvc configuration files. The webserver must be able to write the file."); ?>"><?php print  _("Auth User file"); ?>:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="viewvcdir" name="fViewvcConfigDir" value="<?php print $tViewvcConfigDir; ?>" data-toggle="tooltip" title="<?php print _("Enter the full path to the directory where to save the viewvc configuration files. The webserver must be able to write the file.");?>" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="viewvcrealm" data-toggle="tooltip" title="<?php print _("Enter the realm for the webserver authentication."); ?>"><?php print  _("ViewVC realm"); ?>:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="viewvcrealm" name="fViewvcRealm" value="<?php print $tViewvcRealm; ?>" data-toggle="tooltip" title="<?php print _("Enter the realm for the webserver authentication.");?>" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="alias" data-toggle="tooltip" title="<?php print _("Enter the alias you used in your webserver."); ?>"><?php print  _("ViewVC webserver alias"); ?>:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="alias" name="fViewvcAlias" value="<?php print $tViewvcAlias; ?>" data-toggle="tooltip" title="<?php print _("Enter the alias you used in your webserver.");?>" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="reload" data-toggle="tooltip" title="<?php print _("Enter a command to restart the Apache webserver. The command must be executable by the webserver user. You can use sudo to achieve this."); ?>"><?php print  _("ViewVC webserver reload command"); ?>:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="reload" name="fViewvcApacheReload" value="<?php print $tViewvcApacheReload; ?>" data-toggle="tooltip" title="<?php print _("Enter a command to restart the Apache webserver. The command must be executable by the webserver user. You can use sudo to achieve this.");?>" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="svncmd" data-toggle="tooltip" title="<?php print _("Enter the full path and the name of the svn command."); ?>"><?php print  _("svn command"); ?>:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="svncmd" name="fSvnCommand" value="<?php print $tSvnCommand; ?>" data-toggle="tooltip" title="<?php print _("Enter the full path and the name of the svn command.");?>" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="svnamincmd" data-toggle="tooltip" title="<?php print _("Enter the full path and the name of the svnadmin command."); ?>"><?php print _("svnadmin command"); ?>:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="svnamincmd" name="fSvnadminCommand" value="<?php print $tAuthUserFile; ?>" data-toggle="tooltip" title="<?php print _("Enter the full path and the name of the svnadmin command.");?>" />
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label" for="grepcmd" data-toggle="tooltip" title="<?php print _("Enter the full path and the name of the grep command."); ?>"><?php print _("grep command"); ?>:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="grepcmd" name="fGrepCommand" value="<?php print $tGrepCommand; ?>" data-toggle="tooltip" title="<?php print _("Enter the full path and the name of the grep command.");?>" />
    </div>
</div>
