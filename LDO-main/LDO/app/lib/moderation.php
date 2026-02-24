<?php
declare(strict_types=1);

function moderation_items(string $type = 'all', string $status = 'pending'): array
{
  $where = [];
  $params = [];
  if ($type !== 'all') { $where[] = 'type = ?'; $params[] = $type; }
  if ($status !== 'all') { $where[] = 'status = ?'; $params[] = $status; }
  $sql = 'SELECT m.*, u.email FROM moderation_items m LEFT JOIN users u ON u.id=m.user_id';
  if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
  $sql .= ' ORDER BY m.created_at DESC LIMIT 200';
  $stmt = db()->prepare($sql);
  $stmt->execute($params);
  return $stmt->fetchAll();
}

function moderation_update_status(int $id, string $status): bool
{
  $stmt = db()->prepare('UPDATE moderation_items SET status=?, moderated_by=?, moderated_at=? WHERE id=?');
  $stmt->execute([$status, auth_user_id(), now_dt(), $id]);
  return $stmt->rowCount() > 0;
}

function moderation_add_item(string $type, ?int $userId, string $content, string $status = 'pending'): int
{
  $stmt = db()->prepare('INSERT INTO moderation_items (type,user_id,content,status,created_at) VALUES (?,?,?,?,?)');
  $stmt->execute([$type, $userId, $content, $status, now_dt()]);
  return (int)db()->lastInsertId();
}
