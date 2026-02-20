-- Миграция: аватар и дневник с произвольными упражнениями
-- Выполните: mysql -u root ldo < db/migrations/001_avatar_and_diary.sql

USE ldo;

-- Аватар в профиле (пропустите если уже есть)
ALTER TABLE user_profiles ADD COLUMN avatar_url VARCHAR(500) NULL;

-- workout_log_exercises: снять FK, сделать exercise_id nullable, добавить exercise_name
-- Если FK с другим именем — найдите через SHOW CREATE TABLE workout_log_exercises
SET @fk = (SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE 
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'workout_log_exercises' 
  AND REFERENCED_TABLE_NAME = 'exercises' LIMIT 1);
SET @sql = IF(@fk IS NOT NULL, CONCAT('ALTER TABLE workout_log_exercises DROP FOREIGN KEY ', @fk), 'SELECT 1');
PREPARE st FROM @sql; EXECUTE st; DEALLOCATE PREPARE st;

ALTER TABLE workout_log_exercises MODIFY exercise_id INT UNSIGNED NULL;
ALTER TABLE workout_log_exercises ADD COLUMN exercise_name VARCHAR(255) NULL AFTER log_id;
ALTER TABLE workout_log_exercises ADD COLUMN sort_order INT NOT NULL DEFAULT 0 AFTER reps_count;

ALTER TABLE workout_log_exercises ADD CONSTRAINT fk_wle_exercise 
  FOREIGN KEY (exercise_id) REFERENCES exercises(id) ON DELETE SET NULL;
