SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `product_img` (
  `id` char(36) NOT NULL,
  `productId` char(36) NOT NULL,
  `externalUrl` varchar(255) NOT NULL,
  `externalUrlHash` char(32) NOT NULL,
  `taskDownloadAt` int(10) UNSIGNED NOT NULL,
  `taskDownloadFailCount` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE `product_img`
  ADD PRIMARY KEY (`id`),
  ADD KEY `productId` (`productId`),
  ADD KEY `externalUrlHash` (`externalUrlHash`),
  ADD KEY `taskDownloadAt` (`taskDownloadAt`);
COMMIT;