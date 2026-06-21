-- PostgreSQL schema for restaurant_website
-- Converted from MySQL/MariaDB dump

SET client_encoding = 'UTF8';

-- user_addresses
CREATE TABLE IF NOT EXISTS user_addresses (
    address_id SERIAL PRIMARY KEY,
    user_id    INTEGER NOT NULL,
    label      VARCHAR(100) DEFAULT NULL,
    address    VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- clients
CREATE TABLE IF NOT EXISTS clients (
    client_id    SERIAL PRIMARY KEY,
    client_name  VARCHAR(50)  NOT NULL,
    client_phone VARCHAR(50)  NOT NULL,
    client_email VARCHAR(100) NOT NULL,
    user_id      INTEGER DEFAULT NULL
);

-- image_gallery
CREATE TABLE IF NOT EXISTS image_gallery (
    image_id   SERIAL PRIMARY KEY,
    image_name VARCHAR(30)  NOT NULL,
    image      VARCHAR(255) NOT NULL
);

-- menu_categories
CREATE TABLE IF NOT EXISTS menu_categories (
    category_id   SERIAL PRIMARY KEY,
    category_name VARCHAR(50) NOT NULL
);

-- menus
CREATE TABLE IF NOT EXISTS menus (
    menu_id          SERIAL PRIMARY KEY,
    menu_name        VARCHAR(100)   NOT NULL,
    menu_description VARCHAR(255)   NOT NULL,
    menu_price       DECIMAL(6,2)   NOT NULL,
    menu_image       VARCHAR(255)   NOT NULL,
    category_id      INTEGER        NOT NULL,
    CONSTRAINT fk_menu_category FOREIGN KEY (category_id) REFERENCES menu_categories(category_id)
);

-- users
CREATE TABLE IF NOT EXISTS users (
    user_id      SERIAL PRIMARY KEY,
    username     VARCHAR(20)  NOT NULL UNIQUE,
    email        VARCHAR(30)  NOT NULL UNIQUE,
    full_name    VARCHAR(50)  NOT NULL,
    first_name   VARCHAR(50)  DEFAULT NULL,
    last_name    VARCHAR(50)  DEFAULT NULL,
    phone        VARCHAR(30)  DEFAULT NULL,
    dob          DATE         DEFAULT NULL,
    password     VARCHAR(100) NOT NULL,
    bonus_points DECIMAL(10,2) NOT NULL DEFAULT 0,
    role         VARCHAR(20)  NOT NULL DEFAULT 'client'
);

-- placed_orders
CREATE TABLE IF NOT EXISTS placed_orders (
    order_id            SERIAL PRIMARY KEY,
    order_time          TIMESTAMP    NOT NULL,
    client_id           INTEGER      NOT NULL,
    user_id             INTEGER      DEFAULT NULL,
    delivery_address    VARCHAR(255) NOT NULL,
    bonuses_earned      DECIMAL(10,2) NOT NULL DEFAULT 0,
    bonuses_spent       DECIMAL(10,2) NOT NULL DEFAULT 0,
    discount_amount     DECIMAL(10,2) NOT NULL DEFAULT 0,
    delivered           SMALLINT     NOT NULL DEFAULT 0,
    canceled            SMALLINT     NOT NULL DEFAULT 0,
    cancellation_reason VARCHAR(255) DEFAULT NULL,
    CONSTRAINT fk_order_client FOREIGN KEY (client_id) REFERENCES clients(client_id),
    CONSTRAINT fk_orders_user  FOREIGN KEY (user_id)   REFERENCES users(user_id)
);

-- in_order
CREATE TABLE IF NOT EXISTS in_order (
    id       SERIAL PRIMARY KEY,
    order_id INTEGER NOT NULL,
    menu_id  INTEGER NOT NULL,
    quantity INTEGER NOT NULL DEFAULT 1,
    CONSTRAINT fk_in_order_menu  FOREIGN KEY (menu_id)  REFERENCES menus(menu_id),
    CONSTRAINT fk_in_order_order FOREIGN KEY (order_id) REFERENCES placed_orders(order_id)
);

-- reservations
CREATE TABLE IF NOT EXISTS reservations (
    reservation_id      SERIAL PRIMARY KEY,
    date_created        TIMESTAMP NOT NULL,
    client_id           INTEGER   NOT NULL,
    selected_time       TIMESTAMP NOT NULL,
    nbr_guests          INTEGER   NOT NULL,
    table_id            INTEGER   NOT NULL,
    liberated           SMALLINT  NOT NULL DEFAULT 0,
    canceled            SMALLINT  NOT NULL DEFAULT 0,
    cancellation_reason VARCHAR(255) DEFAULT NULL
);

-- tables
CREATE TABLE IF NOT EXISTS tables (
    table_id SERIAL PRIMARY KEY
);

-- website_settings
CREATE TABLE IF NOT EXISTS website_settings (
    option_id    SERIAL PRIMARY KEY,
    option_name  VARCHAR(255) NOT NULL,
    option_value VARCHAR(255) NOT NULL
);

-- roles
CREATE TABLE IF NOT EXISTS roles (
    role_id     SERIAL PRIMARY KEY,
    role_key    VARCHAR(50)  NOT NULL UNIQUE,
    role_name   VARCHAR(100) NOT NULL,
    description TEXT         DEFAULT NULL
);

-- ===================== SEED DATA =====================

INSERT INTO image_gallery (image_name, image) VALUES
('Суши',    'sushi.jpg'),
('Роллы',   'rolls.jpg'),
('Интерьер','interior.jpg'),
('Суши',    'sushi2.jpg'),
('Роллы',   'rolls2.jpg')
ON CONFLICT DO NOTHING;

INSERT INTO menu_categories (category_name) VALUES
('Суши'),('Роллы'),('Напитки'),('Десерты')
ON CONFLICT DO NOTHING;

INSERT INTO menus (menu_name, menu_description, menu_price, menu_image, category_id) VALUES
('Филадельфия',        'Классические калифорнийские роллы с лососем и крабовым мясом', 450.00, 'philadelphia.jpg',    2),
('Циппuri Маки',       'Маки с креветкой и огурцом',                                   380.00, 'california.jpg',      2),
('Суши Сет',           'Ассорти суши: лосось, тунец, краб',                             550.00, 'sushi_set.jpg',       1),
('Грин Чай',           'Освежающий зеленый чай',                                        150.00, 'greentea.jpg',        3),
('Кофе',               'Кофе латте',                                                    180.00, 'coffee.jpg',          3),
('Морс',               'Классический яблочный морс',                                   120.00, 'mors.jpg',            3),
('Мидии в соевом соусе','Десерт из морепродуктов',                                     280.00, 'mussels.jpg',         4),
('Манго Мачи',         'Сладкие роллы с манго',                                         320.00, 'mango.jpg',           2),
('Калифорния',         'Роллы с лососем, авокадо и икрой',                              420.00, 'california_roll.jpg', 2),
('Каппа Маки',         'Маки с огурцом в нори',                                         220.00, 'kappa_maki.jpg',      2),
('Дракон Маки',        'Роллы с угрем, авокадо и соусом унаги',                         520.00, 'dragon_maki.jpg',     2),
('Тунец Маки',         'Роллы с тунцом и зеленым луком',                                480.00, 'tuna_maki.jpg',       2)
ON CONFLICT DO NOTHING;

-- password = sha1('admin123') for admin_user
INSERT INTO users (username, email, full_name, password, role) VALUES
('admin_user',  'user_admin@gmail.com',  'User Admin',      'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'admin'),
('client_user', 'client@example.com',    'Client User',     '3ebf4fc6e7f46c41d56fc3e499b0ec38a51cece3', 'client'),
('manager_user','manager@example.com',   'Manager User',    '2bd516dbb01d6d82a3d11c6ad77b3e8b3af4ee35', 'manager'),
('admin2',      'admin2@example.com',    'Administrator',   '0d107d09f5bbe40cade3de5c71e9e9b7', 'admin')
ON CONFLICT DO NOTHING;

INSERT INTO clients (client_name, client_phone, client_email) VALUES
('Clinet 1',   '02020202020',   'client1@gmail.com'),
('Client 10',  '0638383933',    'client10@gmail.com'),
('Client 11',  '06242556272',   'client11@yahoo.fr'),
('Client 12',  '030303030202',  'client1133@gmail.com'),
('Client 12',  '030303030',     'client14@gmail.com'),
('Client 14',  '0203203203',    'client14@gmail.com'),
('Client 17',  '0737373822',    'client17@gmail.com'),
('Client 12',  '02920320',      'client12@yahoo.fr'),
('Test',        '1034304300',   'test@gmail.com')
ON CONFLICT DO NOTHING;

INSERT INTO tables (table_id) VALUES (1) ON CONFLICT DO NOTHING;

INSERT INTO website_settings (option_name, option_value) VALUES
('restaurant_name',        'VINCENT SUSHI'),
('restaurant_email',       'mikiyt94@gmail.com'),
('admin_email',            'admin_email@gmail.com'),
('restaurant_phonenumber', '88866777555'),
('restaurant_address',     'Москва, ул. Сушинарочная, д.1')
ON CONFLICT DO NOTHING;

INSERT INTO roles (role_key, role_name, description) VALUES
('admin',   'Администратор', 'Полный доступ: управление сайтом, пользователями и меню.'),
('manager', 'Менеджер',      'Управление меню, обработка заказов, изменение изображений блюд.'),
('client',  'Клиент',        'Обычный клиент сайта.')
ON CONFLICT DO NOTHING;

-- Foreign key from clients to users (after both tables exist)
ALTER TABLE clients
    DROP CONSTRAINT IF EXISTS fk_clients_user;
ALTER TABLE clients
    ADD CONSTRAINT fk_clients_user FOREIGN KEY (user_id) REFERENCES users(user_id);
