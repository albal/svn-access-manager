<div class="form-group">      
    <h3 class="page-header"><?php print _("Installation instructions"); ?></h3> 
</div>
<div class="form-group">                         
    <p class="form-control-static">
        <?php 
            print _("Please fill in the values in the following tabs to start the installation of SVN Access Manager. For automatic database installation you need to have a database user with sufficient rights."); 
        ?>                                 
    </p>
</div>
<div class="form-group">
    <p class="form-control-static">
        <?php 
            printf( _("Please be sure that the webserver is able to write the config directory '%s' to create the config.inc.php file for you. "), $tConfigDir );    
            if( determineOs() != "windows" ) {
                print _("To achieve this on Linux/Unix systems you can either change the owner of the directory to the webserver user or change the directory permissions to 'world writable' for the time of installation. Please set the directory permissions back after installation if you set the permissions to 'world writable'.");
            }
        ?>
    </p>
</div>
<?php
    if( ini_get( 'mysql.allow_persistent' ) != 1 ) {
        print "<div class=\"form-group\">\n";
        print "\t<p class=\"form-control-static alert alert-info\">\n";
        print "\t\t"._("Please make sure that in your php.ini file the varibale mysql.allow_persistent is set to on! Otherwise you may have problems with login after the installation. Please set the variable mysql.allow_persistent to on before you proceed with the installation. Don't forget to restart your webserver after changing the value of mysql.allow_persistent!")."\n";
        print "\t</p>\n";
        print "</div>\n";
    }

    if( ini_get( 'date.timezone' ) == "" ) {
    print "<div class=\"form-group\">\n";
        print "\t<p class=\"form-control-static alert alert-info\">\n";
        print "\t\t"._("Please make sure that in your php.ini file the varibale date.timezone is set to a value according to your timezone. Don't forget to restart your webserver after changing the value of date.timezone!")."\n";
        print "\t</p>\n";
        print "</div>\n";
    }
?>
 <div class="form-group">
    <label class="col-sm-3 control-label" for="description"><?php print _("Base directory"); ?>:</label>
    <div class="col-sm-6">
        <p class="form-control-static"><?php print $tBaseDir;?></p>
    </div>
    <div class="col-sm-3">
        <p class="form-control-static">&nbsp;</p>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="description"><?php print _("Configuration directory"); ?>:</label>
    <div class="col-sm-6">
        <p class="form-control-static"><?php print $tConfigDir;?></p>
    </div>
    <div class="col-sm-3 <?php print outputResponseClasses( (($tConfigWritable == _("writable")) ? 'ok' : 'warn') ); ?>">
        <p class="form-control-static"><?php print $tConfigWritable.' '.outputResponseSpan( (($tConfigWritable == _("writable")) ? 'ok' : 'warn') ); ?></p>
    </div>
</div>
<div class="form-group">                         
    <p class="form-control-static">
        <?php 
            print _("Please fill in the values in the following tabs to start the installation of SVN Access Manager. For automatic database installation you need to have a database user with sufficient rights."); 
        ?>                                 
    </p>
</div>
