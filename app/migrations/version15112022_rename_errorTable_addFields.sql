RENAME TABLE error TO login_attempts;

ALTER TABLE `login_attempts` ADD `email_user` VARCHAR(50) NOT NULL AFTER `id_user`;
ALTER TABLE `login_attempts` ADD `remote_ip` VARCHAR(255) NOT NULL AFTER `email_user`;