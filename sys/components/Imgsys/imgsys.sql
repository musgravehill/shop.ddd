SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `img_sys` (
  `id` char(36) NOT NULL,
  `tags` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `img_sys` (`id`, `tags`) VALUES
('01887d79-fd0c-71e1-bae6-368aa81e9503', ''),
('01887d79-fd11-72bb-b76a-2eeb0154558f', 'AccordTec Блоки питания'),
('01887d79-fd66-706f-8fff-4ec8462a5431', 'logo AVITO Авито'),
('01887d79-fd67-7355-b32d-a43d7605ca36', 'logo BIGLION Биглион'),
('01887d79-fd73-706c-af5a-0ec18dd18a79', 'logo DSSL 600x450'),
('01887d79-fd51-715a-a54d-f04e77cde4d9', 'logo Mail Ru'),
('01887d79-fd6b-7253-8118-f395f5282171', 'logo METRO Cash Carry'),
('01887d79-fd5e-71f5-9ef0-9716fa7cc469', 'logo MIRAX GROUP'),
('01887d79-fd53-703d-a0dd-40d85f43d938', 'logo Renault'),
('01887d79-fd4d-73b4-9338-24c0724adcff', 'logo Авиакомпания Сибирь'),
('01887d79-fd6a-709d-9685-b4a4e55a1dc8', 'logo Автотрейдинг'),
('01887d79-fd55-7141-96a9-9f39a56e183b', 'logo Альфа Капитал'),
('01887d79-fd4c-73d0-a9c1-20f4c4900003', 'logo Арбитражный суд Московской области'),
('01887d79-fd56-70a7-a18f-02e0a770e4a3', 'logo Банк Возрождение'),
('01887d79-fd55-7141-96a9-9f39a5ecf627', 'logo Банк ВТБ24'),
('01887d79-fd6e-73af-8e00-e92d4a8f650a', 'logo Банк Русский Стандарт'),
('01887d79-fd71-72d9-87f0-35ec61724cdc', 'logo Безналом 320х320'),
('01887d79-fd72-7220-8436-358ba57f31b2', 'logo Безналом торговый знак 600х450'),
('01887d79-fd39-7075-a076-786764b555a7', 'logo Верховный Суд'),
('01887d79-fd4f-724a-9e6a-fa47c5af6b1a', 'logo ВИППОРТ'),
('01887d79-fd45-7218-983b-42e9171973b4', 'logo Газпром нефть'),
('01887d79-fd48-7058-b053-622d5a4aa54d', 'logo Герб Москва'),
('01887d79-fd68-723a-9b0f-12e73272a6b3', 'logo Гольфстрим охранные системы'),
('01887d79-fd40-7136-858b-332fa1af44ba', 'logo Госавтоинспекция МВД России'),
('01887d79-fd6c-733c-8bce-ef91db103c02', 'logo Госкорпорация Росатом'),
('01887d79-fd5b-73cc-a7d0-eef8858b98c7', 'logo Государственный Кремлевский дворец'),
('01887d79-fd48-7058-b053-622d5a99cd3f', 'logo Департамент имущества города Москвы'),
('01887d79-fd5f-7130-a691-91c3c2a4e6ad', 'logo ДОН Строй'),
('01887d79-fd59-725b-9ff0-c088aef2ba36', 'logo Евросеть'),
('01887d79-fd61-7037-8b82-a35f1a778f85', 'logo Зарубежнефть'),
('01887d79-fd5c-7128-b563-add38a9f009c', 'logo ИКЕА IKEA'),
('01887d79-fd44-7106-a4bd-6289783dd4a7', 'logo Институт космических исследований Российской академии наук ИКИ РАН'),
('01887d79-fd5a-739a-ae82-1c22ebab8b83', 'logo Киноконцерн Мосфильм'),
('01887d79-fd61-7037-8b82-a35f1b090c52', 'logo Компания Сухой'),
('01887d79-fd54-717c-909c-348b1e0517a8', 'logo Консорциум Альфа Групп'),
('01887d79-fd43-722a-b10f-8687477db020', 'logo Космического приборостроения'),
('01887d79-fd63-7190-9482-707fe883be1d', 'logo Лианозовский колбасный завод'),
('01887d79-fd65-72d3-8aa4-c2556b2b857c', 'logo Люксор Синемакс'),
('01887d79-fd50-70a3-af9c-69c78ecdb930', 'logo МГТУ Баумана'),
('01887d79-fd4e-71c7-a6f4-f02746f59e9e', 'logo Международный аэропорт Внуково'),
('01887d79-fd3c-7001-bcdf-7b1c5aaf7b65', 'logo Министерство внутренних дел МВД'),
('01887d79-fd3b-72b1-b04e-ff683858266c', 'logo Министерство обороны'),
('01887d79-fd4b-7118-a164-635bc54b34e3', 'logo Мосводоканал'),
('01887d79-fd49-73d6-92bc-20e0a79173e0', 'logo Мосгосэкспертиза'),
('01887d79-fd3e-72b9-97ba-38f9e34078f9', 'logo Московская типография Гознака'),
('01887d79-fd4d-73b4-9338-24c0726edda1', 'logo Московский аэропорт Домодедово'),
('01887d79-fd4a-73d5-b713-427adca4c971', 'logo Мосэнергосбыт'),
('01887d79-fd57-72c3-9e31-3993ccab1e21', 'logo МТС'),
('01887d79-fd41-709b-a392-96795be45f6d', 'logo МЧС'),
('01887d79-fd45-7218-983b-42e917452a6e', 'logo Норильскгазпром'),
('01887d79-fd5c-7128-b563-add38abc5f99', 'logo Норильский никель'),
('01887d79-fd66-706f-8fff-4ec8453dcb9e', 'logo Оскольский металлургический комбинат'),
('01887d79-fd39-7075-a076-7867653470f7', 'logo Посольство США'),
('01887d79-fd4b-7118-a164-635bc60e5568', 'logo Почта России'),
('01887d79-fd37-728b-9977-03bf02c1bb03', 'logo Правительство Российской Федерации'),
('01887d79-fd51-715a-a54d-f04e7848e771', 'logo Рамблер Rambler'),
('01887d79-fd6f-70b7-9d86-57790ea53dbb', 'logo РЕСО Гарантия'),
('01887d79-fd69-713b-b561-b9d9013ca763', 'logo РОССИ'),
('01887d79-fd41-709b-a392-96795bf4fd14', 'logo Российские железные дороги РЖД'),
('01887d79-fd42-7098-af78-7053b0139883', 'logo Российские космические системы'),
('01887d79-fd6e-73af-8e00-e92d4a1c5b7a', 'logo Ростелеком'),
('01887d79-fd64-72d5-bec4-6035a7ae3cdf', 'logo РотФронт'),
('01887d79-fd68-723a-9b0f-12e7326f158c', 'logo Сантехкомплект'),
('01887d79-fd3e-72b9-97ba-38f9e3b4a9c8', 'logo Сбербанк России'),
('01887d79-fd58-7146-8cb1-8f9b59ed492c', 'logo Связной'),
('01887d79-fd52-71ed-a4d6-171bfb019520', 'logo Сименс Siemens'),
('01887d79-fd70-710f-8a33-86a099e36891', 'logo Сколково'),
('01887d79-fd3c-7001-bcdf-7b1c5ab24ed6', 'logo Следственный комитет Российской Федерации'),
('01887d79-fd72-7220-8436-358ba4e224b3', 'logo СпецДилер 200х200'),
('01887d79-fd6b-7253-8118-f395f4f914d2', 'logo СПСР Экспресс Сервис'),
('01887d79-fd70-710f-8a33-86a0998a3c4d', 'logo Страховая Компания Согласие'),
('01887d79-fd6d-72eb-9640-eb3f83756b94', 'logo СУ 155'),
('01887d79-fd59-725b-9ff0-c088afeca6d9', 'logo Телевизионный технический центр Останкино'),
('01887d79-fd60-70c0-a526-207e4a425b86', 'logo Транснефть Финанс'),
('01887d79-fd5f-7130-a691-91c3c3746f74', 'logo Туланефтепродукт'),
('01887d79-fd37-728b-9977-03bf0382d91d', 'logo Управление по делам президента Российской Федерации'),
('01887d79-fd47-71f6-a137-0171ca7cf87e', 'logo Управление федерального казначейства'),
('01887d79-fd3d-706c-b8bc-12d386e370da', 'logo ФГУП УСС ФСБ России'),
('01887d79-fd38-701b-9097-6fa1a59259ba', 'logo Федеральная налоговая служба'),
('01887d79-fd46-73f2-84b9-39fe098a7b45', 'logo Федеральная служба по надзору в сфере защиты прав'),
('01887d79-fd57-72c3-9e31-3993cc79abda', 'logo ФИНАМ'),
('01887d79-fd62-714b-a41f-ac90c4cfdfd3', 'logo Хоккейный клуб Динамо'),
('01887d79-fd5d-7009-bf99-39898018aa7a', 'logo Центр Международной Торговли'),
('01887d79-fd3a-703b-98ee-2e16d5736558', 'logo Центральный банк'),
('01887d79-fd63-7190-9482-707fe844a10f', 'logo Экоспас'),
('01887d79-fd43-722a-b10f-868748770216', 'logo ЭХО Федерального космического агентства'),
('01887d79-fd79-7125-952e-43429143e585', 'logo Яндекс'),
('01887d79-fd10-715d-8168-218158c0996d', 'vscode docker ide'),
('01887d79-fd75-73a1-923a-05393b6037b5', 'Бонусы скидка'),
('01887d79-fd14-713c-bb3c-549df967f359', 'Гарантия'),
('01887d79-fd77-736b-a207-226da99b3929', 'Гарантия'),
('01887d79-fd15-72bf-af32-3a714edd7c56', 'Доставка по России'),
('01887d79-fd36-732d-b6d9-62fab189c6e0', 'Доставка по России'),
('01887d79-fd16-7194-80a7-778f879de96f', 'иконка Внимание восклицательный знак 16х16'),
('01887d79-fd1a-7353-8e20-01b88bcf68c6', 'иконка процент скидка распродажа 32х32'),
('01887d79-fd13-729a-87c2-1d0418b85317', 'Категория Карты ключи кнопки брелки'),
('01887d79-fd13-729a-87c2-1d041848c212', 'Категория Расходные материалы'),
('01887d79-fd12-7354-9193-31eaf7f39be6', 'Категория Электромагнитный Замок'),
('01887d79-fd74-7258-b51f-b3b5de1b77ad', 'Контакты СпецДилер'),
('01887d79-fd76-7201-93a7-8444b760caa7', 'Наши клиенты'),
('01887d79-fd7a-72f9-af6f-f9482260c216', 'Новость HiWatch IPT T012 G2 S Двухспектральная IP камера'),
('01887d79-fd18-718b-bfdc-60c3cc1a3fad', 'Новость бренд CAME'),
('01887d79-fd17-73f8-9ea7-081fe5fe0038', 'Новость Вызывная панель XVP'),
('01887d79-fd17-73f8-9ea7-081fe643f2c5', 'Новость Плата блока управления Came ZL39B'),
('01887d79-fd19-723d-93ab-6457ba0940df', 'Новость Пульт CAME TTS'),
('01887d79-fd79-7125-952e-4342918726d9', 'Новость шлагбаум PERCo GS04 со складной стрелой'),
('01887d79-fd78-711d-a676-8dff5c7c2855', 'Политика конфиденциальности персональных данных'),
('01887d79-fd75-73a1-923a-05393a61ef6f', 'Публичный Договор оферта'),
('01887d79-fd1a-7353-8e20-01b88be48cba', 'РАСПРОДАЖА'),
('01887d79-fd26-72fc-b98e-57c2651cbd65', 'Сертификат Acti'),
('01887d79-fd1e-7199-8832-e80778708eed', 'Сертификат BEWARD'),
('01887d79-fd2b-73cb-be19-f4f65858a3fc', 'Сертификат Came Каме Рус'),
('01887d79-fd1b-7182-928e-77686134fda4', 'Сертификат Came УМС Рус'),
('01887d79-fd27-73eb-a98c-3b0f0dc6279a', 'Сертификат Came УМС Рус'),
('01887d79-fd1c-726d-8a5f-8a56535e2eb9', 'Сертификат DAHUA Technology'),
('01887d79-fd23-7261-a984-3e354e634335', 'Сертификат DSSL Trassir'),
('01887d79-fd21-7210-8c16-e6a0ddfbfcc8', 'Сертификат GENIUS'),
('01887d79-fd23-7261-a984-3e354debd243', 'Сертификат HIKVISION'),
('01887d79-fd2c-7070-a5dc-002839bfd8a1', 'Сертификат ISS'),
('01887d79-fd29-7198-87f3-aaef93880487', 'Сертификат ITV'),
('01887d79-fd2a-7065-ba05-c7bc8eb1d209', 'Сертификат ITV'),
('01887d79-fd1e-7199-8832-e8077863fcad', 'Сертификат KOMKOM'),
('01887d79-fd29-7198-87f3-aaef94206d5d', 'Сертификат Komkom'),
('01887d79-fd1d-739b-a0f3-817777f57275', 'Сертификат VIDSTAR'),
('01887d79-fd20-73cb-99d7-0db00665c196', 'Сертификат VISION'),
('01887d79-fd1f-7252-8338-7d2e3ff3893a', 'Сертификат В1 электроникс'),
('01887d79-fd25-71bd-a7b2-74379e17496c', 'Сертификат Возрождение Praktika'),
('01887d79-fd24-738f-a6ea-824c08319660', 'Сертификат Инфотех'),
('01887d79-fd22-72ae-82ad-1253705699d3', 'Сертификат Кронверк Реверс'),
('01887d79-fd33-73c4-af54-05f53f029272', 'Сертификат личный Acti'),
('01887d79-fd2f-7214-93d7-f0de030b4827', 'Сертификат личный BasIP'),
('01887d79-fd2d-711e-ba32-bc5d0f7ea874', 'Сертификат личный Came'),
('01887d79-fd2e-7037-abd8-0a310f5764f1', 'Сертификат личный Came'),
('01887d79-fd2f-7214-93d7-f0de03cf709a', 'Сертификат личный Came'),
('01887d79-fd30-7264-87ae-d046ae0a6877', 'Сертификат личный Came'),
('01887d79-fd34-7142-bbf0-bdbf6d774814', 'Сертификат личный Came'),
('01887d79-fd35-7306-93df-6880d609e8c6', 'Сертификат личный Came'),
('01887d79-fd33-73c4-af54-05f53eb5bc31', 'Сертификат личный Gate'),
('01887d79-fd32-73ae-b497-17eb408350f1', 'Сертификат личный Nice'),
('01887d79-fd31-7384-8728-2a8c495fa53b', 'Сертификат личный Parsec'),
('01887d79-fd28-707a-96de-26522e9c85b0', 'Сертификат Ростов Дон РостЕвроСтрой'),
('01887d79-fd77-736b-a207-226da9fa0a7d', 'Требования чекбоксы'),
('01887d79-fd0e-7301-87ef-83b98ed31a89', 'фото person');


ALTER TABLE `img_sys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tags` (`tags`);
COMMIT;
