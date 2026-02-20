<?php
declare(strict_types=1);

if (is_logged_in()) {
  redirect('profile');
}

$pageTitle = 'Регистрация';
$current = '';
$error = null;

if (is_post()) {
  csrf_validate();
  $email = (string)($_POST['email'] ?? '');
  $password = (string)($_POST['password'] ?? '');
  $password2 = (string)($_POST['password2'] ?? '');
  if ($password !== $password2) {
    $error = 'Пароли не совпадают.';
  } else {
    $result = auth_register($email, $password);
    if ($result['ok']) {
      auth_login($result['user_id'], 'user');
      flash_set('ok', 'Регистрация успешна!');
      redirect('profile');
    }
    $error = $result['error'] ?? 'Ошибка регистрации.';
  }
}

render('auth/register', compact('pageTitle', 'current', 'error'));
