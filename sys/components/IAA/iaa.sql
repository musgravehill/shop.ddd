 

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

 

CREATE TABLE `authentication_access_recovery_token` (
  `id` int(10) UNSIGNED NOT NULL,
  `identityId` char(36) NOT NULL,
  `token` char(36) NOT NULL,
  `createdAt` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

 
ALTER TABLE `authentication_access_recovery_token`
  ADD PRIMARY KEY (`id`),
  ADD KEY `identityId` (`identityId`),
  ADD KEY `token` (`token`),
  ADD KEY `createdAt` (`createdAt`);

 
ALTER TABLE `authentication_access_recovery_token`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;


 
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

 

CREATE TABLE `authentication_identity` (
  `id` char(36) NOT NULL,
  `userId` char(36) NOT NULL,
  `email` varchar(32) NOT NULL,
  `passwordHash` char(64) NOT NULL,
  `role` tinyint(2) UNSIGNED NOT NULL,
  `violationCount` int(10) UNSIGNED NOT NULL,
  `bannedUntil` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

 
 
ALTER TABLE `authentication_identity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `userId` (`userId`);
COMMIT;
