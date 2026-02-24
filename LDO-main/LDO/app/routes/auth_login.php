<?php
declare(strict_types=1);

if (is_logged_in()) {
  redirect('profile');
}

$pageTitle = 'Вход';
$current = '';
$error = null;
$captchaKey = 'login';

if (is_post()) {
  csrf_validate();
  $email = (string)($_POST['email'] ?? '');
  $password = (string)($_POST['password'] ?? '');
  $captchaValue = (string)($_POST['captcha'] ?? '');

  if (!captcha_validate($captchaKey, $captchaValue)) {
    $error = 'Неверно решена CAPTCHA.';
    $captchaQuestion = captcha_generate($captchaKey);
    render('auth/login', compact('pageTitle', 'current', 'error', 'captchaQuestion'));
    return;
  }

  $result = auth_attempt($email, $password);
  if ($result['ok']) {
    flash_set('ok', 'Добро пожаловать!');
    redirect('profile');
  }
  $error = $result['error'] ?? 'Ошибка входа.';
}

$captchaQuestion = captcha_question($captchaKey);
render('auth/login', compact('pageTitle', 'current', 'error', 'captchaQuestion'));
