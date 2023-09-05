
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `supplier` (
  `id` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `dsc` text NOT NULL,
  `imgUrl` varchar(255) NOT NULL,
  `taskDownloadAt` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `supplier` (`id`, `name`, `dsc`, `imgUrl`, `taskDownloadAt`) VALUES
('018872a0-64e9-724d-961f-8dbbc9530094', 'SEO', 'SEO', 'http://beznalom.com.ru/imgsys/01887d79-fd72-7220-8436-358ba4e224b3.jpg', 1690893486),
('0188f85c-a3ff-72ed-bcc7-2e1d8cffc836', 'DSSL', 'dsc', 'http://beznalom.com.ru/imgsys/01887d79-fd73-706c-af5a-0ec18dd18a79.jpg', 1690914601),
('0189b114-7629-72b3-98be-c03b37dd89c2', 'СпецДилер', 'Компания СпецДилер основана в 2006 году. СпецДилер - успешная, динамично развивающаяся торговая и IT-компания, продвигающая комплексные решения и новейшие технические разработки в различных профессиональных отраслях.Работу с клиентами мы строим на основе принципа партнёрского бизнеса, согласно которому наше деловое сотрудничество не ограничивается только поставками продукции и комплектующих изделий. Мы ставим себе цель оперативно решать часть проблем наших партнёров, понимая, что успешное продвижение их бизнеса становится нашим общим успехом.Торговый Дом СпецДилер неустанно поддерживает высокий уровень сервиса, имеет гибкую политику ценообразования, методично расширяет склад, географию закупки товаров и поставок, совершенствует структуру сайта, ведёт деловой диалог с контрагентами, стараясь найти оптимальный способ взаимодействия.', 'http://beznalom.com.ru/imgsys/01887d79-fd72-7220-8436-358ba4e224b3.jpg', 1690893489);


ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `taskDownloadAt` (`taskDownloadAt`);

  ALTER TABLE `supplier` ADD `cityName` VARCHAR(512) NOT NULL DEFAULT '';


COMMIT;