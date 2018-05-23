<div class="form-group">                         
    <p class="form-control-static">
        <?php print _("To get additional information just move the mouse over the input field or the label.");?>                               
    </p>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="createtables" data-toggle="tooltip" title="<?php print _("Select if the database tables will be created automatically. This requires create and drop privileges.");?>"><?php print _("Create datbase tables"); ?>:</label>
    <div class="col-sm-4">
        <label class="radio-inline"><input id="createtables" type="radio" name="fCreateDatabaseTables"  value="YES" <?php print $tCreateDatabaseTablesYes; ?> ><?php print _("Yes"); ?></label>
        <label class="radio-inline"><input id="createtables" type="radio" name="fCreateDatabaseTables"  value="NO" <?php print $tCreateDatabaseTablesNo; ?> ><?php print _("No"); ?></label>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="droptables" data-toggle="tooltip" title="<?php print _("If you select yes here the database tables are droped without taking a backup.");?>"><?php print _("Drop existing database tables"); ?>:</label>
    <div class="col-sm-4">
        <label class="radio-inline"><input id="droptables" type="radio" name="fDropDatabaseTables"  value="YES" <?php print $tDropDatabaseTablesYes; ?> ><?php print _("Yes"); ?></label>
        <label class="radio-inline"><input id="droptables" type="radio" name="fDropDatabaseTables"  value="NO" <?php print $tDropDatabaseTablesNo; ?> ><?php print _("No"); ?></label>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="databasetype"><?php print _("Drop existing datbase tables"); ?>:</label>
    <div class="col-sm-4">
        <label class="radio-inline"><input id="databasetype" type="radio" name="fDatabase"  value="mysql" <?php print $tDatabaseMySQL; ?> ><?php print _("MySQL"); ?></label>
        <label class="radio-inline"><input id="databasetype" type="radio" name="fDatabase"  value="mysqli" <?php print $tDatabaseMySQLi; ?> ><?php print _("MySQLI"); ?></label>
        <label class="radio-inline"><input id="databasetype" type="radio" name="fDatabase"  value="postgres8" <?php print $tDatabasePostgreSQL; ?> ><?php print _("PostgreSQL"); ?></label>
        <label class="radio-inline"><input id="databasetype" type="radio" name="fDatabase"  value="oci8" <?php print $tDatabaseOracle; ?> ><?php print _("Oracle"); ?></label>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="seddioindb" data-toggle="tooltip" title="<?php print _("Decide if you want to keep the PHP sessions in the database or in the file system.");?>"><?php print _("Hold sessions in database"); ?>:</label>
    <div class="col-sm-4">
        <label class="radio-inline"><input id="seddioindb" type="radio" name="fSessionInDatabase"  value="YES" <?php print $tSessionInDatabaseYes; ?> ><?php print _("Yes"); ?></label>
        <label class="radio-inline"><input id="seddioindb" type="radio" name="fSessionInDatabase"  value="NO" <?php print $tSessionInDatabaseNo; ?> ><?php print _("No"); ?></label>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="dbhost" data-toggle="tooltip" title="<?php print _("Enter the ip or the hostname of the database host"); ?>"><?php print _("Database host"); ?>:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="dbhost" name="fDatabaseHost" value="<?php print $tDatabaseHost; ?>" data-toggle="tooltip" title="<?php print _("Enter the ip or the hostname of the database host");?>" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="dbuser" data-toggle="tooltip" title="<?php print _("Enter the username for the database"); ?>"><?php print _("Database user"); ?>:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="dbuser" name="fDatabaseUser" value="<?php print $tDatabaseUser; ?>" data-toggle="tooltip" title="<?php print _("Enter the username for the database");?>" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="dbpass" data-toggle="tooltip" title="<?php print _("Enter the password for the database"); ?>"><?php print _("Database password"); ?>:</label>
    <div class="col-sm-4">
        <input type="password" class="form-control" id="dbpass" name="fDatabasePassword" value="<?php print $tDatabasePassword; ?>" data-toggle="tooltip" title="<?php print _("Enter the password for the database");?>" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="dbname" data-toggle="tooltip" title="<?php print _("Enter the name of the database"); ?>"><?php print _("Database name"); ?>:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="dbname" name="fDatabaseName" value="<?php print $tDatabaseName; ?>" data-toggle="tooltip" title="<?php print _("Enter the name of the database");?>" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="dbcharset" data-toggle="tooltip" title="<?php print _("Enter the character set you want to use" ); ?>"><?php print _("Database charset"); ?>:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="dbcharset" name="fDatabaseCharset" value="<?php print $tDatabaseCharset; ?>" data-toggle="tooltip" title="<?php print _("Enter the character set you want to use" );?>" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="dbcollation" data-toggle="tooltip" title="<?php print _("Enter the collation you want to use" ); ?>"><?php print _("Database collation"); ?>:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="dbcollation" name="fDatabaseCollation" value="<?php print $tDatabaseCollation; ?>" data-toggle="tooltip" title="<?php print _("Enter the collation you want to use" );?>" />
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label" for="dbschema" data-toggle="tooltip" title="<?php print _("Enter the database schema you want to use" ); ?>"><?php print _("Database schema"); ?>:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="dbschema" name="fDatabaseSchema" value="<?php print $tDatabaseSchema; ?>" data-toggle="tooltip" title="<?php print _("Enter the database schema you want to use" );?>" />
    </div>
</div>
<div class="input-group">
    <p>&nbsp;</p>
</div>    
<div class="input-group pull-right">
    <button class="btn btn-sm" data-toggle="tooltip" type="submit" name="fSubmit_testdb" title="<?php print _("Run a database connection test."); ?>"><span class="glyphicon glyphicon-ok"></span> <?php print _("Test database connection"); ?></button>
</div>
