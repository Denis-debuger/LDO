<?php
declare(strict_types=1);

$pageTitle = 'Восстановление пароля';
$current = '';
$error = null;
$resetLink = null;
$token = (string)($_GET['token'] ?? '');
$captchaKey = 'password_reset_request';

if ($token !== '') {
  if (is_post()) {
    csrf_validate();
    $password = (string)($_POST['password'] ?? '');
    $password2 = (string)($_POST['password2'] ?? '');
    if (!valid_password($password)) {
      $error = 'Пароль должен быть не короче 8 символов.';
    } elseif ($password !== $password2) {
      $error = 'Пароли не совпадают.';
    } else {
      $userId = password_reset_verify($token);
      if ($userId) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        db()->prepare('UPDATE users SET password_hash = ? WHERE id = ?')->execute([$hash, $userId]);
        password_reset_use($token);
        flash_set('ok', 'Пароль изменён. Войдите в систему.');
        redirect('login');
      }
      $error = 'Ссылка недействительна или истекла.';
    }
  }
  render('auth/reset_form', compact('pageTitle', 'current', 'error', 'token'));
} else {
  if (is_post()) {
    csrf_validate();
    $email = strtolower(clean_str((string)($_POST['email'] ?? '')));
    $captchaValue = (string)($_POST['captcha'] ?? '');

    if (!captcha_validate($captchaKey, $captchaValue)) {
      $error = 'Неверно решена CAPTCHA.';
    } elseif (!valid_email($email)) {
      $error = 'Некорректный email.';
    } else {
      $token = password_reset_request($email);
      if ($token) {
        $resetLink = url('password-reset', ['token' => $token]);
      }
      flash_set('ok', 'Если email зарегистрирован, проверьте почту. В dev-режиме ссылка показана ниже.');
    }
  }
  $captchaQuestion = captcha_question($captchaKey);
  render('auth/reset_request', compact('pageTitle', 'current', 'error', 'resetLink', 'captchaQuestion'));
}
