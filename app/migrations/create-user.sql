-- CREATE USER 'db'@'%' IDENTIFIED WITH mysql_native_password BY 'Db123!@20';
CREATE USER 'ban'@'%' IDENTIFIED WITH mysql_native_password BY 'Db123!@66';
CREATE USER 'api'@'%' IDENTIFIED WITH mysql_native_password BY 'Db789!@50';
GRANT SELECT, DELETE ON `hamsterauto`.* TO 'ban'@'%';
GRANT SELECT, INSERT ON `hamsterauto`.* TO 'api'@'%';
-- GRANT SELECT, INSERT, UPDATE, DELETE, FILE ON `hamsterauto`.* TO 'db'@'%';

SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));
