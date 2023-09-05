

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `user_company` (
  `id` char(36) NOT NULL,
  `userId` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `inn` varchar(12) NOT NULL,
  `kpp` varchar(32) NOT NULL,
  `rs` varchar(32) NOT NULL,
  `bik` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE `user_company`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`);
COMMIT;