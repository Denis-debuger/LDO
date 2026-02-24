<?php
declare(strict_types=1);

const ARTICLE_MEDIA_MAX_SIZE = 10 * 1024 * 1024; // 10 MB
const ARTICLE_MEDIA_DIR = __DIR__ . '/../../uploads/articles';
const ARTICLE_MEDIA_ALLOWED_IMG = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
const ARTICLE_MEDIA_ALLOWED_VIDEO = ['video/mp4', 'video/webm'];

function article_media_upload(array $file, string $type = 'image'): array
{
  if (empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
    return ['ok' => false, 'error' => 'Файл не загружен.'];
  }

  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  $mime = finfo_file($finfo, $file['tmp_name']);
  finfo_close($finfo);

  $allowed = $type === 'video' ? ARTICLE_MEDIA_ALLOWED_VIDEO : ARTICLE_MEDIA_ALLOWED_IMG;
  if (!in_array($mime, $allowed, true)) {
    return ['ok' => false, 'error' => $type === 'video' ? 'Допустимы только MP4, WebM.' : 'Допустимы только JPG, PNG, GIF, WebP.'];
  }

  if ($file['size'] > ARTICLE_MEDIA_MAX_SIZE) {
    return ['ok' => false, 'error' => 'Максимум 10 МБ.'];
  }

  $dir = ARTICLE_MEDIA_DIR;
  if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
  }

  $ext = match ($mime) {
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/gif' => 'gif',
    'image/webp' => 'webp',
    'video/mp4' => 'mp4',
    'video/webm' => 'webm',
    default => ($type === 'video' ? 'mp4' : 'jpg'),
  };

  $name = bin2hex(random_bytes(12)) . '.' . $ext;
  $path = $dir . '/' . $name;

  if (!move_uploaded_file($file['tmp_name'], $path)) {
    return ['ok' => false, 'error' => 'Ошибка сохранения файла.'];
  }

  $url = 'articles/' . $name;
  return ['ok' => true, 'url' => $url];
}

function article_media_delete(?string $url): void
{
  if (!$url || strpos($url, 'articles/') === false) return;
  $base = realpath(__DIR__ . '/../..') ?: (__DIR__ . '/../..');
  $path = $base . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $url);
  if (is_file($path)) unlink($path);
}
