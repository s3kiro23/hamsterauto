ALTER TABLE `request` ADD `requested_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `state`;

COMMIT;