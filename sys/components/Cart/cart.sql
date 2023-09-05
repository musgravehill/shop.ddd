

CREATE TABLE `cart` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userId` char(36) NOT NULL,
  `productId` char(36) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,

PRIMARY KEY (`id`),
KEY `userId` (`userId`),
KEY `productId` (`productId`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; 

