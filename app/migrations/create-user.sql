CREATE USER 'db'@'%' IDENTIFIED WITH mysql_native_password BY 'Db123!@20';
CREATE USER 'script_bans'@'%' IDENTIFIED WITH mysql_native_password BY 'Db123!@66';
CREATE USER 'API_CT'@'%' IDENTIFIED WITH mysql_native_password BY 'Db789!@50';
GRANT SELECT, DELETE ON `hamsterauto`.* TO 'script_bans'@'%';
GRANT SELECT, INSERT ON `hamsterauto`.* TO 'API_CT'@'%';
GRANT SELECT, INSERT, DELETE, UPDATE ON `hamsterauto`.* TO 'db'@'%';