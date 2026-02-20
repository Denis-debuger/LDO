<?php
declare(strict_types=1);

function auth_user_id(): ?int
{
  $id = $_SESSION['auth_user_id'] ?? null;
  return is_int($id) ? $id : (is_string($id) && ctype_digit($id) ? (int)$id : null);
}

function auth_role(): ?string
{
  $r = $_SESSION['auth_role'] ?? null;
  return is_string($r) ? $r : null;
}

function is_logged_in(): bool
{
  return auth_user_id() !== null;
}

function require_login(): void
{
  if (!is_logged_in()) {
    flash_set('err', 'Сначала войдите в систему.');
    redirect('login');
  }
}

function require_admin(): void
{
  require_login();
  if (auth_role() !== 'admin') {
    http_response_code(403);
    echo 'Доступ запрещён.';
    exit;
  }
}

function auth_logout(): void
{
  $_SESSION = [];
  if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
  }
  session_destroy();
}

function auth_login(int $userId, string $role): void
{
  session_regenerate_id(true);
  $_SESSION['auth_user_id'] = $userId;
  $_SESSION['auth_role'] = $role;
}

function user_by_email(string $email): ?array
{
  $stmt = db()->prepare('SELECT id,email,password_hash,role,is_blocked FROM users WHERE email = ? LIMIT 1');
  $stmt->execute([$email]);
  $row = $stmt->fetch();
  return $row ?: null;
}

function user_by_id(int $id): ?array
{
  $stmt = db()->prepare('SELECT id,email,role,is_blocked,created_at FROM users WHERE id = ? LIMIT 1');
  $stmt->execute([$id]);
  $row = $stmt->fetch();
  return $row ?: null;
}

function auth_register(string $email, string $password): array
{
  $email = strtolower(clean_str($email));

  if (!valid_email($email)) return ['ok' => false, 'error' => 'Некорректный email.'];
  if (!valid_password($password)) return ['ok' => false, 'error' => 'Пароль должен быть не короче 8 символов.'];

  if (user_by_email($email)) return ['ok' => false, 'error' => 'Пользователь с таким email уже существует.'];

  $hash = password_hash($password, PASSWORD_BCRYPT);
  $stmt = db()->prepare('INSERT INTO users (email,password_hash,role,is_blocked,created_at) VALUES (?,?,?,?,?)');
  $stmt->execute([$email, $hash, 'user', 0, now_dt()]);

  $userId = (int)db()->lastInsertId();

  $stmt2 = db()->prepare('INSERT INTO user_profiles (user_id,created_at,updated_at) VALUES (?,?,?)');
  $stmt2->execute([$userId, now_dt(), now_dt()]);

  return ['ok' => true, 'user_id' => $userId];
}

function auth_attempt(string $email, string $password): array
{
  $email = strtolower(clean_str($email));
  if (!valid_email($email)) return ['ok' => false, 'error' => 'Некорректный email.'];

  $user = user_by_email($email);
  if (!$user) return ['ok' => false, 'error' => 'Неверный email или пароль.'];

  if (!empty($user['is_blocked'])) return ['ok' => false, 'error' => 'Аккаунт заблокирован.'];

  if (!password_verify($password, $user['password_hash'])) return ['ok' => false, 'error' => 'Неверный email или пароль.'];

  auth_login((int)$user['id'], $user['role']);
  return ['ok' => true];
}
