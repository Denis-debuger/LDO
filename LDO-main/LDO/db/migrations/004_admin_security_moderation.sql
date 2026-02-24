-- Admin moderation, security panel, categories and maintenance support

CREATE TABLE IF NOT EXISTS moderation_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  type ENUM('comment','ugc') NOT NULL DEFAULT 'comment',
  user_id INT UNSIGNED NULL,
  content TEXT NOT NULL,
  status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  moderated_by INT UNSIGNED NULL,
  moderated_at DATETIME NULL,
  created_at DATETIME NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  FOREIGN KEY (moderated_by) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_status_created (status, created_at DESC)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS admin_audit_logs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  admin_user_id INT UNSIGNED NOT NULL,
  action VARCHAR(120) NOT NULL,
  target_type VARCHAR(120) NOT NULL,
  target_id INT UNSIGNED NULL,
  ip_address VARCHAR(64) NULL,
  changes_json JSON NULL,
  created_at DATETIME NOT NULL,
  FOREIGN KEY (admin_user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_created (created_at DESC)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS auth_security_events (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  event_type VARCHAR(80) NOT NULL,
  user_id INT UNSIGNED NULL,
  email VARCHAR(255) NULL,
  ip_address VARCHAR(64) NULL,
  meta_json JSON NULL,
  created_at DATETIME NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_type_created (event_type, created_at DESC)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS food_categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL,
  UNIQUE KEY unique_name (name)
) ENGINE=InnoDB;

ALTER TABLE food_items ADD COLUMN IF NOT EXISTS category_id INT UNSIGNED NULL;
ALTER TABLE food_items ADD INDEX IF NOT EXISTS idx_food_category (category_id);
ALTER TABLE food_items ADD CONSTRAINT fk_food_category FOREIGN KEY (category_id) REFERENCES food_categories(id) ON DELETE SET NULL;

CREATE TABLE IF NOT EXISTS content_categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  type VARCHAR(50) NOT NULL DEFAULT 'general',
  sort_order INT NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL,
  INDEX idx_type_sort (type, sort_order)
) ENGINE=InnoDB;
