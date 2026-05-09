-- Create database if not exists
CREATE DATABASE IF NOT EXISTS `phonebook_db`;
USE `phonebook_db`;

-- =====================================
-- Users Table
-- =====================================
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `username_unique` (`username`)
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

-- =====================================
-- Contacts Table
-- =====================================
CREATE TABLE IF NOT EXISTS `contacts` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `email` VARCHAR(100) DEFAULT NULL,
    `image_path` VARCHAR(255) NOT NULL DEFAULT 'default.png',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),

    CONSTRAINT `fk_contacts_user`
        FOREIGN KEY (`user_id`)
        REFERENCES `users`(`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;