<?php
declare(strict_types=1);

function captcha_generate(string $key = 'default'): string
{
  $a = random_int(1, 9);
  $b = random_int(1, 9);
  $answer = (string)($a + $b);

  if (!isset($_SESSION['captcha']) || !is_array($_SESSION['captcha'])) {
    $_SESSION['captcha'] = [];
  }

  $_SESSION['captcha'][$key] = [
    'answer' => $answer,
    'question' => "Сколько будет {$a} + {$b}?",
  ];

  return $_SESSION['captcha'][$key]['question'];
}

function captcha_question(string $key = 'default'): string
{
  $item = $_SESSION['captcha'][$key] ?? null;
  if (!is_array($item) || !isset($item['question']) || !is_string($item['question'])) {
    return captcha_generate($key);
  }
  return $item['question'];
}

function captcha_validate(string $key, string $input): bool
{
  $item = $_SESSION['captcha'][$key] ?? null;
  if (!is_array($item) || !isset($item['answer']) || !is_string($item['answer'])) {
    return false;
  }

  $ok = hash_equals($item['answer'], trim($input));
  unset($_SESSION['captcha'][$key]);

  return $ok;
}
