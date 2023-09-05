

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `product` (
  `id` int(10) UNSIGNED NOT NULL, 
  `ufu` char(128) NOT NULL,
  `externalId` char(64) NOT NULL,
  `supplierId` char(36) NOT NULL,
  `brandId` int(10) UNSIGNED DEFAULT NULL,
  `brandCategoryId` int(10) UNSIGNED DEFAULT NULL,
  `pricePurchase` BIGINT UNSIGNED NOT NULL,
  `priceSelling` BIGINT UNSIGNED NOT NULL,
  `sku` varchar(64) NOT NULL,
  `name` varchar(255) NOT NULL,
  `dsc` text NOT NULL,
  `quantityAvailable` int(10) UNSIGNED NOT NULL,
  `createdAt` int(10) UNSIGNED NOT NULL,
  `updatedAt` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ufu` (`ufu`),
  ADD KEY `externalId` (`externalId`),
  ADD KEY `supplierId` (`supplierId`),
  ADD KEY `brandId` (`brandId`),
  ADD KEY `brandCategoryId` (`brandCategoryId`);

ALTER TABLE `product`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `product` ADD `viewIdx` BIGINT UNSIGNED NOT NULL AFTER `updatedAt`, ADD INDEX (`viewIdx`);

COMMIT;
