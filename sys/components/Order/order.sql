

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `order_items` (
  `id` char(36) NOT NULL,
  `orderId` char(36) NOT NULL,
  `productId` char(36) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `priceInitialFractional` BIGINT UNSIGNED NOT NULL,
  `priceFinalFractional` BIGINT UNSIGNED NOT NULL,
  `appliedSaleTypeIds` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `order_list` (
  `id` char(36) NOT NULL,
  `userFriendlyOrderId` varchar(32) NOT NULL,
  `userId` char(36) NOT NULL,
  `deliveryTypeId` int(10) UNSIGNED NOT NULL,
  `cityName` varchar(512) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `createdAt` int(10) UNSIGNED NOT NULL,
  `priceTotalFractionalCount` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `order_ufid` (
  `ufid` int(10) UNSIGNED NOT NULL,
  `createdAt` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orderId` (`orderId`);

ALTER TABLE `order_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`),
  ADD KEY `userFriendlyOrderId` (`userFriendlyOrderId`);

ALTER TABLE `order_ufid`
  ADD PRIMARY KEY (`ufid`);


ALTER TABLE `order_ufid`
  MODIFY `ufid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;


COMMIT;
