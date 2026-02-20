<?php
declare(strict_types=1);

function profile_get(int $userId): ?array
{
  $stmt = db()->prepare('SELECT * FROM user_profiles WHERE user_id = ? LIMIT 1');
  $stmt->execute([$userId]);
  return $stmt->fetch() ?: null;
}

function profile_update(int $userId, array $data): void
{
  $stmt = db()->prepare('
    UPDATE user_profiles SET
      height_cm = ?, weight_kg = ?, age = ?, gender = ?, activity_level = ?, goal = ?,
      updated_at = ?
    WHERE user_id = ?
  ');
  $stmt->execute([
    $data['height_cm'] ?? null,
    $data['weight_kg'] ?? null,
    $data['age'] ?? null,
    $data['gender'] ?? null,
    $data['activity_level'] ?? 'moderate',
    $data['goal'] ?? 'maintain',
    now_dt(),
    $userId,
  ]);
}

function weight_log_add(int $userId, float $weight, string $date): void
{
  $stmt = db()->prepare('INSERT INTO weight_logs (user_id, weight_kg, logged_at, created_at) VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE weight_kg = VALUES(weight_kg)');
  $stmt->execute([$userId, $weight, $date, now_dt()]);
}

function weight_log_list(int $userId, int $limit = 30): array
{
  $stmt = db()->prepare('SELECT logged_at, weight_kg FROM weight_logs WHERE user_id = ? ORDER BY logged_at DESC LIMIT ?');
  $stmt->execute([$userId, $limit]);
  return $stmt->fetchAll();
}
