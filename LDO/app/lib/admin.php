<?php
declare(strict_types=1);

function admin_users_list(int $page = 1, int $perPage = 20): array
{
  $offset = ($page - 1) * $perPage;
  $stmt = db()->prepare('SELECT u.id,u.email,u.role,u.is_blocked,u.created_at,up.avatar_url FROM users u LEFT JOIN user_profiles up ON u.id=up.user_id ORDER BY u.created_at DESC LIMIT ? OFFSET ?');
  $stmt->execute([$perPage, $offset]);
  return $stmt->fetchAll();
}

function admin_user_toggle_block(int $userId): bool
{
  $stmt = db()->prepare('UPDATE users SET is_blocked = NOT is_blocked WHERE id = ?');
  $stmt->execute([$userId]);
  return $stmt->rowCount() > 0;
}

function admin_user_delete(int $userId): bool
{
  $stmt = db()->prepare('DELETE FROM users WHERE id = ? AND role != "admin"');
  $stmt->execute([$userId]);
  return $stmt->rowCount() > 0;
}

function admin_programs_list(): array
{
  return db()->query('SELECT * FROM workout_programs ORDER BY name')->fetchAll();
}

function admin_program_add(string $name, string $level, ?string $description): int
{
  $stmt = db()->prepare('INSERT INTO workout_programs (name, level, description, created_at) VALUES (?,?,?,?)');
  $stmt->execute([$name, $level, $description ?: null, now_dt()]);
  return (int)db()->lastInsertId();
}

function admin_program_update(int $id, string $name, string $level, ?string $description): bool
{
  $stmt = db()->prepare('UPDATE workout_programs SET name=?, level=?, description=? WHERE id=?');
  $stmt->execute([$name, $level, $description ?: null, $id]);
  return $stmt->rowCount() > 0;
}

function admin_program_delete(int $id): bool
{
  $stmt = db()->prepare('DELETE FROM workout_programs WHERE id=?');
  $stmt->execute([$id]);
  return $stmt->rowCount() > 0;
}

function admin_exercises_list(): array
{
  return db()->query('SELECT e.*, c.name as category_name FROM exercises e LEFT JOIN exercise_categories c ON e.category_id=c.id ORDER BY c.sort_order, e.name')->fetchAll();
}

function admin_exercise_add(string $name, ?int $categoryId, ?string $description, ?string $technique, ?string $imageUrl, ?string $videoUrl): int
{
  $stmt = db()->prepare('INSERT INTO exercises (category_id, name, description, technique, image_url, video_url, created_at) VALUES (?,?,?,?,?,?,?)');
  $stmt->execute([$categoryId ?: null, $name, $description ?: null, $technique ?: null, $imageUrl ?: null, $videoUrl ?: null, now_dt()]);
  return (int)db()->lastInsertId();
}

function admin_exercise_update(int $id, string $name, ?int $categoryId, ?string $description, ?string $technique, ?string $imageUrl, ?string $videoUrl): bool
{
  $stmt = db()->prepare('UPDATE exercises SET category_id=?, name=?, description=?, technique=?, image_url=?, video_url=? WHERE id=?');
  $stmt->execute([$categoryId ?: null, $name, $description ?: null, $technique ?: null, $imageUrl ?: null, $videoUrl ?: null, $id]);
  return $stmt->rowCount() > 0;
}

function admin_exercise_delete(int $id): bool
{
  $stmt = db()->prepare('DELETE FROM exercises WHERE id=?');
  $stmt->execute([$id]);
  return $stmt->rowCount() > 0;
}

function admin_articles_list(bool $all = false): array
{
  $sql = 'SELECT a.*, c.name as category_name FROM articles a LEFT JOIN article_categories c ON a.category_id=c.id';
  if (!$all) $sql .= ' WHERE a.published=1';
  $sql .= ' ORDER BY a.created_at DESC';
  return db()->query($sql)->fetchAll();
}

function admin_article_get(int $id): ?array
{
  $stmt = db()->prepare('SELECT a.*, c.name as category_name FROM articles a LEFT JOIN article_categories c ON a.category_id=c.id WHERE a.id=? LIMIT 1');
  $stmt->execute([$id]);
  return $stmt->fetch() ?: null;
}

function admin_article_add(string $title, string $slug, ?int $categoryId, ?string $excerpt, string $body, ?string $coverImage, ?string $videoUrl, bool $published): int
{
  $stmt = db()->prepare('INSERT INTO articles (category_id, title, slug, excerpt, cover_image, video_url, body, published, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?,?,?)');
  $stmt->execute([$categoryId ?: null, $title, $slug, $excerpt ?: null, $coverImage ?: null, $videoUrl ?: null, $body, $published ? 1 : 0, now_dt(), now_dt()]);
  return (int)db()->lastInsertId();
}

function admin_article_update(int $id, string $title, string $slug, ?int $categoryId, ?string $excerpt, string $body, ?string $coverImage, ?string $videoUrl, bool $published): bool
{
  $stmt = db()->prepare('UPDATE articles SET category_id=?, title=?, slug=?, excerpt=?, cover_image=?, video_url=?, body=?, published=?, updated_at=? WHERE id=?');
  $stmt->execute([$categoryId ?: null, $title, $slug, $excerpt ?: null, $coverImage ?: null, $videoUrl ?: null, $body, $published ? 1 : 0, now_dt(), $id]);
  return $stmt->rowCount() > 0;
}

function admin_article_delete(int $id): bool
{
  $stmt = db()->prepare('DELETE FROM articles WHERE id=?');
  $stmt->execute([$id]);
  return $stmt->rowCount() > 0;
}

function admin_categories_list(string $type = 'article'): array
{
  $table = $type === 'exercise' ? 'exercise_categories' : 'article_categories';
  return db()->query("SELECT * FROM $table ORDER BY sort_order, name")->fetchAll();
}

function admin_category_add(string $type, string $name, int $sortOrder = 0): int
{
  $table = $type === 'exercise' ? 'exercise_categories' : 'article_categories';
  $stmt = db()->prepare("INSERT INTO $table (name, sort_order) VALUES (?,?)");
  $stmt->execute([$name, $sortOrder]);
  return (int)db()->lastInsertId();
}
