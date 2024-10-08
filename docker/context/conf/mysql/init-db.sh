#!/bin/bash
set -e

echo '##########'
echo 'Database configuration script'
export $(grep -v '^#' .env | xargs)
env
echo '##########'

mysql -h localhost -u root -p$MYSQL_ROOT_PASSWORD <<-EOSQL
    CREATE USER IF NOT EXISTS $MYSQL_USER@'%' IDENTIFIED BY '$MYSQL_PASSWORD';

    DROP DATABASE IF EXISTS $MYSQL_DATABASE;
    CREATE DATABASE $MYSQL_DATABASE;
    CREATE USER IF NOT EXISTS $MYSQL_USER@'%' IDENTIFIED BY '$MYSQL_PASSWORD';
    GRANT ALL PRIVILEGES ON $MYSQL_DATABASE.* TO $MYSQL_USER@'%';
EOSQL
