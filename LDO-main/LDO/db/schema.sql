-- LDO Database Schema
-- Charset: utf8mb4

CREATE DATABASE IF NOT EXISTS ldo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ldo;

-- Пользователи
CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','user') NOT NULL DEFAULT 'user',
  is_blocked TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL,
  INDEX idx_email (email),
  INDEX idx_role (role)
) ENGINE=InnoDB;

-- Восстановление пароля
CREATE TABLE IF NOT EXISTS password_resets (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  token_hash VARCHAR(64) NOT NULL,
  expires_at DATETIME NOT NULL,
  created_at DATETIME NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_token (token_hash(32)),
  INDEX idx_expires (expires_at)
) ENGINE=InnoDB;

-- Профили пользователей
CREATE TABLE IF NOT EXISTS user_profiles (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL UNIQUE,
  height_cm INT UNSIGNED NULL,
  weight_kg DECIMAL(5,2) NULL,
  age INT UNSIGNED NULL,
  gender ENUM('male','female') NULL,
  activity_level ENUM('sedentary','light','moderate','active','very') NULL DEFAULT 'moderate',
  goal ENUM('maintain','lose','gain') NULL DEFAULT 'maintain',
  avatar_url VARCHAR(500) NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Лог веса (для динамики)
CREATE TABLE IF NOT EXISTS weight_logs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  weight_kg DECIMAL(5,2) NOT NULL,
  logged_at DATE NOT NULL,
  created_at DATETIME NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  UNIQUE KEY unique_user_date (user_id, logged_at),
  INDEX idx_user_date (user_id, logged_at DESC)
) ENGINE=InnoDB;

-- Категории упражнений
CREATE TABLE IF NOT EXISTS exercise_categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  sort_order INT NOT NULL DEFAULT 0
) ENGINE=InnoDB;

-- Упражнения
CREATE TABLE IF NOT EXISTS exercises (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id INT UNSIGNED NULL,
  name VARCHAR(255) NOT NULL,
  description TEXT NULL,
  technique TEXT NULL,
  image_url VARCHAR(500) NULL,
  video_url VARCHAR(500) NULL,
  created_at DATETIME NOT NULL,
  FOREIGN KEY (category_id) REFERENCES exercise_categories(id) ON DELETE SET NULL,
  INDEX idx_category (category_id),
  FULLTEXT idx_search (name, description)
) ENGINE=InnoDB;

-- Тренировочные программы
CREATE TABLE IF NOT EXISTS workout_programs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  level ENUM('beginner','intermediate','advanced','all') NOT NULL DEFAULT 'all',
  description TEXT NULL,
  created_at DATETIME NOT NULL,
  INDEX idx_level (level)
) ENGINE=InnoDB;

-- Дни программы
CREATE TABLE IF NOT EXISTS program_days (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  program_id INT UNSIGNED NOT NULL,
  day_number INT UNSIGNED NOT NULL,
  name VARCHAR(100) NULL,
  FOREIGN KEY (program_id) REFERENCES workout_programs(id) ON DELETE CASCADE,
  INDEX idx_program (program_id)
) ENGINE=InnoDB;

-- Упражнения в дне программы
CREATE TABLE IF NOT EXISTS program_exercises (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  day_id INT UNSIGNED NOT NULL,
  exercise_id INT UNSIGNED NOT NULL,
  sets_count INT UNSIGNED NOT NULL DEFAULT 3,
  reps_count INT UNSIGNED NULL,
  rest_sec INT UNSIGNED NULL,
  sort_order INT NOT NULL DEFAULT 0,
  FOREIGN KEY (day_id) REFERENCES program_days(id) ON DELETE CASCADE,
  FOREIGN KEY (exercise_id) REFERENCES exercises(id) ON DELETE CASCADE,
  INDEX idx_day (day_id)
) ENGINE=InnoDB;

-- Назначенные программы пользователям
CREATE TABLE IF NOT EXISTS user_programs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  program_id INT UNSIGNED NOT NULL,
  assigned_at DATETIME NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (program_id) REFERENCES workout_programs(id) ON DELETE CASCADE,
  UNIQUE KEY unique_user_program (user_id, program_id)
) ENGINE=InnoDB;

-- Дневник тренировок
CREATE TABLE IF NOT EXISTS workout_logs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  logged_at DATE NOT NULL,
  notes TEXT NULL,
  duration_min INT UNSIGNED NULL,
  feeling ENUM('excellent','good','normal','tired','exhausted') NULL,
  body_weight DECIMAL(5,2) NULL,
  created_at DATETIME NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user_date (user_id, logged_at DESC)
) ENGINE=InnoDB;

-- Записи упражнений в дневнике (exercise_id или exercise_name)
CREATE TABLE IF NOT EXISTS workout_log_exercises (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  log_id INT UNSIGNED NOT NULL,
  exercise_id INT UNSIGNED NULL,
  exercise_name VARCHAR(255) NULL,
  weight_kg DECIMAL(6,2) NULL,
  sets_count INT UNSIGNED NOT NULL DEFAULT 1,
  reps_count INT UNSIGNED NULL,
  sort_order INT NOT NULL DEFAULT 0,
  FOREIGN KEY (log_id) REFERENCES workout_logs(id) ON DELETE CASCADE,
  FOREIGN KEY (exercise_id) REFERENCES exercises(id) ON DELETE SET NULL,
  INDEX idx_log (log_id)
) ENGINE=InnoDB;

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

-- Начальные данные: популярные продукты
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

-- Прогресс (объёмы, силовые и т.д.)
CREATE TABLE IF NOT EXISTS progress_entries (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  logged_at DATE NOT NULL,
  weight_kg DECIMAL(5,2) NULL,
  chest_cm DECIMAL(5,2) NULL,
  waist_cm DECIMAL(5,2) NULL,
  hips_cm DECIMAL(5,2) NULL,
  biceps_cm DECIMAL(5,2) NULL,
  notes TEXT NULL,
  created_at DATETIME NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user_date (user_id, logged_at DESC)
) ENGINE=InnoDB;

-- Категории статей
CREATE TABLE IF NOT EXISTS article_categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  sort_order INT NOT NULL DEFAULT 0
) ENGINE=InnoDB;

-- Статьи
CREATE TABLE IF NOT EXISTS articles (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id INT UNSIGNED NULL,
  title VARCHAR(500) NOT NULL,
  slug VARCHAR(500) NOT NULL,
  excerpt TEXT NULL,
  cover_image VARCHAR(500) NULL,
  video_url VARCHAR(500) NULL,
  body LONGTEXT NOT NULL,
  published TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  FOREIGN KEY (category_id) REFERENCES article_categories(id) ON DELETE SET NULL,
  INDEX idx_category (category_id),
  INDEX idx_published (published),
  UNIQUE KEY unique_slug (slug(191))
) ENGINE=InnoDB;
