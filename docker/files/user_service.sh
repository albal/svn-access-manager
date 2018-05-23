#!/bin/bash
	
echo "user service"

tempSqlFile='/tmp/mysql-first-time.sql'
cat > "$tempSqlFile" <<-EOSQL
	DELETE FROM mysql.user ;
	CREATE USER 'root'@'%' IDENTIFIED BY '${MYSQL_ROOT_PASSWORD}' ;
	GRANT ALL ON *.* TO 'root'@'%' WITH GRANT OPTION ;
	DROP DATABASE IF EXISTS test ;
EOSQL

if [ "$MYSQL_DATABASE" ]; then
	echo "CREATE DATABASE IF NOT EXISTS \`$MYSQL_DATABASE\` ;" >> "$tempSqlFile"
	if [ "$MYSQL_CHARSET" ]; then
		echo "ALTER DATABASE \`$MYSQL_DATABASE\` CHARACTER SET \`$MYSQL_CHARSET\` ;" >> "$tempSqlFile"
	fi
	
	if [ "$MYSQL_COLLATION" ]; then
		echo "ALTER DATABASE \`$MYSQL_DATABASE\` COLLATE \`$MYSQL_COLLATION\` ;" >> "$tempSqlFile"
	fi
fi

if [ "$MYSQL_USER" -a "$MYSQL_PASSWORD" ]; then
	echo "CREATE USER '$MYSQL_USER'@'%' IDENTIFIED BY '$MYSQL_PASSWORD' ;" >> "$tempSqlFile"
	
	if [ "$MYSQL_DATABASE" ]; then
		echo "GRANT ALL ON \`$MYSQL_DATABASE\`.* TO '$MYSQL_USER'@'%' ;" >> "$tempSqlFile"
	fi
fi

echo 'FLUSH PRIVILEGES ;' >> "$tempSqlFile"

sleep 10
mysql -u root < "$tempSqlFile"
	
echo "user service finished"
