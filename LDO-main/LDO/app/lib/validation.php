<?php
declare(strict_types=1);

function valid_email(string $email): bool
{
  return (bool)filter_var($email, FILTER_VALIDATE_EMAIL);
}

function valid_password(string $password): bool
{
  return mb_strlen($password) >= 8;
}

function clean_str(string $s): string
{
  return trim(preg_replace('/\s+/', ' ', $s) ?? '');
}
