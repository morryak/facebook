#!/bin/bash
set -e

echo '##########'
echo 'CH Database configuration script'
env
echo '##########'

clickhouse-client --host localhost --secure --port 8123 --user root --password $CLICKHOUSE_ROOT_PASSWORD <<-EOSQL
    CREATE USER IF NOT EXISTS $CLICKHOUSE_USER@'%' IDENTIFIED BY '$CLICKHOUSE_PASSWORD';

    DROP DATABASE IF EXISTS $CLICKHOUSE_DATABASE;
    CREATE DATABASE $CLICKHOUSE_DATABASE;
    CREATE USER IF NOT EXISTS $CLICKHOUSE_USER@'%' IDENTIFIED BY '$CLICKHOUSE_PASSWORD';
    GRANT ALL PRIVILEGES ON $CLICKHOUSE_DATABASE.* TO $CLICKHOUSE_USER@'%';
EOSQL
