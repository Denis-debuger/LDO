<?php
declare(strict_types=1);

const AVATAR_MAX_SIZE = 2 * 1024 * 1024; // 2 MB
const AVATAR_DIR = __DIR__ . '/../../uploads/avatars';
const AVATAR_ALLOWED = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

function avatar_url(int $userId): ?string
{
  $profile = profile_get($userId);
  return $profile['avatar_url'] ?? null;
}

function avatar_path(?string $url): ?string
{
  if (!$url || strpos($url, 'avatars/') === false) return null;
  $base = realpath(__DIR__ . '/../..') ?: (__DIR__ . '/../..');
  return $base . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $url);
}

function avatar_upload(int $userId, array $file): array
{
  if (empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
    return ['ok' => false, 'error' => 'Файл не загружен.'];
  }

  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  $mime = finfo_file($finfo, $file['tmp_name']);
  finfo_close($finfo);

  if (!in_array($mime, AVATAR_ALLOWED, true)) {
    return ['ok' => false, 'error' => 'Допустимы только JPG, PNG, GIF, WebP.'];
  }

  if ($file['size'] > AVATAR_MAX_SIZE) {
    return ['ok' => false, 'error' => 'Максимум 2 МБ.'];
  }

  $dir = AVATAR_DIR;
  if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
  }

  $ext = match ($mime) {
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/gif' => 'gif',
    'image/webp' => 'webp',
    default => 'jpg',
  };

  $name = $userId . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
  $path = $dir . '/' . $name;

  if (!move_uploaded_file($file['tmp_name'], $path)) {
    return ['ok' => false, 'error' => 'Ошибка сохранения файла.'];
  }

  $oldProfile = profile_get($userId);
  $oldUrl = $oldProfile['avatar_url'] ?? null;
  if ($oldUrl) {
    $oldPath = avatar_path($oldUrl);
    if ($oldPath && is_file($oldPath)) unlink($oldPath);
  }

  $url = 'avatars/' . $name;
  db()->prepare('UPDATE user_profiles SET avatar_url = ?, updated_at = ? WHERE user_id = ?')
    ->execute([$url, now_dt(), $userId]);

  return ['ok' => true, 'url' => $url];
}

function avatar_remove(int $userId): void
{
  $profile = profile_get($userId);
  $url = $profile['avatar_url'] ?? null;
  if ($url) {
    $path = avatar_path($url);
    if ($path && is_file($path)) unlink($path);
  }
  db()->prepare('UPDATE user_profiles SET avatar_url = NULL, updated_at = ? WHERE user_id = ?')
    ->execute([now_dt(), $userId]);
}
