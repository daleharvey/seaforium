#!/bin/bash
#
# Wipe the database and initialise the schema again
# $ ./reset_db username password

cd `dirname $0`

mysql -u$1 -p$2 <<< "DROP DATABASE new_forum; CREATE DATABASE new_forum;"

for file in *.sql; do
    mysql -u$1 -p$2 new_forum < $file
done
