-- Миграция: детальный дневник и питание
-- Выполните: mysql -u root ldo < db/migrations/003_diary_food.sql

USE ldo;

-- Расширение workout_logs для детального описания
ALTER TABLE workout_logs ADD COLUMN duration_min INT UNSIGNED NULL AFTER notes;
ALTER TABLE workout_logs ADD COLUMN feeling ENUM('excellent','good','normal','tired','exhausted') NULL AFTER duration_min;
ALTER TABLE workout_logs ADD COLUMN body_weight DECIMAL(5,2) NULL AFTER feeling;

-- Продукты питания
CREATE TABLE IF NOT EXISTS food_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  calories_per_100g DECIMAL(6,2) NOT NULL,
  protein_per_100g DECIMAL(6,2) NOT NULL DEFAULT 0,
  fat_per_100g DECIMAL(6,2) NOT NULL DEFAULT 0,
  carbs_per_100g DECIMAL(6,2) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL,
  INDEX idx_name (name(191))
) ENGINE=InnoDB;

-- Приёмы пищи в дневнике
CREATE TABLE IF NOT EXISTS meal_logs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  log_id INT UNSIGNED NOT NULL,
  meal_type ENUM('breakfast','lunch','dinner','snack') NOT NULL DEFAULT 'snack',
  food_item_id INT UNSIGNED NULL,
  food_name VARCHAR(255) NULL,
  amount_g DECIMAL(6,2) NOT NULL,
  calories DECIMAL(6,2) NOT NULL,
  protein DECIMAL(6,2) NOT NULL DEFAULT 0,
  fat DECIMAL(6,2) NOT NULL DEFAULT 0,
  carbs DECIMAL(6,2) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL,
  FOREIGN KEY (log_id) REFERENCES workout_logs(id) ON DELETE CASCADE,
  FOREIGN KEY (food_item_id) REFERENCES food_items(id) ON DELETE SET NULL,
  INDEX idx_log (log_id),
  INDEX idx_meal_type (meal_type)
) ENGINE=InnoDB;

-- Добавляем популярные продукты
INSERT INTO food_items (name, calories_per_100g, protein_per_100g, fat_per_100g, carbs_per_100g, created_at) VALUES
('Куриная грудка', 165, 31, 3.6, 0, NOW()),
('Рис варёный', 130, 2.7, 0.3, 28, NOW()),
('Гречка варёная', 101, 4.2, 1.1, 20, NOW()),
('Овсянка', 389, 16.9, 6.9, 66, NOW()),
('Яйцо куриное', 155, 13, 11, 1.1, NOW()),
('Творог 5%', 121, 16, 5, 3, NOW()),
('Банан', 89, 1.1, 0.3, 23, NOW()),
('Яблоко', 52, 0.3, 0.2, 14, NOW()),
('Орехи грецкие', 654, 15.2, 65.2, 7, NOW()),
('Молоко 2.5%', 52, 2.8, 2.5, 4.7, NOW()),
('Хлеб белый', 265, 9, 3.2, 49, NOW()),
('Макароны варёные', 131, 5, 1.1, 25, NOW()),
('Лосось', 208, 20, 13, 0, NOW()),
('Авокадо', 160, 2, 15, 9, NOW()),
('Брокколи', 34, 2.8, 0.4, 7, NOW());
