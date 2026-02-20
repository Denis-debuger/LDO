<?php
declare(strict_types=1);

if (!is_post()) {
  redirect('diary');
}
csrf_validate();

$id = (int)($_POST['id'] ?? 0);
$userId = auth_user_id();

if (diary_log_delete($id, $userId)) {
  flash_set('ok', 'Тренировка удалена.');
}
redirect('diary');
