#!/bin/bash

DATADIR="/var/lib/mysql"
	
if [ ! -d "$DATADIR/mysql" ]; then
	if [ -z "$MYSQL_ROOT_PASSWORD" -a -z "$MYSQL_ALLOW_EMPTY_PASSWORD" ]; then
		echo >&2 'error: database is uninitialized and MYSQL_ROOT_PASSWORD not set'
		echo >&2 '  Did you forget to add -e MYSQL_ROOT_PASSWORD=... ?'
		exit 1
	fi
	
	echo 'Running mysql_install_db ...'
	mysql_install_db --datadir="$DATADIR"
	echo 'Finished mysql_install_db'
fi
