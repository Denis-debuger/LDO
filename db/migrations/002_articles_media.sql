-- Миграция: медиа в статьях
-- Выполните: mysql -u root ldo < db/migrations/002_articles_media.sql

USE ldo;

ALTER TABLE articles ADD COLUMN cover_image VARCHAR(500) NULL AFTER excerpt;
ALTER TABLE articles ADD COLUMN video_url VARCHAR(500) NULL AFTER cover_image;
