SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `user` (
  `id` char(36) NOT NULL,
  `username` varchar(128) NOT NULL,
  `email` varchar(32) NOT NULL,
  `phone` char(10) NOT NULL,
  `createdAt` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `user` ADD `cityName` VARCHAR(512) NOT NULL DEFAULT '' AFTER `createdAt`, ADD `address` VARCHAR(512) NOT NULL DEFAULT '' AFTER `cityName`;

COMMIT;