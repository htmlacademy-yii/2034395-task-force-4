CREATE DATABASE taskForce
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_general_ci;

use taskForce;

CREATE TABLE `categories`
(
  `id`   INT AUTO_INCREMENT PRIMARY KEY,
  `name` CHAR(64),
  `icon` CHAR(64)
);

CREATE TABLE `users`
(
  `id`                INT AUTO_INCREMENT PRIMARY KEY,
  `email`             VARCHAR(320) UNIQUE,
  `username`          VARCHAR(128),
  `password`          CHAR(64),
  `city`              VARCHAR(128),
  `is_executor`       BOOL,
  `avatar_url`        VARCHAR(2048),
  `birthday`          TIMESTAMP,
  `phone_number`      VARCHAR(32),
  `telegram`          VARCHAR(128),
  `details`           TEXT,
  `registration_date` TIMESTAMP
);

CREATE TABLE `user_categories`
(
  `id`          INT AUTO_INCREMENT PRIMARY KEY,
  `user_id`     INT,
  `category_id` INT,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
);

CREATE TABLE `tasks`
(
  `id`             INT AUTO_INCREMENT PRIMARY KEY,
  `status`         TINYTEXT,
  `creation_date`  TIMESTAMP,
  `title`          TINYTEXT,
  `details`        TEXT,
  `category_id`    INT,
  `customer_id`    INT,
  `executor_id`    INT,
  `location`       TEXT,
  `budget`         INT,
  `execution_date` TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`),
  FOREIGN KEY (`executor_id`) REFERENCES `users` (`id`),
  FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
);

CREATE TABLE `files`
(
  `id`   INT AUTO_INCREMENT PRIMARY KEY,
  `url`  VARCHAR(2048),
  `type` VARCHAR(16)
);

CREATE TABLE `task_files`
(
  `id`      INT AUTO_INCREMENT PRIMARY KEY,
  `task_id` INT,
  `file_id` INT,
  FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`),
  FOREIGN KEY (`file_id`) REFERENCES `files` (`id`)
);

CREATE TABLE `responses`
(
  `id`            INT AUTO_INCREMENT PRIMARY KEY,
  `creation_date` TIMESTAMP,
  `text`          TEXT,
  `executor_id`   INT,
  `task_id`       INT,
  FOREIGN KEY (`executor_id`) REFERENCES `users` (`id`),
  FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`)
);

CREATE TABLE `reviews`
(
  `id`          INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT,
  `executor_id` INT,
  `task_id`     INT,
  `grade`       INT,
  `text`        TEXT,
  FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`),
  FOREIGN KEY (`executor_id`) REFERENCES `users` (`id`),
  FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`)
);

CREATE TABLE `cities`
(
  `id`   INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(128),
  `lat`  FLOAT,
  `long` FLOAT
)
