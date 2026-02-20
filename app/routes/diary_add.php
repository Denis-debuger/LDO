<?php
declare(strict_types=1);

if (!is_post()) {
  redirect('diary');
}
csrf_validate();

$userId = auth_user_id();
$date = (string)($_POST['logged_at'] ?? '');
$notes = clean_str((string)($_POST['notes'] ?? ''));

if ($date === '') $date = today();

$id = diary_log_add($userId, $date, $notes ?: null);
flash_set('ok', 'Тренировка добавлена.');
redirect('diary-view', ['id' => $id]);
