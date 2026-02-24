<?php
declare(strict_types=1);

function request_ip(): string
{
  return (string)($_SERVER['REMOTE_ADDR'] ?? 'unknown');
}

function security_event(string $eventType, ?int $userId = null, ?string $email = null, array $meta = []): void
{
  try {
    $stmt = db()->prepare('INSERT INTO auth_security_events (event_type, user_id, email, ip_address, meta_json, created_at) VALUES (?,?,?,?,?,?)');
    $stmt->execute([$eventType, $userId, $email, request_ip(), $meta ? json_encode($meta, JSON_UNESCAPED_UNICODE) : null, now_dt()]);
  } catch (Throwable $e) {
    // ignore when migration not applied
  }
}

function admin_audit_log(string $action, string $targetType, ?int $targetId = null, array $changes = []): void
{
  if (!is_logged_in()) return;
  try {
    $stmt = db()->prepare('INSERT INTO admin_audit_logs (admin_user_id, action, target_type, target_id, ip_address, changes_json, created_at) VALUES (?,?,?,?,?,?,?)');
    $stmt->execute([auth_user_id(), $action, $targetType, $targetId, request_ip(), $changes ? json_encode($changes, JSON_UNESCAPED_UNICODE) : null, now_dt()]);
  } catch (Throwable $e) {
    // ignore when migration not applied
  }
}

function admin_audit_recent(int $limit = 100): array
{
  try {
    $stmt = db()->prepare('SELECT l.*, u.email AS admin_email FROM admin_audit_logs l LEFT JOIN users u ON u.id=l.admin_user_id ORDER BY l.created_at DESC LIMIT ?');
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
  } catch (Throwable $e) {
    return [];
  }
}

function security_panel_stats(int $days = 14): array
{
  try {
    $from = date('Y-m-d H:i:s', time() - ($days * 86400));
    $stmt = db()->prepare('SELECT event_type, COUNT(*) cnt FROM auth_security_events WHERE created_at >= ? GROUP BY event_type');
    $stmt->execute([$from]);
    $rows = $stmt->fetchAll();
    $map = [];
    foreach ($rows as $r) $map[$r['event_type']] = (int)$r['cnt'];
    return [
      'login_success' => $map['login_success'] ?? 0,
      'login_failed' => $map['login_failed'] ?? 0,
      'login_blocked' => $map['login_blocked'] ?? 0,
      'password_reset_request' => $map['password_reset_request'] ?? 0,
      'password_reset_success' => $map['password_reset_success'] ?? 0,
      'blocked_users' => (int)db()->query('SELECT COUNT(*) FROM users WHERE is_blocked=1')->fetchColumn(),
    ];
  } catch (Throwable $e) {
    return ['login_success'=>0,'login_failed'=>0,'login_blocked'=>0,'password_reset_request'=>0,'password_reset_success'=>0,'blocked_users'=>0];
  }
}
