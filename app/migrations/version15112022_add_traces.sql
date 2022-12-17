CREATE TABLE `aflauto`.`traces` ( `id` TINYINT(30) UNSIGNED NOT NULL AUTO_INCREMENT , `id_user` INT(11) NOT NULL , `type` VARCHAR(255) NOT NULL , `action` VARCHAR(255) NOT NULL , `triggered_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `traces` ADD CONSTRAINT `traces_id_user_fk` FOREIGN KEY (`id_user`) REFERENCES `users`(`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;