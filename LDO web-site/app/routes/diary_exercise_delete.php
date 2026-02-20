<?php
declare(strict_types=1);

if (!is_post()) {
  redirect('diary');
}
csrf_validate();

$exId = (int)($_POST['ex_id'] ?? 0);
$logId = (int)($_POST['log_id'] ?? 0);
$userId = auth_user_id();

if (diary_exercise_delete($exId, $userId)) {
  flash_set('ok', 'Упражнение удалено.');
}
redirect('diary-view', ['id' => $logId]);
