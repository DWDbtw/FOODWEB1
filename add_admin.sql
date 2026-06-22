-- Добавление администратора
INSERT INTO users (username, email, full_name, password, role) 
VALUES ('admin', 'admin@mail.com', 'Admin User', encode(sha256('admin123'::bytea), 'hex'), 'admin')
ON CONFLICT (username) DO UPDATE SET 
    email = EXCLUDED.email,
    full_name = EXCLUDED.full_name,
    password = EXCLUDED.password,
    role = EXCLUDED.role;

-- Проверяем
SELECT * FROM users WHERE username = 'admin';
