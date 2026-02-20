<?php
declare(strict_types=1);

function csrf_token(): string
{
  if (empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
  return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
  return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function csrf_validate(): void
{
  if (!is_post()) return;
  $sent = (string)($_POST['csrf_token'] ?? '');
  $good = (string)($_SESSION['csrf_token'] ?? '');
  if ($sent === '' || $good === '' || !hash_equals($good, $sent)) {
    http_response_code(419);
    echo 'CSRF validation failed.';
    exit;
  }
}
