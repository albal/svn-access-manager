#!/bin/bash

if [ -f "/tmp/db.dump" ] ; then

	echo "loading database"
	sleep 1
	mysql -u root -p${MYSQL_ROOT_PASSWORD} -D $MYSQL_DATABASE < /tmp/db.dump
	rm -f /tmp/db.dump
	echo "loading database finished"
	
fi