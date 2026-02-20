<?php
declare(strict_types=1);

function diary_logs(int $userId, int $limit = 50): array
{
  $stmt = db()->prepare('SELECT wl.id,wl.logged_at,wl.notes,
    (SELECT COUNT(*) FROM workout_log_exercises WHERE log_id=wl.id) as ex_count
    FROM workout_logs wl WHERE wl.user_id = ? ORDER BY wl.logged_at DESC LIMIT ?');
  $stmt->execute([$userId, $limit]);
  return $stmt->fetchAll();
}

function diary_log_get(int $id, int $userId): ?array
{
  $stmt = db()->prepare('SELECT * FROM workout_logs WHERE id = ? AND user_id = ? LIMIT 1');
  $stmt->execute([$id, $userId]);
  return $stmt->fetch() ?: null;
}

function diary_log_exercises(int $logId): array
{
  $stmt = db()->prepare('
    SELECT wle.*, e.name as exercise_title
    FROM workout_log_exercises wle
    LEFT JOIN exercises e ON e.id = wle.exercise_id
    WHERE wle.log_id = ?
    ORDER BY wle.sort_order, wle.id
  ');
  $stmt->execute([$logId]);
  $rows = $stmt->fetchAll();
  foreach ($rows as &$r) {
    $r['display_name'] = $r['exercise_title'] ?? $r['exercise_name'] ?? 'Упражнение';
  }
  return $rows;
}

function diary_log_add(int $userId, string $date, ?string $notes): int
{
  $stmt = db()->prepare('INSERT INTO workout_logs (user_id, logged_at, notes, created_at) VALUES (?,?,?,?)');
  $stmt->execute([$userId, $date, $notes ?: null, now_dt()]);
  return (int)db()->lastInsertId();
}

function diary_log_update(int $id, int $userId, string $date, ?string $notes, ?int $durationMin = null, ?string $feeling = null, ?float $bodyWeight = null): bool
{
  $stmt = db()->prepare('UPDATE workout_logs SET logged_at=?, notes=?, duration_min=?, feeling=?, body_weight=? WHERE id=? AND user_id=?');
  $stmt->execute([$date, $notes ?: null, $durationMin ?: null, $feeling ?: null, $bodyWeight ?: null, $id, $userId]);
  return $stmt->rowCount() > 0;
}

function diary_log_delete(int $id, int $userId): bool
{
  $stmt = db()->prepare('DELETE FROM workout_logs WHERE id=? AND user_id=?');
  $stmt->execute([$id, $userId]);
  return $stmt->rowCount() > 0;
}

function diary_exercise_add(int $logId, int $userId, ?int $exerciseId, ?string $exerciseName, ?float $weight, int $sets, ?int $reps): bool
{
  $log = diary_log_get($logId, $userId);
  if (!$log) return false;
  if (!$exerciseId && !$exerciseName) return false;

  $maxOrder = db()->prepare('SELECT COALESCE(MAX(sort_order),0) FROM workout_log_exercises WHERE log_id=?');
  $maxOrder->execute([$logId]);
  $order = (int)$maxOrder->fetchColumn() + 1;

  $stmt = db()->prepare('INSERT INTO workout_log_exercises (log_id, exercise_id, exercise_name, weight_kg, sets_count, reps_count, sort_order) VALUES (?,?,?,?,?,?,?)');
  $stmt->execute([$logId, $exerciseId ?: null, $exerciseName ?: null, $weight ?: null, max(1, $sets), $reps ?: null, $order]);
  return true;
}

function diary_exercise_delete(int $exId, int $userId): bool
{
  $stmt = db()->prepare('DELETE wle FROM workout_log_exercises wle JOIN workout_logs wl ON wl.id=wle.log_id WHERE wle.id=? AND wl.user_id=?');
  $stmt->execute([$exId, $userId]);
  return $stmt->rowCount() > 0;
}
