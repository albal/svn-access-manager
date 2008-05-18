#!/usr/bin/perl

use DBI;
use strict;

# ---------------------------------------------------------------------------- #
# sub dbconnect
#     connect to a mysql database
# ---------------------------------------------------------------------------- #

sub dbconnect ( $$$$ ) {
        my( $dbhost, $dbname, $dbuser, $dbpass ) = @_;
        my( $dbh );

        unless ( $dbh = DBI->connect("DBI:mysql:$dbname:$dbhost",$dbuser,$dbpass, {AutoCommit => 0, RaiseError => 1}) ) {
                $dbh = undef;
                return (DBI::errstr, $dbh);
        } else {
                return ("ok", $dbh);
        }
}

# ---------------------------------------------------------------------------- #
# sub dbdisconnect
#     disconnect from the database
# ---------------------------------------------------------------------------- #

sub dbdisconnect ( $ ) {
        my( $dbh ) = @_;

        ($dbh and $dbh->disconnect) or die (DBI::errstr);
}

# ---------------------------------------------------------------------------- #
# main section
# ---------------------------------------------------------------------------- #

my( $error, $dbh, $stmth, $query, $data, $csnumber, $password, $first,
    $errorsvn, $dbhsvn, $locked, $admin, $passwordexpires, $fullname,
    $givenname, $name, $stmthinsert, $emailaddress );

($error, $dbh) = dbconnect( "10.207.136.60", "cvsadmin", "svnread", "j34l0u5" );

if(lc($error) eq "ok") {
	
	( $errorsvn, $dbhsvn ) = dbconnect( "10.207.136.68", "svnadmin", "svnadmin", "j34l0u5" );
	if(lc($errorsvn) eq "ok") {
	
		eval {
			$query		= "INSERT INTO svnusers (userid, name, givenname, password, passwordexpires, locked, emailaddress, created, created_user, password_modified) VALUE (?, ?, ?, ?, ?, ?, ?; ?; ?, ?)";
			$stmthinsert	= $dbh->prepare( $query );
			
	                $query          = "SELECT * FROM cvsusers";
	                $stmth          = $dbh->prepare( $query );
	                $stmth->execute();
	
	                while( $data = $stmth->fetchrow_hashref() ) {
	                        
	                        $csnumber       	= $data->{"csnumber"};
	                        $password       	= $data->{"password"};
	                        $fullname		= $data->{'fullanme'};
	                        $passwordexpires	= $data->{'passwordexpires'};
	                        $locked			= $data->{'locked'};
	                        $admin			= $data->{'admin'};
	                        $emailaddress		= $data->{'emailaddress'};
	
	                        ($givenname, $name)	= split / /, $fullname;
	                        
	                  	$stmthinsert->execute($csnumber, $name, $givenname, $password, $passwordexpires, $locked, $emailaddress, now(), 'migration', now());
	                }
	
	                $stmth->finish();
	        };
	        
	        if( $@ ) {
	                die $@."\n";
	        }
        
		dbdisconnect( $dbhsvn );	
	} else {
		
		die $errorsvn."\n";
		
	}
	
	dbdisconnect( $dbh );
} else {
	
	die $error."\n",
	
}

exit 0;