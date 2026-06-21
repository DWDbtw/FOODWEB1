-- PostgreSQL migration: create roles table
CREATE TABLE IF NOT EXISTS roles (
    role_id     SERIAL PRIMARY KEY,
    role_key    VARCHAR(50)  NOT NULL UNIQUE,
    role_name   VARCHAR(100) NOT NULL,
    description TEXT         DEFAULT NULL
);

INSERT INTO roles (role_key, role_name, description) VALUES
('admin',   'Администратор', 'Полный доступ: управление сайтом, пользователями и меню.'),
('manager', 'Менеджер',      'Управление меню, обработка заказов, изменение изображений блюд.'),
('client',  'Клиент',        'Обычный клиент сайта.')
ON CONFLICT DO NOTHING;
