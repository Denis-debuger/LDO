<?php
declare(strict_types=1);

function password_reset_request(string $email): ?string
{
  $user = user_by_email($email);
  if (!$user) return null;

  $token = bin2hex(random_bytes(32));
  $hash = hash('sha256', $token);
  $expires = date('Y-m-d H:i:s', time() + 3600);

  $stmt = db()->prepare('INSERT INTO password_resets (user_id, token_hash, expires_at, created_at) VALUES (?,?,?,?)');
  $stmt->execute([$user['id'], $hash, $expires, now_dt()]);

  return $token;
}

function password_reset_verify(string $token): ?int
{
  $hash = hash('sha256', $token);
  $stmt = db()->prepare('SELECT user_id FROM password_resets WHERE token_hash = ? AND expires_at > NOW() LIMIT 1');
  $stmt->execute([$hash]);
  $row = $stmt->fetch();
  return $row ? (int)$row['user_id'] : null;
}

function password_reset_use(string $token): void
{
  $hash = hash('sha256', $token);
  db()->prepare('DELETE FROM password_resets WHERE token_hash = ?')->execute([$hash]);
}
