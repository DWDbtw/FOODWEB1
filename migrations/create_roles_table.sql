-- Migration: create roles table and seed three roles
CREATE TABLE IF NOT EXISTS `roles` (
  `role_id` int(5) NOT NULL AUTO_INCREMENT,
  `role_key` varchar(50) NOT NULL UNIQUE,
  `role_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Seed roles
INSERT IGNORE INTO `roles` (`role_key`, `role_name`, `description`) VALUES
('admin', 'Администратор', 'Полный доступ: управление сайтом, пользователями и меню.'),
('manager', 'Менеджер', 'Управление меню, обработка заказов, изменение изображений блюд.'),
('client', 'Клиент', 'Обычный клиент сайта.');
