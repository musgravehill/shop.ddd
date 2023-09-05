

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `sale_personal_brand_category` (
  `id` int(10) UNSIGNED NOT NULL,
  `userId` char(36) NOT NULL,
  `brandId` int(10) UNSIGNED NOT NULL,
  `brandCategoryId` int(10) UNSIGNED NOT NULL,
  `salePercent` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE `sale_personal_brand_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`),
  ADD KEY `brandId` (`brandId`),
  ADD KEY `brandCategoryId` (`brandCategoryId`);


ALTER TABLE `sale_personal_brand_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;
