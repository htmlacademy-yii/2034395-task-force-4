CREATE DATABASE taskForce
    DEFAULT CHARACTER SET utf8;

use taskForce;

CREATE TABLE `category`
(
    `id`   INT AUTO_INCREMENT PRIMARY KEY,
    `name` CHAR(64),
    `icon` CHAR(64)
);

CREATE TABLE `city`
(
    `id`   INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(128),
    `lat`  FLOAT,
    `long` FLOAT
);

CREATE TABLE `user`
(
    `id`                INT AUTO_INCREMENT PRIMARY KEY,
    `status`            VARCHAR(32),
    `email`             VARCHAR(320) UNIQUE,
    `username`          VARCHAR(128),
    `age`               INT,
    `password`          CHAR(64),
    `city_id`           INT,
    `is_executor`       BOOL,
    `avatar_url`        VARCHAR(2048),
    `birthday`          TIMESTAMP,
    `phone_number`      VARCHAR(32),
    `telegram`          VARCHAR(128),
    `details`           TEXT,
    `registration_date` TIMESTAMP,
    FOREIGN KEY (`city_id`) REFERENCES `city` (`id`)
);

CREATE TABLE `user_category`
(
    `id`          INT AUTO_INCREMENT PRIMARY KEY,
    `user_id`     INT,
    `category_id` INT,
    FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
    FOREIGN KEY (`category_id`) REFERENCES `category` (`id`)
);

CREATE TABLE `task`
(
    `id`             INT AUTO_INCREMENT PRIMARY KEY,
    `status`         TINYTEXT,
    `creation_date`  TIMESTAMP,
    `title`          TINYTEXT,
    `details`        TEXT,
    `category_id`    INT,
    `customer_id`    INT,
    `executor_id`    INT,
    `city_id`        INT,
    `location`       TEXT,
    `budget`         INT,
    `execution_date` TIMESTAMP,
    FOREIGN KEY (`customer_id`) REFERENCES `user` (`id`),
    FOREIGN KEY (`executor_id`) REFERENCES `user` (`id`),
    FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
    FOREIGN KEY (`city_id`) REFERENCES `city` (`id`)
);

CREATE TABLE `file`
(
    `id`   INT AUTO_INCREMENT PRIMARY KEY,
    `url`  VARCHAR(2048),
    `type` VARCHAR(16)
);

CREATE TABLE `task_file`
(
    `id`      INT AUTO_INCREMENT PRIMARY KEY,
    `task_id` INT,
    `file_id` INT,
    FOREIGN KEY (`task_id`) REFERENCES `task` (`id`),
    FOREIGN KEY (`file_id`) REFERENCES `file` (`id`)
);

CREATE TABLE `response`
(
    `id`            INT AUTO_INCREMENT PRIMARY KEY,
    `creation_date` TIMESTAMP,
    `text`          TEXT,
    `price`         INT,
    `executor_id`   INT,
    `task_id`       INT,
    FOREIGN KEY (`executor_id`) REFERENCES `user` (`id`),
    FOREIGN KEY (`task_id`) REFERENCES `task` (`id`)
);

CREATE TABLE `review`
(
    `id`            INT AUTO_INCREMENT PRIMARY KEY,
    `creation_date` TIMESTAMP,
    `customer_id`   INT,
    `executor_id`   INT,
    `task_id`       INT,
    `grade`         INT,
    `text`          TEXT,
    FOREIGN KEY (`customer_id`) REFERENCES `user` (`id`),
    FOREIGN KEY (`executor_id`) REFERENCES `user` (`id`),
    FOREIGN KEY (`task_id`) REFERENCES `task` (`id`)
);