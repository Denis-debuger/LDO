<?php
declare(strict_types=1);

function base_path(): string
{
  $dir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
  return $dir === '' ? '/' : $dir . '/';
}

function url(string $route, array $params = []): string
{
  $params = array_merge(['r' => $route], $params);
  return base_path() . 'index.php?' . http_build_query($params);
}

function redirect(string $route, array $params = []): void
{
  header('Location: ' . url($route, $params));
  exit;
}

function e(?string $value): string
{
  return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function render(string $view, array $data = []): void
{
  $viewFile = __DIR__ . '/../views/' . $view . '.php';
  if (!is_file($viewFile)) {
    http_response_code(500);
    echo 'View not found.';
    exit;
  }

  extract($data, EXTR_SKIP);
  ob_start();
  require $viewFile;
  $content = (string)ob_get_clean();

  require __DIR__ . '/../views/layout.php';
}

function request_method(): string
{
  return strtoupper((string)($_SERVER['REQUEST_METHOD'] ?? 'GET'));
}

function is_post(): bool
{
  return request_method() === 'POST';
}

function now_dt(): string
{
  return date('Y-m-d H:i:s');
}

function today(): string
{
  return date('Y-m-d');
}

function asset_url(string $path): string
{
  return base_path() . 'assets/' . ltrim($path, '/');
}

function upload_url(string $path): string
{
  return base_path() . 'uploads/' . ltrim($path, '/');
}
