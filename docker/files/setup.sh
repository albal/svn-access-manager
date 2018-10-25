#!/bin/bash

sed -i "s/###DBHOST###/${DBHOST}/" /etc/svn-access-manager/config.inc.php
sed -i "s/###DBNAME###/${DBNAME}/" /etc/svn-access-manager/config.inc.php
sed -i "s/###DBUSER###/${DBUSER}/" /etc/svn-access-manager/config.inc.php
sed -i "s/###DBPASS###/${DBPASS}/" /etc/svn-access-manager/config.inc.php
sed -i "s/###CHARSET###/${CHARSET}/" /etc/svn-access-manager/config.inc.php
sed -i "s/###COLLATION###/${COLLATION}/" /etc/svn-access-manager/config.inc.php

exit 0