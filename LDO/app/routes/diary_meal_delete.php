<?php
declare(strict_types=1);

if (!is_post()) {
  redirect('diary');
}
csrf_validate();

$mealId = (int)($_POST['meal_id'] ?? 0);
$logId = (int)($_POST['log_id'] ?? 0);
$userId = auth_user_id();

if (meal_log_delete($mealId, $userId)) {
  flash_set('ok', 'Приём пищи удалён.');
}
redirect('diary-view', ['id' => $logId]);
