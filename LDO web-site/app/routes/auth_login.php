<?php
declare(strict_types=1);

if (is_logged_in()) {
  redirect('profile');
}

$pageTitle = 'Вход';
$current = '';
$error = null;

if (is_post()) {
  csrf_validate();
  $email = (string)($_POST['email'] ?? '');
  $password = (string)($_POST['password'] ?? '');
  $result = auth_attempt($email, $password);
  if ($result['ok']) {
    flash_set('ok', 'Добро пожаловать!');
    redirect('profile');
  }
  $error = $result['error'] ?? 'Ошибка входа.';
}

render('auth/login', compact('pageTitle', 'current', 'error'));
