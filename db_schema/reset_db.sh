#!/bin/bash
#
# Wipe the database
# $ ./reset_db username password

cd `dirname $0`

mysql -u$1 -p$2 new_forum <<< "TRUNCATE acquaintances;"
mysql -u$1 -p$2 new_forum <<< "TRUNCATE comments;"
mysql -u$1 -p$2 new_forum <<< "TRUNCATE favorites;"
mysql -u$1 -p$2 new_forum <<< "TRUNCATE login_attempts;"
mysql -u$1 -p$2 new_forum <<< "TRUNCATE pm_content;"
mysql -u$1 -p$2 new_forum <<< "TRUNCATE pm_inbox;"
mysql -u$1 -p$2 new_forum <<< "TRUNCATE pm_outbox;"
mysql -u$1 -p$2 new_forum <<< "TRUNCATE sessions;"
mysql -u$1 -p$2 new_forum <<< "TRUNCATE threads;"
mysql -u$1 -p$2 new_forum <<< "TRUNCATE titles;"
mysql -u$1 -p$2 new_forum <<< "TRUNCATE users;"
mysql -u$1 -p$2 new_forum <<< "TRUNCATE user_autologin;"
mysql -u$1 -p$2 new_forum <<< "TRUNCATE user_profiles;"
mysql -u$1 -p$2 new_forum <<< "TRUNCATE yh_invites;"

echo "Completed reset!"