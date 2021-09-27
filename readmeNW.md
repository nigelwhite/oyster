27.9.21 Tried to backup remote db to local but PDOException: SQLSTATE[42S02]: Base table or view not found: 1146 Table 'db.semaphore' doesn't exist: SELECT expire, value FROM {semaphore} WHERE name = :name; Array ( [:name] => variable_init ) in lock_may_be_available() (line 167 of /var/www/html/includes/lock.inc). Gave up

This site transferred to ddev from lando on 5.3.19 because lando kept having problems with not loading drush.

Top db backups
echo2 21.1.19 after update of core and backup_migrate
echo2
