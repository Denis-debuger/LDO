<?php
declare(strict_types=1);

// Basic hardening (still configure Apache/PHP properly in production).
ini_set('display_errors', '1');
error_reporting(E_ALL);

date_default_timezone_set('Europe/Moscow');

$isHttps = (!empty($_SERVER['HTTPS']) && strtolower((string)$_SERVER['HTTPS']) !== 'off')
  || (isset($_SERVER['SERVER_PORT']) && (string)$_SERVER['SERVER_PORT'] === '443');

session_set_cookie_params([
  'lifetime' => 0,
  'path' => '/',
  'secure' => $isHttps,
  'httponly' => true,
  'samesite' => 'Lax',
]);
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

require_once __DIR__ . '/config.php';

try {
  require_once __DIR__ . '/lib/db.php';
  db();
} catch (Throwable $e) {
  if (php_sapi_name() === 'cli') throw $e;
  http_response_code(500);
  header('Content-Type: text/html; charset=utf-8');
  echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>LDO</title></head><body style="background:#111;color:#fff;font-family:sans-serif;padding:24px">';
  echo '<h1>Ошибка базы данных</h1><p>Выполните <code>db/schema.sql</code> в MySQL и настройте <code>app/config.php</code>.</p>';
  echo '<pre>' . htmlspecialchars($e->getMessage()) . '</pre></body></html>';
  exit;
}
require_once __DIR__ . '/lib/helpers.php';
require_once __DIR__ . '/lib/flash.php';
require_once __DIR__ . '/lib/csrf.php';
require_once __DIR__ . '/lib/validation.php';
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/lib/kbju.php';
require_once __DIR__ . '/lib/profile.php';
require_once __DIR__ . '/lib/avatar.php';
require_once __DIR__ . '/lib/diary.php';
require_once __DIR__ . '/lib/admin.php';
require_once __DIR__ . '/lib/article_media.php';
require_once __DIR__ . '/lib/statistics.php';
require_once __DIR__ . '/lib/food.php';
require_once __DIR__ . '/lib/password_reset.php';

// Ensure CSRF token exists for forms.
csrf_token();
