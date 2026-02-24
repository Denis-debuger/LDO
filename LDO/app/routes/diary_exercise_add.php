<?php
declare(strict_types=1);

if (!is_post()) {
  redirect('diary');
}
csrf_validate();

$logId = (int)($_POST['log_id'] ?? 0);
$userId = auth_user_id();
$exerciseId = isset($_POST['exercise_id']) && $_POST['exercise_id'] !== '' ? (int)$_POST['exercise_id'] : null;
$exerciseName = clean_str((string)($_POST['exercise_name'] ?? ''));
$weight = isset($_POST['weight_kg']) ? (float)str_replace(',', '.', $_POST['weight_kg']) : null;
$sets = (int)($_POST['sets_count'] ?? 1);
$reps = isset($_POST['reps_count']) ? (int)$_POST['reps_count'] : null;

if ($exerciseId || $exerciseName) {
  diary_exercise_add($logId, $userId, $exerciseId, $exerciseName ?: null, $weight, max(1, $sets), $reps);
  flash_set('ok', 'Упражнение добавлено.');
}
redirect('diary-view', ['id' => $logId]);
